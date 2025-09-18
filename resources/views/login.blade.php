<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Toko Alat Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('css/login.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body>

    <div class="glass-card text-center">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <div class="logo-box">LOGO</div>
            <div class="text-start ms-3">
                <p class="welcome-text mb-0">Selamat datang di</p>
                <h6 class="fw-bold mb-0">Toko Alat Kesehatan</h6>
            </div>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            @if ($errors->has('login'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('login') }}
            </div>
            @endif
            <div class="mb-3 text-start">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">LOGIN</button>
        </form>

        <div class="">
            <p class="mt-2">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>