@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
  <style>
    .profile-page {
      min-height: 100%;
      padding: 44px 16px 56px;
      background: #fff;
      color: #172b4d;
    }

    .profile-heading {
      margin-bottom: 24px;
      font-size: clamp(24px, 3vw, 30px);
      font-weight: 750;
      letter-spacing: -.6px;
    }

    .profile-card {
      overflow: hidden;
      border: 1px solid #e9edf3;
      border-radius: 14px;
      background: #fff;
      box-shadow: 0 2px 8px rgb(23 43 77 / 5%);
    }

    .profile-card-header {
      padding: 20px 24px;
      border-bottom: 1px solid #edf1f2;
      background: #f5f8f7;
    }

    .profile-card-header h2 {
      margin: 0;
      font-size: 17px;
      font-weight: 700;
    }

    .profile-card-header p {
      margin: 5px 0 0;
      color: #75839a;
      font-size: 13px;
    }

    .profile-card-body {
      padding: 24px;
    }

    .profile-page .form-label {
      color: #35445c;
      font-size: 13px;
      font-weight: 600;
    }

    .profile-page .form-control,
    .profile-page .form-select {
      min-height: 42px;
      border-color: #dfe7e5;
      border-radius: 8px;
      color: #35445c;
      font-size: 14px;
    }

    .profile-page .form-control:focus,
    .profile-page .form-select:focus {
      border-color: #1bb89a;
      box-shadow: 0 0 0 .2rem rgb(27 184 154 / 15%);
    }

    .profile-submit {
      border: 0;
      background: #1bb89a;
      color: #fff;
      /* font-weight: 650; */
    }

    .profile-submit:hover {
      background: #159d83;
      color: #fff;
    }

    .profile-back {
      border-color: #b7e9dc;
      color: #138c76;
    }

    .profile-back:hover {
      border-color: #1bb89a;
      background: #e8f8f4;
      color: #107762;
    }

    @media (max-width: 575.98px) {
      .profile-page {
        padding: 28px 14px 40px;
      }

      .profile-card-header,
      .profile-card-body {
        padding: 18px;
      }
    }
  </style>
    @php
      $profileBackRoute = Session::get('role') === 'admin'
        ? route('admin.dashboard')
        : (Session::get('role') === 'vendor' ? route('vendor.dashboard') : route('product'));
    @endphp
    <div class="profile-page">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-9">
            <h1 class="profile-heading">Edit Profile</h1>
            <section class="profile-card">
              <header class="profile-card-header">
                <h2>Personal Information</h2>
              </header>
              <div class="profile-card-body">
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
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="fullname" class="form-label">Full Name</label>
                      <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname', $user->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="contact_no" class="form-label">Contact Number</label>
                      <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ old('contact_no', $user->contact_no) }}" required>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="address" class="form-label">Address</label>
                      <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="city_id" class="form-label">City</label>
                      <select class="form-select" id="city_id" name="city_id" required>
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                          <option value="{{ $city->id }}" {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12">
                      <hr class="my-2">
                      <h3 class="h6 mt-3 mb-1">Change Password</h3>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="password" class="form-label">New Password</label>
                      <input type="password" class="form-control" id="password" name="password" minlength="6" autocomplete="new-password">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="password_confirmation" class="form-label">Confirm New Password</label>
                      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="6" autocomplete="new-password">
                    </div>
                  </div>
                  <div class="d-flex flex-wrap gap-2 mt-2">
                    <button type="submit" class="btn profile-submit">Update Profile</button>
                    <a href="{{ $profileBackRoute }}" class="btn btn-outline-success profile-back">Back</a>
                  </div>
                </form>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
@endsection
