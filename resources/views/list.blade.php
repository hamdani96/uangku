<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00A86B">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo/icon-512x512.png') }}">
    <title>Pengeluaran</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
</head>

<body>

    <div class="mobile-wrapper">

        {{-- Navbar --}}
        <div class="bottom-nav" id="navbar">
            <a href="{{ route('insight.index', $user->pin) }}" class="nav-item @if (Request::is('insight/*')) active @endif">
                <i class="fa-solid fa-chart-pie"></i>
            </a>
            <a href="{{ route('expense.index', $user->pin) }}" class="nav-item @if (Request::is('expense/*')) active @endif">
                <i class="fa-solid fa-paw"></i>
            </a>
            <button class="nav-item add-btn" data-bs-toggle="offcanvas" data-bs-target="#tambahData" aria-controls="tambahData" style="border: none;">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        {{-- End Navbar --}}

        {{-- Tambah Form --}}
        <div class="offcanvas offcanvas-bottom" style="height: 55vh" tabindex="-1" id="tambahData"
            aria-labelledby="tambahDataLabel">
            <div class="offcanvas-header">
                <h6 class="offcanvas-title" id="tambahDataLabel">Tambah Pengeluaran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body small">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" required
                            placeholder="Judul Pengeluaran" />
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="amount" required
                            placeholder="Masukan Jumlah" />
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="text" class="form-control" id="date-input" required
                            placeholder="Pilih tanggal" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Kategori</label>
                        <select class="form-select" id="category" aria-label="Default select example">
                            <option selected>Pilih Kategori</option>
                            <option value="makanan">Makanan</option>
                            <option value="tempat_tinggal">Tempat Tinggal</option>
                            <option value="jajan">Jajan</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="transportasi">Transportasi</option>
                            <option value="hiburan">Hiburan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success w-100 me-2" id="btn-save">Simpan</button>
                </div>
            </div>
        </div>
        {{-- Tambah Form --}}

        <!-- HEADER -->
        <div class="header-box">
            <h6 class="m-0 me-0 fw-bold">Hai, {{ $user->name }}</h6>
            <small class="m-0 me-0">Catat pengeluaran mu sekarang</small>

            <div class="text-center">
                <h2 class="mt-4 mb-0 fw-bold" id="sum-expense"></h2>
                <small small class="text-center" id="range-date"></small>
            </div>
        </div>

        <div class="container mt-4">

            <!-- NAV TABS -->
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" id="bulan-tab" data-bs-toggle="tab"
                        data-bs-target="#bulan" type="button" role="tab">Bulan ini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" id="hari-ini-tab" data-bs-toggle="tab"
                        data-bs-target="#hari-ini" type="button" role="tab">Hari ini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" id="custom-tab" data-bs-toggle="tab"
                        data-bs-target="#custom" type="button" role="tab">Custom</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <!-- BULAN INI -->
                <div class="tab-pane fade show active" id="bulan" role="tabpanel" aria-labelledby="bulan-tab">
                </div>

                {{-- HARI INI --}}
                <div class="tab-pane fade" id="hari-ini" role="tabpanel" aria-labelledby="hari-ini-tab">
                </div>

                {{-- CUSTOM --}}
                <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                    <input type="text" class="form-control mt-2 mb-2" id="filter-date"
                        placeholder="Select Date.." />
                    <div id="custom-list">
                    </div>
                </div>

                {{-- Canvas action --}}
                {{-- Action Data --}}
                <div class="offcanvas offcanvas-top" style="height: 20vh" tabindex="-1" id="actionData"
                    aria-labelledby="actionDataLabel">
                    <div class="offcanvas-header">
                        <h6 class="offcanvas-title" id="actionDataLabel">Aksi</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body small text-center">
                        <br>
                        <input type="text" id="expense-id" hidden>
                        <button type="button" class="btn btn-danger rounded-circle shadow-lg"
                            style="width: 60px; height: 60px;" id="btn-delete"><i
                                class="fa-solid fa-trash text-white"></i></button>
                    </div>
                </div>
                {{-- Action Data --}}

            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script>
        flatpickr("#filter-date", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function() {
                getExpense(type = 'custom');
            }
        });

        flatpickr("#date-input", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $('#bulan-tab').on('shown.bs.tab', function() {
            $('#filter-date').val('');
            var type = 'monthly';
            getExpense(type);
        });

        $('#hari-ini-tab').on('shown.bs.tab', function() {
            $('#filter-date').val('');
            var type = 'today';
            getExpense(type);
        });

        $('#custom-tab').on('shown.bs.tab', function() {
            var type = 'custom';
            getExpense(type);
        });

        function getExpense(type) {
            var pin = '{{ $user->pin }}';
            var type = type || 'monthly';
            var dateRange = $('#filter-date').val();
            var startDate = '';
            var endDate = '';

            if (dateRange) {
                let dates = dateRange.split(" to ");
                startDate = dates[0] ?? '';
                endDate = dates[1] ?? '';
            }

            $.ajax({
                url: '/expense/list/' + pin,
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    type: type
                },
                success: function(response) {
                    console.log(response);

                    $('#sum-expense').text('Rp. ' + response.sumExpense);
                    $('#range-date').text(response.dateId);

                    let listData = '';

                    $.each(response.expenses, function(index, value) {

                        if (value.category == 'makanan') {
                            value.category = 'Makanan';
                        } else if (value.category == 'tempat_tinggal') {
                            value.category = 'Tempat Tinggal';
                        } else if (value.category == 'jajan') {
                            value.category = 'Jajan';
                        } else if (value.category == 'olahraga') {
                            value.category = 'Olahraga';
                        } else if (value.category == 'transportasi') {
                            value.category = 'Transportasi';
                        } else if (value.category == 'hiburan') {
                            value.category = 'Hiburan';
                        } else if (value.category == 'lainnya') {
                            value.category = 'Lainnya';
                        }

                        listData += `
                        <div class="expense-card" data-bs-toggle="offcanvas" data-bs-target="#actionData"
                          aria-controls="actionData" data-expense-id="${value.expense_id}">
                          <span class="badge-category">${value.category}</span>
                          <p class="mb-1 mt-2">${value.title}</p>
                          <div class="d-flex justify-content-between">
                              <span class="expense-price">Rp. ${value.amount}</span>
                              <span class="expense-date">${value.date}</span>
                          </div>
                        </div>
                      `;
                    });

                    if (type == 'monthly') {
                        $('#bulan').html(listData);
                    } else if (type == 'today') {
                        $('#hari-ini').html(listData);
                    } else if (type == 'custom') {
                        $('#custom-list').html(listData);
                    }

                    // Set expense id to hidden input
                    $('.expense-card').on('click', function() {
                        var expenseId = $(this).data('expense-id');
                        $('#expense-id').val(expenseId);
                    });
                }
            })
        }

        getExpense();

        // store
        $('#btn-save').on('click', function() {
            const title = $('#title').val();
            const amount = $('#amount').val().replace(/[^,\d]/g, '');
            const date = $('#date-input').val();
            const category = $('#category').val();
            var user_pin_id = '{{ $user->user_pin_id }}';

            $.ajax({
                url: '/expense/store',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title,
                    amount: amount,
                    date: date,
                    category: category,
                    user_pin_id: user_pin_id
                },
                success: function(response) {
                    console.log(response);

                    getExpense(type = "today");

                    iziToast.success({
                        position: 'topRight',
                        title: 'Sukses',
                        message: 'Berhasil di tambahkan',
                    });

                    // Clear form
                    $('#title').val('');
                    $('#amount').val('');
                    $('#date-input').val('');
                    $('#category').val('Pilih Kategori');

                    $('#tambahData').removeClass('show');
                    $('.offcanvas-backdrop').remove();

                    const navbar = document.getElementById('navbar');
                    navbar.style.display = '';

                    // show hari-ini-tab
                    var hariIniTab = new bootstrap.Tab(document.querySelector('#hari-ini-tab'));
                    hariIniTab.show();
                }
            })
        });

        // delete
        $('#btn-delete').on('click', function() {
            iziToast.question({
                timeout: false,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                title: 'Konfirmasi',
                message: 'Hapus pengeluaran ini?',
                position: 'center',
                buttons: [
                    ['<button><b>Ya</b></button>', function(instance, toast) {

                        var expenseId = $('#expense-id').val();

                        $.ajax({
                            url: '/expense/delete/' + expenseId, // sesuaikan endpoint kamu
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}' // kalau Laravel perlu CSRF token
                            },
                            success: function(response) {
                                iziToast.success({
                                    position: 'topRight',
                                    title: 'Sukses',
                                    message: 'Berhasil di hapus',
                                });

                                // hapus item dari DOM tanpa reload
                                getExpense(type = 'today');
                                $('#actionData').removeClass('show');
                                $('.offcanvas-backdrop').remove();

                                const navbar = document.getElementById('navbar');
                                navbar.style.display = '';

                                expenseId.val = '';
                            },
                            error: function(xhr) {
                                iziToast.danger({
                                    position: 'topRight',
                                    title: 'gagal',
                                    message: 'terjadi masalah',
                                });
                            }
                        });

                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');
                    }, true],
                    ['<button>Batal</button>', function(instance, toast) {
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');
                    }]
                ]
            });
        })

        // Hide button when button create is click
        const offcanvasEl = document.getElementById('tambahData');
        const actionDataEl = document.getElementById('actionData');
        const navbar = document.getElementById('navbar');

        offcanvasEl.addEventListener('show.bs.offcanvas', function() {
            navbar.style.display = 'none';
        });

        actionDataEl.addEventListener('show.bs.offcanvas', function() {
            navbar.style.display = 'none';
        });

        offcanvasEl.addEventListener('hidden.bs.offcanvas', function() {
            navbar.style.display = '';
        });

        actionDataEl.addEventListener('hidden.bs.offcanvas', function() {
            navbar.style.display = '';
        });

        // Generate rupiah
        $('#amount').on('input', function() {
            let value = $(this).val().replace(/[^,\d]/g, '');
            let numberString = value.toString(),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

            $(this).val(rupiah);
        });
    </script>
</body>

</html>
