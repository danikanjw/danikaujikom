@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .admin-dashboard {
        --admin-ink: #172b4d;
        --admin-muted: #75839a;
        --admin-accent: #1bb89a;
        min-height: 100vh;
        display: flex;
        background: #fff;
        color: var(--admin-ink);
    }

    .admin-sidebar {
        width: 258px;
        flex: 0 0 258px;
        padding: 28px 18px;
        background: #fff;
        color: var(--admin-ink);
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 0 10px 35px;
        color: var(--admin-ink);
        font-size: 19px;
        font-weight: 750;
        letter-spacing: 1.4px;
        text-decoration: none;
    }

    .admin-brand-mark {
        display: grid;
        width: 36px;
        height: 36px;
        place-items: center;
        border-radius: 11px;
        background: var(--admin-accent);
        color: #102b45;
        font-size: 18px;
        font-weight: 800;
    }

    .admin-nav-label {
        padding: 0 11px 12px;
        color: #8491a5;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
    }

    .admin-nav {
        display: grid;
        gap: 7px;
    }

    .admin-nav a {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 46px;
        padding: 0 12px;
        border-radius: 9px;
        color: #53647d;
        font-size: 14px;
        text-decoration: none;
        transition: background-color .18s ease, color .18s ease;
    }

    .admin-nav a:hover,
    .admin-nav a.active {
        background: #e8f8f4;
        color: #138c76;
    }

    .admin-nav-icon {
        width: 20px;
        color: var(--admin-accent);
        font-size: 16px;
        text-align: center;
    }

    .admin-main {
        min-width: 0;
        flex: 1;
        padding: 28px clamp(20px, 4vw, 54px) 48px;
    }

    .admin-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 36px;
    }

    .admin-page-title {
        margin: 0;
        font-size: clamp(23px, 3vw, 30px);
        font-weight: 750;
        letter-spacing: -.7px;
    }

    .admin-page-subtitle {
        margin: 6px 0 0;
        color: var(--admin-muted);
        font-size: 14px;
    }

    .admin-profile-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 10px 6px 6px;
        border: 1px solid #e6eaf0;
        border-radius: 30px;
        background: #fff;
        color: var(--admin-ink);
    }

    .admin-avatar {
        display: grid;
        width: 35px;
        height: 35px;
        place-items: center;
        border-radius: 50%;
        background: #dff7f1;
        color: #138c76;
        font-size: 13px;
        font-weight: 750;
    }

    .admin-profile-name {
        font-size: 13px;
        font-weight: 650;
    }

    .admin-section-heading {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .admin-section-hint {
        margin: 5px 0 0;
        color: var(--admin-muted);
        font-size: 13px;
    }

    .admin-stat-card,
    .admin-panel {
        background: #fff;
    }

    .admin-stat-card {
        height: 100%;
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px 16px;
        border: 1px solid #e9edf3;
        border-radius: 11px;
        box-shadow: 0 2px 8px rgb(23 43 77 / 5%);
    }

    .admin-stat-label {
        margin: 3px 0 0;
        color: var(--admin-muted);
        font-size: 11px;
        font-weight: 600;
    }

    .admin-stat-value {
        margin: 0;
        font-size: 24px;
        font-weight: 750;
        line-height: 1.1;
        letter-spacing: -.5px;
    }

    .admin-stat-icon {
        display: grid;
        width: 35px;
        height: 35px;
        flex: 0 0 35px;
        place-items: center;
        border-radius: 10px;
        background: #e7f8f4;
        color: #10977f;
        font-size: 16px;
    }

    .admin-stat-content {
        min-width: 0;
    }

    .admin-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: start;
        gap: 20px;
    }

    .admin-content-column {
        display: grid;
        grid-column: span 2;
        min-width: 0;
        gap: 20px;
    }

    .admin-panel {
        min-width: 0;
    }

    .admin-top-stores {
        padding: 22px;
        border-radius: 14px;
        background: #e8f8f1;
    }

    .admin-chart-wrap {
        position: relative;
        height: 270px;
        margin-top: 20px;
    }

    .admin-bottom-grid {
        display: grid;
        grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
        align-items: stretch;
        gap: 20px;
    }

    .admin-status-chart-wrap {
        position: relative;
        height: 230px;
        margin-top: 18px;
    }

    .admin-feedback-list,
    .admin-store-list {
        display: grid;
        margin-top: 17px;
    }

    .admin-feedback-list {
        gap: 8px;
    }

    .admin-store-list {
        gap: 0;
    }

    .admin-feedback-item {
        padding: 12px;
        border-radius: 10px;
        background: #f0f5f2;
    }

    .admin-feedback-user {
        display: flex;
        min-width: 0;
        align-items: center;
        gap: 9px;
    }

    .admin-feedback-avatar {
        display: grid;
        width: 32px;
        height: 32px;
        flex: 0 0 32px;
        place-items: center;
        overflow: hidden;
        border-radius: 50%;
        background: #d7e9df;
        color: #267657;
        font-size: 12px;
        font-weight: 700;
    }

    .admin-feedback-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-feedback-name {
        overflow: hidden;
        color: #35445c;
        font-size: 12px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .admin-feedback-message {
        display: -webkit-box;
        overflow: hidden;
        margin: 9px 0 6px;
        color: #35445c;
        font-size: 13px;
        line-height: 1.5;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .admin-feedback-meta {
        color: var(--admin-muted);
        font-size: 11px;
    }

    .admin-feedback-delete {
        padding: 0;
        border: 0;
        background: none;
        color: #dc5460;
        font-size: 11px;
    }

    .admin-store-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 0;
        border-bottom: 1px solid #ccebdd;
    }

    .admin-store-item:last-child {
        border-bottom: 0;
    }

    .admin-store-rank {
        display: grid;
        width: 29px;
        height: 29px;
        flex: 0 0 29px;
        place-items: center;
        border-radius: 9px;
        background: #d3f1e2;
        color: #267657;
        font-size: 12px;
        font-weight: 700;
    }

    .admin-store-item:nth-child(-n+3) .admin-store-rank {
        background: #bcebd2;
        color: #106b4c;
    }

    .admin-store-info {
        min-width: 0;
        flex: 1;
    }

    .admin-store-name {
        overflow: hidden;
        color: #244b3a;
        font-size: 12px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .admin-store-sales {
        margin-top: 3px;
        color: #527a66;
        font-size: 11px;
    }

    @media (max-width: 991.98px) {
        .admin-dashboard-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .admin-content-column {
            grid-column: auto;
        }
    }

    @media (max-width: 767.98px) {
        .admin-dashboard {
            display: block;
        }

        .admin-sidebar {
            width: 100%;
            padding: 16px;
        }

        .admin-brand {
            padding: 0 4px 16px;
        }

        .admin-nav-label {
            display: none;
        }

        .admin-nav {
            display: flex;
            overflow-x: auto;
            gap: 6px;
            padding-bottom: 3px;
        }

        .admin-nav a {
            flex: 0 0 auto;
            min-height: 40px;
            padding: 0 10px;
            font-size: 12px;
        }

        .admin-main {
            padding: 24px 16px 36px;
        }

        .admin-topbar {
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .admin-profile-name {
            display: none;
        }

        .admin-bottom-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .admin-chart-wrap {
            height: 230px;
        }
    }
</style>

<div class="admin-dashboard">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        @include('layouts.alkeshub-header')

        <section class="mb-4" aria-labelledby="statistics-heading">
            <h2 class="admin-section-heading" id="statistics-heading">Overview</h2>

            <div class="row g-3 mt-2">
                <div class="col-12 col-md-4">
                    <article class="admin-stat-card">
                        <span class="admin-stat-icon" aria-hidden="true">♙</span>
                        <div class="admin-stat-content">
                            <p class="admin-stat-value">{{ $totalCustomers }}</p>
                            <p class="admin-stat-label">Total Customers</p>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="admin-stat-card">
                        <span class="admin-stat-icon" aria-hidden="true">⌂</span>
                        <div class="admin-stat-content">
                            <p class="admin-stat-value">{{ $activeStores }}</p>
                            <p class="admin-stat-label">Active Stores</p>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="admin-stat-card">
                        <span class="admin-stat-icon" aria-hidden="true">◷</span>
                        <div class="admin-stat-content">
                            <p class="admin-stat-value">{{ $pendingStoreRequests }}</p>
                            <p class="admin-stat-label">Pending Store Request</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <div class="admin-dashboard-grid">
            <div class="admin-content-column">
                <section class="admin-panel" aria-labelledby="transactions-heading">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                        <h2 class="admin-section-heading" id="transactions-heading">Transaction Overview</h2>
                        <span class="badge rounded-pill text-bg-light">12 bulan terakhir</span>
                    </div>
                    <div class="admin-chart-wrap">
                        <canvas id="transactionsChart" aria-label="Grafik penjualan bulanan" role="img"></canvas>
                    </div>
                </section>

                <div class="admin-bottom-grid">
                    <section class="admin-panel" aria-labelledby="order-status-heading">
                        <h2 class="admin-section-heading" id="order-status-heading">Order Status Distribution</h2>
                        <div class="admin-status-chart-wrap">
                            <canvas id="orderStatusChart" aria-label="Diagram distribusi status order" role="img"></canvas>
                        </div>
                    </section>

                    <section class="admin-panel" aria-labelledby="feedback-heading">
                        <h2 class="admin-section-heading" id="feedback-heading">Recent Feedbacks</h2>
                        @if($recentFeedback->count() > 0)
                            <div class="admin-feedback-list">
                            @foreach($recentFeedback as $feedback)
                                <article class="admin-feedback-item">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="admin-feedback-user">
                                            <span class="admin-feedback-avatar" aria-hidden="true">
                                                {{ strtoupper(substr($feedback->user->name ?? $feedback->user->username ?? 'N', 0, 1)) }}
                                            </span>
                                            <strong class="admin-feedback-name">{{ $feedback->user->name ?? $feedback->user->username ?? 'N/A' }}</strong>
                                        </div>
                                        <form action="{{ route('admin.delete_feedback', $feedback->feedback_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-feedback-delete" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</button>
                                        </form>
                                    </div>
                                    <p class="admin-feedback-message">{{ $feedback->message }}</p>
                                    @if($feedback->created_at)
                                        <div class="admin-feedback-meta">{{ $feedback->created_at->format('d M Y') }}</div>
                                    @endif
                                </article>
                            @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-0 small">No feedback yet.</p>
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            <aside class="admin-panel admin-top-stores" aria-labelledby="top-stores-heading">
                <h2 class="admin-section-heading" id="top-stores-heading">Top 10 Stores by Sale</h2>
                @if($topStores->isNotEmpty())
                    <div class="admin-store-list">
                        @foreach($topStores as $index => $store)
                            <div class="admin-store-item">
                                <span class="admin-store-rank">{{ $index + 1 }}</span>
                                <div class="admin-store-info">
                                    <div class="admin-store-name">{{ $store->name ?: $store->username }}</div>
                                    <div class="admin-store-sales">Rp {{ number_format((float) $store->total_sales, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-0 small">Belum ada data penjualan toko.</p>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const currencyFormat = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    });

    new Chart(document.getElementById('transactionsChart'), {
        type: 'line',
        data: {
            labels: @json($transactionLabels),
            datasets: [{
                label: 'Penjualan',
                data: @json($transactionTotals),
                borderColor: '#1bb89a',
                backgroundColor: 'rgba(27, 184, 154, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#1bb89a'
            }]
        },
        options: {
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => currencyFormat.format(context.parsed.y)
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#edf0f5' },
                    ticks: { callback: (value) => currencyFormat.format(value) }
                },
                x: { grid: { display: false } }
            }
        }
    });

    const orderStatuses = @json($orderStatusDistribution);
    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: orderStatuses.length
                ? orderStatuses.map((item) => item.status.replaceAll('_', ' ').replace(/\b\w/g, (letter) => letter.toUpperCase()))
                : ['No orders'],
            datasets: [{
                data: orderStatuses.length ? orderStatuses.map((item) => item.total) : [1],
                backgroundColor: ['#1bb89a', '#5978d1', '#f4b740', '#ed7d5d', '#a883d1', '#6cb4c6'],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, pointStyle: 'circle', padding: 15, boxWidth: 8 }
                },
                tooltip: { enabled: orderStatuses.length > 0 }
            }
        }
    });
</script>
@endsection