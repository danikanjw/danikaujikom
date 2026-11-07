@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<style>
    .vendor-dashboard {
        --vendor-ink: #172b4d;
        --vendor-muted: #75839a;
        --vendor-accent: #1bb89a;
        min-height: calc(100vh - 74px);
        padding: 32px clamp(18px, 4vw, 54px) 48px;
        background: #f7faf9;
        color: var(--vendor-ink);
    }

    .vendor-dashboard-inner {
        width: min(100%, 1440px);
        margin: 0 auto;
    }

    .vendor-welcome {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
    }

    .vendor-welcome h1 {
        margin: 0;
        font-size: clamp(24px, 3vw, 31px);
        font-weight: 750;
        letter-spacing: -.7px;
    }

    .vendor-welcome p {
        margin: 7px 0 0;
        color: var(--vendor-muted);
        font-size: 14px;
    }

    .vendor-primary-action {
        border: 0;
        border-radius: 9px;
        background: var(--vendor-accent);
        color: #fff;
        font-weight: 650;
    }

    .vendor-primary-action:hover {
        background: #159d83;
        color: #fff;
    }

    .vendor-stat-card,
    .vendor-panel {
        border: 1px solid #e9edf3;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 2px 8px rgb(23 43 77 / 5%);
    }

    .vendor-stat-card {
        display: flex;
        height: 100%;
        align-items: center;
        gap: 14px;
        padding: 18px;
    }

    .vendor-stat-icon {
        display: grid;
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        place-items: center;
        border-radius: 12px;
        background: #e7f8f4;
        color: #10977f;
        font-size: 19px;
    }

    .vendor-stat-icon--blue {
        background: #edf3ff;
        color: #5478c7;
    }

    .vendor-stat-icon--amber {
        background: #fff5e5;
        color: #bd8219;
    }

    .vendor-stat-label {
        margin: 0 0 4px;
        color: var(--vendor-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .vendor-stat-value {
        margin: 0;
        font-size: 25px;
        font-weight: 750;
        line-height: 1.1;
    }

    .vendor-panel {
        margin-top: 24px;
        overflow: hidden;
    }

    .vendor-panel-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf1f2;
    }

    .vendor-panel-heading h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .vendor-panel-heading p {
        margin: 5px 0 0;
        color: var(--vendor-muted);
        font-size: 12px;
    }

    .vendor-product-table {
        margin: 0;
        color: #35445c;
        font-size: 13px;
    }

    .vendor-product-table thead th {
        padding: 12px 18px;
        border-bottom: 1px solid #e6edf0;
        background: #f5f8f7;
        color: var(--vendor-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .vendor-product-table tbody td {
        padding: 14px 18px;
        border-color: #edf1f2;
        vertical-align: middle;
    }

    .vendor-product-name {
        color: var(--vendor-ink);
        font-weight: 650;
    }

    .vendor-product-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        border-top: 1px solid #edf1f2;
    }

    .vendor-product-pagination-label {
        color: var(--vendor-muted);
        font-size: 13px;
    }

    .vendor-stock-badge {
        display: inline-block;
        min-width: 46px;
        padding: 5px 9px;
        border-radius: 20px;
        background: #e7f8f4;
        color: #138c76;
        font-weight: 650;
        text-align: center;
    }

    .vendor-stock-badge--empty {
        background: #fff1f0;
        color: #c34e53;
    }

    .vendor-empty-state {
        padding: 46px 20px;
        text-align: center;
    }

    .vendor-empty-state p {
        margin-bottom: 16px;
        color: var(--vendor-muted);
    }

    .vendor-product-page {
        --vendor-ink: #172b4d;
        --vendor-muted: #75839a;
        position: fixed;
        z-index: 1080;
        inset: 0;
        overflow-y: auto;
        background: #f7faf9;
        opacity: 0;
        pointer-events: none;
        transform: translateY(-100%);
        transition: transform .42s cubic-bezier(.22, .75, .25, 1), opacity .42s cubic-bezier(.22, .75, .25, 1);
    }

    .vendor-product-page.is-open {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
    }

    .vendor-product-page-content {
        width: min(100%, 900px);
        min-height: 100vh;
        margin: 0 auto;
        padding: 32px clamp(18px, 4vw, 54px) 48px;
    }

    .vendor-product-form-page .container {
        width: 100%;
        max-width: none;
        margin: 0 !important;
        padding: 0;
    }

    .vendor-product-form-page h1 {
        margin-bottom: 24px;
        color: var(--vendor-ink);
        font-size: clamp(24px, 3vw, 31px);
        font-weight: 750;
    }

    .vendor-product-form-page .form-label {
        color: var(--vendor-ink);
        font-weight: 600;
    }

    .vendor-product-form-page .form-control,
    .vendor-product-form-page .form-select {
        min-height: 44px;
    }

    .vendor-product-form-page textarea.form-control {
        min-height: 120px;
    }

    @media (prefers-reduced-motion: reduce) {
        .vendor-product-page {
            transition: none;
        }
    }

    @media (max-width: 575.98px) {
        .vendor-dashboard {
            padding: 24px 14px 36px;
        }

        .vendor-product-page-content {
            padding: 24px 14px 36px;
        }

        .vendor-welcome {
            align-items: flex-start;
            flex-direction: column;
        }

        .vendor-panel-heading {
            align-items: flex-start;
            flex-direction: column;
            padding: 17px;
        }

        .vendor-product-table thead th,
        .vendor-product-table tbody td {
            padding-right: 12px;
            padding-left: 12px;
        }
    }
</style>

<div class="vendor-dashboard">
    <div class="vendor-dashboard-inner">
        <section class="vendor-welcome" aria-labelledby="vendor-dashboard-title">
            <div>
                <h1 id="vendor-dashboard-title">Vendor Dashboard</h1>
            </div>
            <a href="{{ route('vendor.product.create') }}" class="btn vendor-primary-action px-3 py-2" data-ajax-page>
                Add New Product
            </a>
        </section>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="alert alert-success d-none js-stock-submit-success" role="status" aria-live="polite"></div>

        <section class="row g-3" aria-label="Store overview">
            <div class="col-12 col-md-4">
                <article class="vendor-stat-card">
                    <span class="vendor-stat-icon vendor-stat-icon--blue" aria-hidden="true">▦</span>
                    <div>
                        <p class="vendor-stat-label">Total Products</p>
                        <p class="vendor-stat-value">{{ number_format($totalProducts) }}</p>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-4">
                <article class="vendor-stat-card">
                    <span class="vendor-stat-icon" aria-hidden="true">▤</span>
                    <div>
                        <p class="vendor-stat-label">Available Stock</p>
                        <p class="vendor-stat-value">{{ number_format($availableStock) }}</p>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-4">
                <article class="vendor-stat-card">
                    <span class="vendor-stat-icon vendor-stat-icon--amber" aria-hidden="true">◷</span>
                    <div>
                        <p class="vendor-stat-label">Pending Stock Submissions</p>
                        <p class="vendor-stat-value js-pending-stock-count" data-count="{{ $pendingStockSubmissions }}">{{ number_format($pendingStockSubmissions) }}</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="vendor-panel" aria-labelledby="recent-products-title">
            <div class="vendor-panel-heading">
                <div>
                    <h2 id="recent-products-title">Recent Products</h2>
                </div>
                {{-- <a href="{{ route('vendor.product.index') }}" class="btn btn-outline-success btn-sm">Manage Products</a> --}}
            </div>

            @if($products->isNotEmpty())
                <div class="table-responsive">
                    <table class="table vendor-product-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Available Stock</th>
                                <th>Stock Submission Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr data-product-id="{{ $product->product_id }}">
                                    <td class="vendor-product-name">{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="vendor-stock-badge {{ $product->warehouse_quantity < 1 ? 'vendor-stock-badge--empty' : '' }}">
                                            {{ number_format($product->warehouse_quantity) }}
                                        </span>
                                    </td>
                                    <td class="js-stock-status">
                                        @if($product->pending_stock_submissions_count)
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('vendor.product.edit', $product->product_id) }}" class="btn btn-outline-success btn-sm" aria-label="Edit {{ $product->name }}" title="Edit product" data-ajax-page><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <button type="button" class="btn btn-success btn-sm js-stock-submit-button" data-bs-toggle="modal" data-bs-target="#submitStockModal{{ $product->product_id }}" @if($product->pending_stock_submissions_count) disabled title="Menunggu persetujuan admin" @endif>
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" aria-label="Delete {{ $product->name }}" title="Delete product" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->product_id }}"
                                                @if($product->stock_delivery_items_count || $product->order_items_count)
                                                    disabled title="Products with stock or order history cannot be deleted"
                                                @endif
                                            ><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                    <nav class="vendor-product-pagination" aria-label="Product list pagination">
                        @if($products->onFirstPage())
                            <span class="btn btn-outline-secondary btn-sm disabled" aria-disabled="true">Previous</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="btn btn-outline-success btn-sm" data-dashboard-page>Previous</a>
                        @endif
                        <span class="vendor-product-pagination-label">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="btn btn-outline-success btn-sm" data-dashboard-page>Next</a>
                        @else
                            <span class="btn btn-outline-secondary btn-sm disabled" aria-disabled="true">Next</span>
                        @endif
                    </nav>
                @endif
            @else
                <div class="vendor-empty-state">
                    <p>You haven't added any products yet.</p>
                    <a href="{{ route('vendor.product.create') }}" class="btn vendor-primary-action px-3" data-ajax-page>Add Your First Product</a>
                </div>
            @endif
        </section>
    </div>
</div>

<div class="vendor-product-page js-vendor-product-page" aria-hidden="true" inert>
    <div class="vendor-product-page-content js-vendor-product-page-content"></div>
</div>

<div class="js-vendor-modals">
@foreach($products as $product)
    <div class="modal fade" id="submitStockModal{{ $product->product_id }}" tabindex="-1" aria-labelledby="submitStockModalLabel{{ $product->product_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('vendor.stock.submit', $product->product_id) }}" class="js-submit-stock-form" data-product-id="{{ $product->product_id }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="submitStockModalLabel{{ $product->product_id }}">Submit Stock for Verification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Product: <strong>{{ $product->name }}</strong></p>
                        <p>Currently available: {{ number_format($product->warehouse_quantity) }}</p>
                        @if($product->stock_delivery_items_count)
                            <p class="text-muted">You can submit additional stock after the previous submission has been reviewed by admin.</p>
                        @endif
                        <div class="mb-3">
                            <label for="quantity{{ $product->product_id }}" class="form-label">Stock submitted</label>
                            <input type="number" class="form-control" id="quantity{{ $product->product_id }}" name="quantity" min="1" max="4294967295" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes{{ $product->product_id }}" class="form-label">Notes (optional)</label>
                            <textarea class="form-control" id="notes{{ $product->product_id }}" name="notes" rows="2" maxlength="1000"></textarea>
                        </div>
                        <div class="alert alert-danger d-none js-stock-submit-error" role="alert"></div>
                        <small class="text-muted">Stock will be available to customers after admin verifies it.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Submit Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(!$product->stock_delivery_items_count && !$product->order_items_count)
        <div class="modal fade" id="deleteProductModal{{ $product->product_id }}" tabindex="-1" aria-labelledby="deleteProductModalLabel{{ $product->product_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('vendor.product.destroy', $product->product_id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteProductModalLabel{{ $product->product_id }}">Delete Product?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong>{{ $product->name }}</strong>? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
</div>

<script>
    const productPagePanel = document.querySelector('.js-vendor-product-page');
    const productPageContent = document.querySelector('.js-vendor-product-page-content');
    const dashboardUrl = @json(route('vendor.dashboard'));
    let productPageRequest;

    function ajaxHeaders(accept) {
        return {
            'Accept': accept,
            'X-Requested-With': 'XMLHttpRequest',
        };
    }

    async function fetchHtml(url, errorMessage, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: ajaxHeaders('text/html'),
        });

        if (!response.ok) {
            throw new Error(errorMessage);
        }

        return response.text();
    }

    async function fetchJson(url, options, errorMessage) {
        const response = await fetch(url, {
            ...options,
            headers: ajaxHeaders('application/json'),
        });
        let result;

        try {
            result = await response.json();
        } catch {
            throw new Error('The server returned an invalid response.');
        }

        if (!response.ok) {
            const validationMessages = result.errors
                ? Object.values(result.errors).flat()
                : [];
            throw new Error(validationMessages.join(' ') || result.message || errorMessage);
        }

        return result;
    }

    function setFormFeedback(form, submitting, message = '') {
        const errorElement = form.matches('.js-product-form')
            ? form.closest('.vendor-product-form-page').querySelector('.js-product-form-error')
            : form.querySelector('.js-stock-submit-error');

        form.querySelector('button[type="submit"]').disabled = submitting;
        errorElement.textContent = message;
        errorElement.classList.toggle('d-none', !message);
    }

    function setProductPageVisibility(isOpen) {
        productPagePanel.classList.toggle('is-open', isOpen);
        productPagePanel.setAttribute('aria-hidden', String(!isOpen));
        productPagePanel.inert = !isOpen;
    }

    function showProductPageError(message) {
        productPageContent.replaceChildren();

        const alert = document.createElement('div');
        alert.className = 'alert alert-danger';
        alert.setAttribute('role', 'alert');
        alert.textContent = message;

        const backLink = document.createElement('a');
        backLink.href = dashboardUrl;
        backLink.className = 'btn btn-secondary';
        backLink.dataset.ajaxPage = '';
        backLink.textContent = 'Back to dashboard';

        productPageContent.append(alert, backLink);
    }

    async function openProductPage(url, updateHistory = true) {
        if (productPageRequest) {
            productPageRequest.abort();
        }

        const request = new AbortController();
        productPageRequest = request;

        if (updateHistory) {
            history.pushState({ vendorProductPage: true, productUrl: url }, '', window.location.href);
        }

        try {
            const html = await fetchHtml(url, 'Could not load the product form. Please try again.', {
                signal: request.signal,
            });
            const documentPage = new DOMParser().parseFromString(html, 'text/html');
            const formPage = documentPage.querySelector('.vendor-product-form-page');

            if (!formPage) {
                throw new Error('The product form was not found on the requested page.');
            }

            const productForm = formPage.querySelector('.js-product-form');
            if (!productForm) {
                throw new Error('The product form could not be initialized.');
            }

            if (request.signal.aborted) {
                return;
            }

            productForm.addEventListener('submit', submitProductForm);
            productPageContent.replaceChildren(formPage);
            setProductPageVisibility(true);
            const firstInput = formPage.querySelector('input:not([type="hidden"]), textarea, select');
            firstInput?.focus({ preventScroll: true });
        } catch (error) {
            if (!request.signal.aborted) {
                showProductPageError(error.message || 'Could not load the product form. Please try again.');
                setProductPageVisibility(true);
            }
        }
    }

    function closeProductPage() {
        if (productPageRequest) {
            productPageRequest.abort();
            productPageRequest = null;
        }

        setProductPageVisibility(false);
    }

    async function refreshVendorDashboard(successMessage) {
        const html = await fetchHtml(dashboardUrl, 'Product was saved, but the dashboard could not be refreshed.', {
            cache: 'no-store',
        });
        const documentPage = new DOMParser().parseFromString(html, 'text/html');
        const newDashboard = documentPage.querySelector('.vendor-dashboard');
        const newModals = documentPage.querySelector('.js-vendor-modals');
        const currentDashboard = document.querySelector('.vendor-dashboard');
        const currentModals = document.querySelector('.js-vendor-modals');

        if (!newDashboard || !newModals || !currentDashboard || !currentModals) {
            throw new Error('Product was saved, but the dashboard could not be refreshed.');
        }

        const successAlert = newDashboard.querySelector('.js-stock-submit-success');
        successAlert.textContent = successMessage;
        successAlert.classList.remove('d-none');
        currentDashboard.replaceWith(newDashboard);
        currentModals.replaceWith(newModals);
    }

    async function loadDashboardPage(url, updateHistory = true) {
        const html = await fetchHtml(url, 'Could not load the requested product page.');
        const documentPage = new DOMParser().parseFromString(html, 'text/html');
        const newDashboard = documentPage.querySelector('.vendor-dashboard');
        const newModals = documentPage.querySelector('.js-vendor-modals');
        const currentDashboard = document.querySelector('.vendor-dashboard');
        const currentModals = document.querySelector('.js-vendor-modals');

        if (!newDashboard || !newModals || !currentDashboard || !currentModals) {
            throw new Error('Could not load the requested product page.');
        }

        currentDashboard.replaceWith(newDashboard);
        currentModals.replaceWith(newModals);

        if (updateHistory) {
            history.pushState({ vendorDashboardPage: true }, '', url);
        }
    }

    document.addEventListener('click', (event) => {
        const link = event.target instanceof Element
            ? event.target.closest('a[data-ajax-page], a[data-dashboard-page]')
            : null;
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        event.preventDefault();

        if (link.hasAttribute('data-dashboard-page')) {
            loadDashboardPage(link.href).catch((error) => {
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.setAttribute('role', 'alert');
                alert.textContent = error.message || 'Could not load the requested product page.';
                document.querySelector('.vendor-dashboard-inner').prepend(alert);
            });
            return;
        }

        if (link.hasAttribute('data-cancel-product-page') && productPagePanel.classList.contains('is-open')) {
            history.replaceState(null, '', window.location.href);
            closeProductPage();
            return;
        }

        if (link.href === dashboardUrl && productPagePanel.classList.contains('is-open')) {
            history.back();
            return;
        }

        openProductPage(link.href);
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.matches('.js-submit-stock-form')) {
            return;
        }

        event.preventDefault();

        setFormFeedback(form, true);

        try {
            const result = await fetchJson(form.action, {
                method: 'POST',
                body: new FormData(form),
            }, 'Stock submission failed.');

            const productRow = document.querySelector(`[data-product-id="${form.dataset.productId}"]`);
            const pendingCount = document.querySelector('.js-pending-stock-count');
            const successElement = document.querySelector('.js-stock-submit-success');

            if (productRow) {
                const statusCell = productRow.querySelector('.js-stock-status');
                const stockButton = productRow.querySelector('.js-stock-submit-button');

                const statusBadge = document.createElement('span');
                statusBadge.className = 'badge bg-warning text-dark';
                statusBadge.textContent = 'Menunggu Persetujuan';
                statusCell.replaceChildren(statusBadge);
                stockButton.disabled = true;
                stockButton.title = 'Menunggu persetujuan admin';
            }

            if (pendingCount) {
                const count = Number(pendingCount.dataset.count) + 1;
                pendingCount.dataset.count = count;
                pendingCount.textContent = new Intl.NumberFormat().format(count);
            }

            successElement.textContent = result.message;
            successElement.classList.remove('d-none');
            bootstrap.Modal.getOrCreateInstance(form.closest('.modal')).hide();
            form.reset();
        } catch (error) {
            setFormFeedback(form, false, error.message || 'Could not submit stock. Please try again.');
        }
    });

    async function submitProductForm(event) {
        event.preventDefault();
        const form = event.currentTarget;

        setFormFeedback(form, true);

        try {
            const result = await fetchJson(form.action, {
                method: 'POST',
                body: new FormData(form),
            }, 'Could not save product.');

            await refreshVendorDashboard(result.message || 'Product saved successfully.');
            history.replaceState(null, '', result.redirect || dashboardUrl);
            closeProductPage();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch (error) {
            setFormFeedback(form, false, error.message || 'Could not save product. Please try again.');
        }
    }

    window.addEventListener('popstate', (event) => {
        if (event.state && event.state.vendorProductPage && event.state.productUrl) {
            openProductPage(event.state.productUrl, false);
        } else {
            closeProductPage();
            loadDashboardPage(window.location.href, false).catch((error) => {
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.setAttribute('role', 'alert');
                alert.textContent = error.message || 'Could not load the requested product page.';
                document.querySelector('.vendor-dashboard-inner').prepend(alert);
            });
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && productPagePanel.classList.contains('is-open')) {
            history.back();
        }
    });

    if (history.state && history.state.vendorProductPage && history.state.productUrl) {
        openProductPage(history.state.productUrl, false);
    }
</script>
@endsection
