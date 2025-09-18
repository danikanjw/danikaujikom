<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Toko Alat Kesehatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="{{ asset('css/main.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

  @include('layouts.header')

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  <main class="flex-grow-1">
    <div class="container mt-5">
      <h2 class="text-center mb-4">Product Page</h2>

      <!-- Collapsible Sidebar for Mobile -->
      <div class="d-md-none mb-3">
        <div class="accordion" id="mobileCategoryAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingCategories">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories">
                Product Categories
              </button>
            </h2>
            <div id="collapseCategories" class="accordion-collapse collapse" aria-labelledby="headingCategories" data-bs-parent="#mobileCategoryAccordion">
              <div class="accordion-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item {{ !$categoryId ? 'active' : '' }}">
                    <a href="{{ route('product') }}" class="text-decoration-none">All</a>
                  </li>
                  @foreach($categories as $category)
                  <li class="list-group-item {{ $categoryId == $category->category_id ? 'active' : '' }}">
                    <a href="{{ route('product', ['category_id' => $category->category_id]) }}" class="text-decoration-none">{{ $category->name }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-3 order-1 order-md-2 d-none d-md-block">
          <div class="card shadow-sm" style="min-height: 200px;">
            <div class="card-body text-center">
              <h3 class="card-title">Product Category</h3>
              <ul class="list-group list-group-flush mt-2">
                <li class="list-group-item {{ !$categoryId ? 'active' : '' }}">
                  <a href="{{ route('product') }}" class="text-decoration-none">All</a>
                </li>
                @foreach($categories as $category)
                <li class="list-group-item {{ $categoryId == $category->category_id ? 'active' : '' }}">
                  <a href="{{ route('product', ['category_id' => $category->category_id]) }}" class="text-decoration-none">{{ $category->name }}</a>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-9 order-2 order-md-1">
          <div class="row row-cols-2 row-cols-md-3 g-4">
            @foreach($products as $product)
            <div class="col">
              <div class="card h-100 shadow-sm">
                @if($product->image_url)
                <img src="{{ asset('storage/' . $product->image_url) }}" class="card-img-top" alt="{{ $product->name }}" style="width: 100%; height: 200px; object-fit: contain;">
                @endif
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">{{ $product->name }}</h5>
                  @if($product->vendor)
                    <p class="card-text text-muted small mb-1">by {{ $product->vendor->username }}</p>
                  @endif
                  <p class="card-text">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                  <div class="d-flex justify-content-center gap-2 mt-auto">
                    <button type="button" class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#productDetailModal" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}" data-description="{{ $product->description }}" data-image="{{ $product->image_url }}">View</button>
                    @if(Session::has('user_id'))
                    <button type="button" class="btn btn-success btn-buy" data-bs-toggle="modal" data-bs-target="#buyModal" data-product-id="{{ $product->product_id }}" data-product-name="{{ $product->name }}" data-max-quantity="{{ $product->quantity }}">Buy</button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-success">Buy</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
          </div>
        </div>
      </div>
    </div>
  </main>

  @include('layouts.footer')

  <!-- Product Detail Modal -->
  <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productDetailModalLabel">Product Detail</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 text-center">
              <img id="modalProductImage" src="" alt="Product Image" class="img-fluid" style="max-height: 300px; object-fit: contain;">
            </div>
            <div class="col-md-6">
              <h3 id="modalProductName"></h3>
              <p><strong>Price:</strong> Rp <span id="modalProductPrice"></span></p>
              <p><strong>Quantity Available:</strong> <span id="modalProductQuantity"></span></p>
              <p id="modalProductDescription"></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" id="checkoutBtn">Checkout</button> -->
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Buy Modal -->
  <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="buyModalLabel">Buy Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="buyForm" method="POST" action="{{ route('cart.add') }}">
          @csrf
          <div class="modal-body">
            <p id="buyProductName"></p>
            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
              <input type="hidden" id="productId" name="product_id">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Add to Cart</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Cart Modal -->
  <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-center">
          <h4 class="modal-title" id="cartModalLabel">Keranjang Belanja</h4>
          <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div id="cartItems">
            <!-- Cart items will be loaded here -->
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script>
    var productDetailModal = document.getElementById('productDetailModal');
    productDetailModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var name = button.getAttribute('data-name');
      var price = button.getAttribute('data-price');
      var quantity = button.getAttribute('data-quantity');
      var description = button.getAttribute('data-description');
      var image = button.getAttribute('data-image');

      var modalTitle = productDetailModal.querySelector('.modal-title');
      var modalProductName = productDetailModal.querySelector('#modalProductName');
      var modalProductPrice = productDetailModal.querySelector('#modalProductPrice');
      var modalProductQuantity = productDetailModal.querySelector('#modalProductQuantity');
      var modalProductDescription = productDetailModal.querySelector('#modalProductDescription');
      var modalProductImage = productDetailModal.querySelector('#modalProductImage');

      modalTitle.textContent = name;
      modalProductName.textContent = name;
      modalProductPrice.textContent = new Intl.NumberFormat('id-ID').format(price);
      modalProductQuantity.textContent = quantity;
      modalProductDescription.textContent = description;
      if (image) {
        modalProductImage.src = "{{ asset('storage') }}/" + image;
        modalProductImage.style.display = 'block';
      } else {
        modalProductImage.style.display = 'none';
      }
    });
  </script>

  <script>
    // Buy modal setup
    var buyModal = document.getElementById('buyModal');
    buyModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var productId = button.getAttribute('data-product-id');
      var productName = button.getAttribute('data-product-name');
      var maxQuantity = parseInt(button.getAttribute('data-max-quantity'));

      var buyProductName = buyModal.querySelector('#buyProductName');
      var quantityInput = buyModal.querySelector('#quantity');
      var productIdInput = buyModal.querySelector('#productId');

      buyProductName.textContent = 'Buy: ' + productName;
      quantityInput.value = 1;
      quantityInput.max = maxQuantity;
      productIdInput.value = productId;
    });

    // Load cart items and update badge
    function loadCart() {
      fetch('{{ route("cart.get") }}')
        .then(response => {
          if (!response.ok) throw new Error('Not logged in');
          return response.json();
        })
        .then(data => {
          var cartItemsContainer = document.getElementById('cartItems');
          var cartBadge = document.getElementById('cartBadge');
          cartItemsContainer.innerHTML = '';
          if (data.items.length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
            cartBadge.style.display = 'none';
            return;
          }
          cartBadge.style.display = 'inline-block';
          cartBadge.textContent = data.items.length;

          var table = document.createElement('table');
          table.className = 'table table-bordered align-middle text-center';

          var thead = document.createElement('thead');
          thead.innerHTML = `
        <tr class="table-secondary">
          <th style="width: 50px;">No.</th>
          <th>Nama Produk dengan ID</th>
          <th style="width: 100px;">Jumlah</th>
          <th style="width: 150px;">Harga</th>
          <th style="width: 150px;">Subtotal</th>
          <th style="width: 100px;">Aksi</th>
        </tr>
      `;
          table.appendChild(thead);

          var tbody = document.createElement('tbody');
          data.items.forEach((item, index) => {
            var tr = document.createElement('tr');

            tr.innerHTML = `
          <td>${index + 1}</td>
          <td>${item.name}</td>
          <td>${item.quantity}</td>
          <td>Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</td>
          <td>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
          <td>
            <button class="btn btn-danger btn-sm">Hapus</button>
          </td>
        `;

            // tombol hapus
            tr.querySelector('button').addEventListener('click', function() {
              if (confirm('Are you sure you want to remove this item from the cart?')) {
                fetch('/cart/remove/' + item.cart_id, {
                  method: 'DELETE',
                  headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                  }
                }).then(response => {
                  if (response.ok) {
                    loadCart();
                  } else {
                    alert('Failed to remove item.');
                  }
                });
              }
            });

            tbody.appendChild(tr);
          });
          table.appendChild(tbody);

          var tfoot = document.createElement('tfoot');
          tfoot.innerHTML = ``;

          table.appendChild(tfoot);

          cartItemsContainer.appendChild(table);

          var totalElement = document.createElement('h4');
          totalElement.classList.add('fs-6', 'mt-3');
          totalElement.innerHTML = `Total belanja (termasuk pajak): Rp ${new Intl.NumberFormat('id-ID').format(data.total)}`;
          cartItemsContainer.appendChild(totalElement);
        })
        .catch(error => {
          var cartItemsContainer = document.getElementById('cartItems');
          var cartBadge = document.getElementById('cartBadge');
          cartItemsContainer.innerHTML = '<p>Please login to view your cart.</p>';
          cartBadge.style.display = 'none';
        });
    }

    fetch('{{ route("cart.get") }}')
      .then(response => {
        if (!response.ok) throw new Error('Not logged in');
        return response.json();
      })
      .then(data => {
        var cartItemsContainer = document.getElementById('cartItems');
        var cartBadge = document.getElementById('cartBadge');
        cartItemsContainer.innerHTML = '';
        if (data.items.length === 0) {
          cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
          cartBadge.style.display = 'none';
          return;
        }
        cartBadge.style.display = 'inline-block';
        cartBadge.textContent = data.items.length;

        var table = document.createElement('table');
        table.className = 'table';
        var thead = document.createElement('thead');
        thead.innerHTML = '<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>';
        table.appendChild(thead);

        var tbody = document.createElement('tbody');
        data.items.forEach(item => {
          var tr = document.createElement('tr');

          var tdName = document.createElement('td');
          tdName.textContent = item.name;
          tr.appendChild(tdName);

          var tdPrice = document.createElement('td');
          tdPrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(item.price);
          tr.appendChild(tdPrice);

          var tdQuantity = document.createElement('td');
          tdQuantity.textContent = item.quantity;
          tr.appendChild(tdQuantity);

          var tdSubtotal = document.createElement('td');
          tdSubtotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(item.subtotal);
          tr.appendChild(tdSubtotal);

          var tdAction = document.createElement('td');
          var btnRemove = document.createElement('button');
          btnRemove.className = 'btn btn-danger btn-sm';
          btnRemove.textContent = 'Remove';
          btnRemove.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item from the cart?')) {
              fetch('/cart/remove/' + item.cart_id, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Content-Type': 'application/json'
                }
              }).then(response => {
                if (response.ok) {
                  loadCart();
                } else {
                  alert('Failed to remove item.');
                }
              });
            }
          });
          tdAction.appendChild(btnRemove);
          tr.appendChild(tdAction);

          tbody.appendChild(tr);
        });
        table.appendChild(tbody);

        var tfoot = document.createElement('tfoot');
        tfoot.innerHTML = '<tr><th colspan="3">Total</th><th colspan="2">Rp ' + new Intl.NumberFormat('id-ID').format(data.total) + '</th></tr>';
        table.appendChild(tfoot);

        cartItemsContainer.appendChild(table);
      })
      .catch(error => {
        var cartItemsContainer = document.getElementById('cartItems');
        var cartBadge = document.getElementById('cartBadge');
        cartItemsContainer.innerHTML = '<p>Please login to view your cart.</p>';
        cartBadge.style.display = 'none';
      });


    // Load cart on modal show
    var cartModal = document.getElementById('cartModal');
    cartModal.addEventListener('show.bs.modal', loadCart);

    // Load cart badge on page load
    loadCart();
  </script>
</body>

</html>