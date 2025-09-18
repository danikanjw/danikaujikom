<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - Toko Alat Kesehatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="{{ asset('css/main.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

  @include('layouts.header')

  <main class="flex-grow-1">
    <div class="container mt-5">
      <h2 class="text-center mb-4">Checkout</h2>

      <div class="card shadow-sm">
          <div class="card-body">
            <h4>User Information</h4>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Address:</strong> {{ $user->address }}</p>
            <p><strong>City:</strong> {{ $user->city->name ?? '' }}</p>
            <p><strong>Phone:</strong> {{ $user->contact_no }}</p>

            <h4 class="mt-4">Items to Purchase</h4>
            <table class="table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cartItems as $item)
                <tr>
                  <td>{{ $item->product->name }}</td>
                  <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                  <td>{{ $item->quantity }}</td>
                  <td>Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                </tr>
              </tfoot>
            </table>

          <h4 class="mt-4">Shipping Service</h4>
          <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <div class="mb-3">
              <select class="form-select" name="shipping_service_id" required>
                <option value="" disabled selected>Select Shipping Service</option>
                @foreach($shippingServices as $service)
                <option value="{{ $service->shipping_service_id }}">{{ $service->name }}</option>
                @endforeach
              </select>
            </div>

            <h4 class="mt-4">Payment Method</h4>
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="prepaid" value="prepaid" checked onchange="togglePaymentOptions()">
                <label class="form-check-label" for="prepaid">
                  Prepaid
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="postpaid" value="postpaid" onchange="togglePaymentOptions()">
                <label class="form-check-label" for="postpaid">
                  Postpaid
                </label>
              </div>
            </div>

            <div id="prepaid-options" class="mb-3">
              <label class="form-label">Prepaid Payment Type</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="prepaid_type" id="bank" value="bank" checked onchange="toggleBankPaypal()">
                <label class="form-check-label" for="bank">Bank</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="prepaid_type" id="paypal" value="paypal" onchange="toggleBankPaypal()">
                <label class="form-check-label" for="paypal">PayPal</label>
              </div>

              <div id="bank-options" class="mt-2">
                <label for="payment_account_id" class="form-label">Select Bank Account</label>
                <select class="form-select" name="payment_account_id" id="payment_account_id" onchange="toggleAccountNumberInput()">
                  <option value="" disabled selected>Select Bank Account</option>
                  @foreach($userAccounts as $account)
                  <option value="{{ $account->payment_account_id }}">{{ $account->bank_name }}</option>
                  @endforeach
                </select>
                <label for="account_number" class="form-label mt-2">Account Number</label>
                <input type="text" class="form-control" name="account_number" id="account_number" readonly placeholder="Account number will appear here">

              </div>

              <div id="paypal-info" class="mt-2" style="display:none;">
                <!-- Hidden for PayPal as per requirement -->
              </div>
            </div>

            <div id="postpaid-options" class="mb-3" style="display:none;">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{ $user->contact_no }}" placeholder="Enter your phone number" required>
            </div>

            <div id="postpaid-warning" class="alert alert-warning" style="display:none;">
              Your contact number is empty. Please <a href="{{ route('profile') }}">update your profile</a> before choosing postpaid payment.
            </div>

            <button type="submit" class="btn btn-primary" id="confirm-order-btn">Confirm Order</button>
            <a href="{{ route('product') }}" class="btn btn-secondary ms-2">Cancel</a>
          </form>

          
          <script>
  
            const bankAccountNumbers = @json($accountNumbers);

            function togglePaymentOptions() {
              const isPrepaid = document.getElementById('prepaid').checked;
              const postpaidOptions = document.getElementById('postpaid-options');
              const prepaidOptions = document.getElementById('prepaid-options');
              const postpaidWarning = document.getElementById('postpaid-warning');
              const confirmBtn = document.getElementById('confirm-order-btn');

              prepaidOptions.style.display = isPrepaid ? 'block' : 'none';
              postpaidOptions.style.display = isPrepaid ? 'none' : 'block';

              if (!isPrepaid) {
                const contactNo = "{{ $user->contact_no }}";
                if (!contactNo || contactNo.trim() === '') {
                  postpaidWarning.style.display = 'block';
                  confirmBtn.disabled = true;
                } else {
                  postpaidWarning.style.display = 'none';
                  confirmBtn.disabled = false;
                  document.getElementById('phone_number').value = contactNo;
                }
              } else {
                confirmBtn.disabled = false; // prepaid always enable
              }
            }

            function toggleBankPaypal() {
              const isBank = document.getElementById('bank').checked;
              document.getElementById('bank-options').style.display = isBank ? 'block' : 'none';
              document.getElementById('paypal-info').style.display = !isBank ? 'block' : 'none';
            }

            function toggleAccountNumberInput() {
              const selectedBank = document.getElementById('payment_account_id').value;
              document.getElementById('account_number').value = bankAccountNumbers[selectedBank] || '';
            }

            document.addEventListener('DOMContentLoaded', function() {
              togglePaymentOptions();
              toggleBankPaypal();
              toggleAccountNumberInput();
            });
          </script>

        </div>
      </div>
    </div>
  </main>

  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>