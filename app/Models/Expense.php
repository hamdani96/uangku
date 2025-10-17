<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getDateAttribute($created_at) {
        return Carbon::parse($created_at)->format('Y-m-d H:i');
    }

    public function getAmountAttribute($amount) {
        return number_format($amount, 0, ',', '.');
    }
}
