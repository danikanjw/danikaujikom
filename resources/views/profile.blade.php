<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile - Toko Alat Kesehatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="{{ asset('css/main.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

  @include('layouts.header')

  <main class="flex-grow-1">
    <div class="container mt-5">
      <h2 class="text-center mb-4">User Profile</h2>

      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow-sm">
            <div class="card-body">
              @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
              @endif
              @if($errors->any())
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                </div>
                <div class="mb-3">
                  <label for="fullname" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname', $user->name) }}" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>
                <div class="mb-3">
                  <label for="city_id" class="form-label">City</label>
                  <select class="form-control" id="city_id" name="city_id" required>
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                      <option value="{{ $city->id }}" {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="contact_no" class="form-label">Contact Number</label>
                  <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ old('contact_no', $user->contact_no) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
                @if(Session::get('role') === 'vendor')
                  <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary ms-2">Back to Dashboard</a>
                @else
                  <a href="{{ route('product') }}" class="btn btn-secondary ms-2">Back to Products</a>
                @endif
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
