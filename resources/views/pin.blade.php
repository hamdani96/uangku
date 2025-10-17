<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00A86B">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo/icon-512x512.png') }}">
    <title>Login PIN</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <style>
      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #fff;
      }
      .login-box {
        text-align: center;
        width: 100%;
        max-width: 320px;
      }
      .login-box img {
        width: 60px;
        margin-bottom: 30px;
      }
      .form-control {
        text-align: center;
        font-size: 16px;
        padding: 10px;
      }
      .btn-login {
        background-color: #00c389;
        color: #fff;
        width: 100%;
        border-radius: 6px;
      }
    </style>
  </head>
  <body>
    <div class="login-box">
      <!-- Ganti src logo sesuai kebutuhan -->
      <img src="{{ asset('/assets/images/logo.png') }}" alt="Logo">

      <div class="mb-3">
        <input type="number" class="form-control" placeholder="Masukan Pin">
      </div>
      {{-- <button type="button" class="btn btn-login">Masuk</button> --}}
      <button id="btn-masuk" class="btn btn-login">Masuk</button>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('#btn-masuk').on('click', function() {
            const pin = $('input').val();
            window.location.href = `/expense/${pin}`;
        });
    </script>
  </body>
</html>
