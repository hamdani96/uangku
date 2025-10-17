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

    <style>
        #lingkaran,
        #tahunan,
        #tahunan-detail {
            width: 100%;
            height: 300px;
            margin: 0 auto;
        }

        @media (max-width: 576px) {

            #lingkaran,
            #tahunan,
            #tahunan-detail {
                height: 220px;
            }
        }

        .flatpickr-calendar {
            z-index: 1056 !important;
        }
    </style>
</head>

<body <div class="mobile-wrapper">

    {{-- Navbar --}}
    <div class="bottom-nav" id="navbar">
        <a href="{{ route('insight.index', $user->pin) }}"
            class="nav-item @if (Request::is('insight/*')) active @endif">
            <i class="fa-solid fa-chart-pie"></i>
        </a>
        <a href="{{ route('expense.index', $user->pin) }}"
            class="nav-item @if (Request::is('expense/*')) active @endif">
            <i class="fa-solid fa-paw"></i>
        </a>
        <button class="nav-item add-btn" data-bs-toggle="offcanvas" data-bs-target="#tambahData"
            aria-controls="tambahData" style="border: none;">
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
                    <input type="number" class="form-control" id="amount" required placeholder="Masukan Jumlah" />
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="text" class="form-control" id="date-input" required placeholder="Pilih tanggal" />
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
        <h6 class="m-0 me-0 fw-bold">Insight, {{ $user->name }}</h6>
        <small class="m-0 me-0">Lihat ringkasan kamu</small>
    </div>

    <div class="container mt-4">

        <div class="card-custom d-flex justify-content-center align-items-center">
            <div class="row w-100">
                <input type="text" name="" placeholder="Select Date.." id="filter-pie" class="form-control">
                <div id="lingkaran"></div>
            </div>
        </div>

        <div class="card-custom d-flex justify-content-center align-items-center">
            <div class="row w-100">
                <input type="text" name="" placeholder="Select Date" id="filter-bar"
                    class="form-control mb-5">
                <div id="tahunan"></div>
            </div>
        </div>


    </div>

    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/themes/adaptive.js"></script>


    <script>
        $(document).ready(function() {
            flatpickr("#test-date", {
                mode: "range",
                dateFormat: "Y-m-d",
            });
        });
    </script>
    <script>
        // Hide button when button create is click
        const offcanvasEl = document.getElementById('tambahData');
        const navbar = document.getElementById('navbar');

        offcanvasEl.addEventListener('show.bs.offcanvas', function() {
            navbar.style.display = 'none';
        });

        offcanvasEl.addEventListener('hidden.bs.offcanvas', function() {
            navbar.style.display = '';
        });
    </script>

    <script>
        $(document).ready(function() {

            // Ganti dengan ID user yang sesuai
            let userPinId = "{{ $user->user_pin_id }}";
            let startDate = '';
            let endDate = '';

            // Inisialisasi Flatpickr
            flatpickr("#filter-pie", {
                mode: "range",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    // Pisahkan tanggal awal dan akhir saat user memilih range
                    let dates = dateStr.split(" to ");
                    startDate = dates[0] ?? '';
                    endDate = dates[1] ?? '';

                    // Panggil chart ulang setelah tanggal diubah
                    pieChart(userPinId, startDate, endDate);
                }
            });

            // Fungsi untuk memuat chart via AJAX
            function pieChart(userPinId, startDate, endDate) {
                $.ajax({
                    url: "{{ route('insight.circleChart', '') }}/" + userPinId, // route: /circle-chart/{id}
                    type: "GET",
                    dataType: "json",
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        // Ambil data dari response JSON
                        let chartData = response.chartCircle;

                        // Bangun chart menggunakan data dari controller
                        Highcharts.chart('lingkaran', {
                            chart: {
                                type: 'pie',
                                backgroundColor: 'transparent',
                            },
                            title: {
                                text: ''
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                            },
                            accessibility: {
                                point: {
                                    valueSuffix: '%'
                                }
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    borderRadius: 5,
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
                                        distance: -50,
                                        filter: {
                                            property: 'percentage',
                                            operator: '>',
                                            value: 4
                                        }
                                    }
                                }
                            },
                            series: [{
                                name: 'Share',
                                colorByPoint: true,
                                data: chartData // data dari controller
                            }]
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Gagal memuat data chart:", error);
                    }
                });
            }

            // Load chart pertama kali saat halaman dibuka
            pieChart(userPinId, startDate, endDate);

        });
    </script>

    {{-- Bar Chart --}}
    <script>
        $(document).ready(function() {
            let userPinId = '{{ $user->user_pin_id }}';
            let startDate = '';
            let endDate = '';

            flatpickr("#filter-bar", {
                mode: "range",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    let dates = dateStr.split(" to ");
                    startDate = dates[0] ?? '';
                    endDate = dates[1] ?? '';

                    barChart(userPinId, startDate, endDate);
                }
            });

            function barChart(userPinId, startDate, endDate) {
                $.ajax({
                    url: '{{ route('insight.barChart', '') }}/' + userPinId,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        let barData = response.expenses;
                        let categories = response.categories;

                        console.log(barData);
                        console.log(categories);

                        Highcharts.chart('tahunan', {
                            chart: {
                                type: 'bar',
                                backgroundColor: 'transparent',
                            },
                            title: {
                                text: ''
                            },
                            xAxis: {
                                categories: categories,
                                title: {
                                    text: null
                                },
                            },
                            yAxis: {
                                min: 0,
                                labels: {
                                    overflow: 'justify'
                                },
                                gridLineWidth: 0
                            },
                            tooltip: {
                                valueSuffix: ' '
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: '50%',
                                    dataLabels: {
                                        enabled: true
                                    },
                                    groupPadding: 0.1
                                }
                            },
                            legend: {
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'top',
                                x: -40,
                                y: 80,
                                floating: true,
                                borderWidth: 1,
                                backgroundColor: 'var(--highcharts-background-color, #ffffff)',
                                shadow: true,
                                enabled: false
                            },
                            credits: {
                                enabled: false
                            },
                            series: [{
                                name: 'IDR',
                                data: barData
                            }]
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal memuat data chart:', error);
                    }
                });
            }

            barChart(userPinId, startDate, endDate);
        })
    </script>

</body>

</html>
