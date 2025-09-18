<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}?v={{ time() }}" rel="stylesheet">

</head>

<body>
    <div class="glass-card">
        <h4 class="form-title">FORM REGISTRASI</h4>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-3">
                <label for="username" class="col-12 col-sm-3 col-form-label">Username</label>
                <div class="col-12 col-sm-9">
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="fullname" class="col-12 col-sm-3 col-form-label">Full Name</label>
                <div class="col-12 col-sm-9">
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-12 col-sm-3 col-form-label">Password</label>
                <div class="col-12 col-sm-9">
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="password_confirmation" class="col-12 col-sm-3 col-form-label">Retype Password</label>
                <div class="col-12 col-sm-9">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-12 col-sm-3 col-form-label">E-mail</label>
                <div class="col-12 col-sm-9">
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="dob" class="col-12 col-sm-3 col-form-label">Date of Birth</label>
                <div class="col-12 col-sm-9">
                    <input type="date" id="dob" name="dob" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label">Gender</label>
                <div class="col-12 col-sm-9 d-flex flex-wrap align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="address" class="col-12 col-sm-3 col-form-label">Address</label>
                <div class="col-12 col-sm-9">
                    <textarea id="address" name="address" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label for="city" class="col-12 col-sm-3 col-form-label">City</label>
                <div class="col-12 col-sm-9">
                    <select id="city" name="city" class="form-select" required>
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->name }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="contact" class="col-12 col-sm-3 col-form-label">Contact No</label>
                <div class="col-12 col-sm-9">
                    <input type="text" id="contact" name="contact" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="paypal" class="col-12 col-sm-3 col-form-label">PayPal ID</label>
                <div class="col-12 col-sm-9">
                    <input type="email" id="paypal" name="paypal" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary mx-2">Submit</button>
                <button type="reset" class="btn btn-secondary">Clear</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>