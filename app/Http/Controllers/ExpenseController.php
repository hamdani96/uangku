<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\UserPin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index($pin)
    {
        $user = UserPin::where('pin', $pin)->first();

        if (!$user) {
            return redirect()->route('pin');
        }

        return view('list', compact('user'));
    }

    public function insight($pin)
    {
        $user = UserPin::where('pin', $pin)->first();

        if (!$user) {
            return redirect()->route('pin');
        }

        return view('insight', compact('user'));
    }

    public function list($pin, Request $request)
    {
        $user = UserPin::where('pin', $pin)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid PIN'], 404);
        }

        $sumExpense = Expense::where('user_pin_id', $user->user_pin_id);
        $expenses = Expense::where('user_pin_id', $user->user_pin_id)->orderBy('date', 'desc');

        if ($request->type == 'custom') {
            if ($request->start_date == null) {
                $today = Carbon::today();

                if ($today->day >= 25) {
                    // periode 25 bulan ini → 24 bulan depan
                    $startDate = Carbon::create($today->year, $today->month, 25);
                    $endDate   = (clone $startDate)->addMonth();
                } else {
                    // periode 25 bulan lalu → 24 bulan ini
                    $startDate = Carbon::create($today->year, $today->month, 25)->subMonth();
                    $endDate   = (clone $startDate)->addMonth();
                }

                $dateId = TanggalID('j M Y', $startDate) . ' - ' . TanggalID('j M Y', $endDate);

                $expenses = $expenses->whereBetween('date', [$startDate, $endDate])->get();
                $sumExpense = $sumExpense->whereBetween('date', [$startDate, $endDate])->sum('amount');
            } else {
                if ($request->end_date == null) {
                    $startDate = $request->start_date;

                    $dateId = TanggalID('j M Y', $startDate);

                    $expenses = $expenses->whereDate('date', $startDate)->get();
                    $sumExpense = $sumExpense->whereDate('date', $startDate)->sum('amount');
                } else {
                    $startDate = $request->start_date;
                    $endDate = Carbon::parse($request->end_date)->addDay();

                    $dateId = TanggalID('j M Y', $startDate) . ' - ' . TanggalID('j M Y', $endDate);

                    $expenses = $expenses->whereBetween('date', [$startDate, $endDate])->get();
                    $sumExpense = $sumExpense->whereBetween('date', [$startDate, $endDate])->sum('amount');
                }
            }
        } elseif ($request->type == 'monthly') {
            $today = Carbon::today();

            if ($today->day >= 25) {
                // periode 25 bulan ini → 24 bulan depan
                $startDate = Carbon::create($today->year, $today->month, 25);
                $endDate   = (clone $startDate)->addMonth();
            } else {
                // periode 25 bulan lalu → 24 bulan ini
                $startDate = Carbon::create($today->year, $today->month, 25)->subMonth();
                $endDate   = (clone $startDate)->addMonth();
            }

            $dateId = TanggalID('j M Y', $startDate) . ' - ' . TanggalID('j M Y', $endDate);

            $expenses = $expenses->whereBetween('date', [$startDate, $endDate])->get();
            $sumExpense = $sumExpense->whereBetween('date', [$startDate, $endDate])->sum('amount');
        } elseif ($request->type == 'today') {
            $dateId = TanggalID('j M Y', Carbon::now());

            $expenses = $expenses->whereDate('date', Carbon::now())->get();
            $sumExpense = $sumExpense->whereDate('date', Carbon::now())->sum('amount');
        }

        return response()->json([
            'dateId'        => $dateId,
            'sumExpense'    => number_format($sumExpense, 0, ',', '.'),
            'expenses'      => $expenses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_pin_id' => 'required|exists:user_pins,user_pin_id',
            'title' => 'required|string|max:255',
            'amount' => 'required|integer|min:0',
            'date' => 'required|date_format:Y-m-d H:i',
            'category' => 'required|string|max:100',
        ]);

        $amount = str_replace(',', '', $request->amount);

        $expense = Expense::create([
            'user_pin_id' => $request->user_pin_id,
            'title' => $request->title,
            'amount' => $amount,
            'date' => $request->date,
            'category' => $request->category,
        ]);

        return response()->json(['message' => 'Expense added successfully', 'expense' => $expense], 201);
    }

    public function delete($id)
    {
        Expense::where('expense_id', $id)->delete();

        return response()->json(['message' => 'success'], 201);
    }

    public function circleChart($id, Request $request)
    {
        $user = UserPin::where('user_pin_id', $id)->first();

        if (!$user) {
            return redirect()->route('pin');
        }

        if ($request->start_date == null) {
            $today = Carbon::today();

            if ($today->day >= 25) {
                $startDate = Carbon::create($today->year, $today->month, 25);
                $endDate   = (clone $startDate)->addMonth();
            } else {
                $startDate = Carbon::create($today->year, $today->month, 25)->subMonth();
                $endDate   = (clone $startDate)->addMonth();
            }
        } else {
            if ($request->end_date == null) {
                $startDate = $request->start_date;
                $endDate = Carbon::parse($request->start_date)->addDay();
            } else {
                $startDate = $request->start_date;
                $endDate = Carbon::parse($request->end_date)->addDay();
            }
        }

        $categories = ['tempat_tinggal', 'makanan', 'jajan', 'olahraga', 'lainnya', 'transportasi', 'hiburan'];
        $expenses = [];

        foreach ($categories as $category) {
            $expenses[$category] = Expense::where('user_pin_id', $user->user_pin_id)
                ->where('category', $category)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
        }

        // Total semua pengeluaran
        $total = array_sum($expenses);

        // Siapkan data chart + persentase
        $chartCircle = [];
        foreach ($expenses as $key => $amount) {
            $percentage = $total > 0 ? ($amount / $total) * 100 : 0;
            $chartCircle[] = [
                'name' => ucfirst(str_replace('_', ' ', $key)),
                'y' => (int) $amount,
                'percentage' => round($percentage, 2) 
            ];
        }

        return response()->json(['chartCircle' => $chartCircle]);
    }

    public function barChart($id, Request $request)
    {
        $user = UserPin::where('user_pin_id', $id)->first();

        if (!$user) {
            return redirect()->route('pin');
        }

        if ($request->start_date == null) {
            $today = Carbon::today();

            if ($today->day >= 25) {
                $startDate = Carbon::create($today->year, $today->month, 25);
                $endDate   = (clone $startDate)->addMonth();
            } else {
                $startDate = Carbon::create($today->year, $today->month, 25)->subMonth();
                $endDate   = (clone $startDate)->addMonth();
            }
        } else {
            if ($request->end_date == null) {
                $startDate = $request->start_date;
                $endDate = Carbon::parse($request->start_date)->addDay();
            } else {
                $startDate = $request->start_date;
                $endDate = Carbon::parse($request->end_date)->addDay();
            }
        }

        // Ambil data pengeluaran per kategori
        $categories = ['tempat_tinggal', 'makanan', 'jajan', 'olahraga', 'lainnya', 'transportasi', 'hiburan'];
        $expenses = [];

        foreach ($categories as $category) {
            $expenses[$category] = (int) Expense::where('user_pin_id', $user->user_pin_id)
                ->where('category', $category)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
        }

        return response()->json([
            'categories' => $categories,
            'expenses' => array_values($expenses)
        ]);
    }
}
