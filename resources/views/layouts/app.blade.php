<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Toko Alat Kesehatan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .alkeshub-header {
            display: flex;
            min-height: 74px;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 12px clamp(18px, 4vw, 54px);
            border-bottom: 1px solid #edf1f2;
            background: #fff;
        }

        .alkeshub-header-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #172b4d;
            font-size: 19px;
            font-weight: 750;
            letter-spacing: 1.4px;
            text-decoration: none;
        }

        .alkeshub-header-title {
            margin: 0;
            color: #172b4d;
            font-size: clamp(18px, 2vw, 22px);
            font-weight: 750;
            letter-spacing: -.4px;
        }

        .alkeshub-header-mark {
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 11px;
            background: #1bb89a;
            color: #102b45;
            font-size: 18px;
            font-weight: 800;
        }

        .alkeshub-header-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px 6px 6px;
            border: 1px solid #e6eaf0;
            border-radius: 30px;
            background: #fff;
            color: #172b4d;
        }

        .alkeshub-header-avatar {
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

        .alkeshub-header-name {
            font-size: 13px;
            font-weight: 650;
        }

        .admin-route-main > .alkeshub-header,
        .admin-main > .alkeshub-header {
            margin: -28px calc(-1 * clamp(20px, 4vw, 54px)) 28px;
        }

        @media (max-width: 575.98px) {
            .alkeshub-header {
                min-height: 66px;
                padding: 10px 14px;
            }

            .alkeshub-header-brand {
                font-size: 16px;
            }

            .alkeshub-header-name {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .admin-route-main > .alkeshub-header,
            .admin-main > .alkeshub-header {
                margin: -24px -16px 24px;
            }
        }
    </style>
    @if(request()->routeIs('admin.*'))
        <style>
            .admin-route-shell {
                display: flex;
                min-height: 100vh;
                background: #fff;
                color: #172b4d;
            }

            .admin-route-shell .admin-sidebar {
                box-sizing: border-box;
                width: 258px;
                flex: 0 0 258px;
                padding: 28px 18px;
                background: #fff;
                color: #172b4d;
            }

            .admin-route-shell .admin-brand {
                display: flex;
                align-items: center;
                gap: 11px;
                padding: 0 10px 35px;
                color: #172b4d;
                font-size: 19px;
                font-weight: 750;
                letter-spacing: 1.4px;
                text-decoration: none;
            }

            .admin-route-shell .admin-brand-mark {
                display: grid;
                width: 36px;
                height: 36px;
                place-items: center;
                border-radius: 11px;
                background: #1bb89a;
                color: #102b45;
                font-size: 18px;
                font-weight: 800;
            }

            .admin-route-shell .admin-nav-label {
                padding: 0 11px 12px;
                color: #8491a5;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 1.4px;
                text-transform: uppercase;
            }

            .admin-route-shell .admin-nav {
                display: grid;
                gap: 7px;
            }

            .admin-route-shell .admin-nav a {
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

            .admin-route-shell .admin-nav a:hover,
            .admin-route-shell .admin-nav a.active {
                background: #e8f8f4;
                color: #138c76;
            }

            .admin-route-shell .admin-nav-icon {
                width: 20px;
                color: #1bb89a;
                font-size: 16px;
                text-align: center;
            }

            .admin-route-main {
                min-width: 0;
                flex: 1;
                padding: 28px clamp(20px, 4vw, 54px) 48px;
            }

            .admin-route-content > .container {
                width: 100%;
                max-width: none;
                margin: 0 !important;
                padding: 0;
            }

            .admin-route-content > .container > .row > .col-md-12 > .card {
                border: 0;
                border-radius: 0;
                background: #fff;
            }

            .admin-route-content > .container > .row > .col-md-12 > .card > .card-header {
                padding: 0 0 14px;
                border: 0;
                background: #fff;
            }

            .admin-route-content > .container > .row > .col-md-12 > .card > .card-header h1 {
                margin: 0;
                color: #172b4d;
                font-size: 21px;
                font-weight: 700;
            }

            .admin-route-content > .container > .row > .col-md-12 > .card > .card-body {
                padding: 0;
            }

            .admin-route-content .admin-list-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 18px;
            }

            .admin-route-content .admin-list-description {
                margin: 0;
                color: #75839a;
                font-size: 13px;
            }

            .admin-route-content .admin-list-table {
                margin-bottom: 0;
                color: #35445c;
                font-size: 13px;
            }

            .admin-route-content .admin-list-table thead th {
                padding: 12px 14px;
                border-bottom: 1px solid #e6edf0;
                background: #f5f8f7;
                color: #75839a;
                font-size: 13px;
                font-weight: 700;
                letter-spacing: .5px;
                text-transform: uppercase;
            }

            .admin-route-content .admin-list-table tbody td {
                padding: 14px;
                border-color: #edf1f2;
                background: #fff;
                vertical-align: middle;
            }

            .admin-route-content .admin-list-table tbody tr:last-child td {
                border-bottom: 0;
            }

            .admin-route-content .admin-action-primary {
                border: 0;
                background: #1bb89a;
                color: #fff;
            }

            .admin-route-content .admin-action-primary:hover {
                background: #159d83;
                color: #fff;
            }

            .admin-route-content .admin-action-outline {
                border-color: #b7e9dc;
                color: #138c76;
            }

            .admin-route-content .admin-action-outline:hover {
                border-color: #1bb89a;
                background: #e8f8f4;
                color: #107762;
            }

            .admin-route-content .admin-action-danger {
                border: 0;
                background: #dc5460;
                color: #fff;
            }

            .admin-route-content .admin-action-danger:hover {
                background: #c94350;
                color: #fff;
            }

            @media (max-width: 767.98px) {
                .admin-route-shell {
                    display: block;
                }

                .admin-route-shell .admin-sidebar {
                    width: 100%;
                    padding: 16px;
                }

                .admin-route-shell .admin-brand {
                    padding: 0 4px 16px;
                }

                .admin-route-shell .admin-nav-label {
                    display: none;
                }

                .admin-route-shell .admin-nav {
                    display: flex;
                    overflow-x: auto;
                    gap: 6px;
                    padding-bottom: 3px;
                }

                .admin-route-shell .admin-nav a {
                    flex: 0 0 auto;
                    min-height: 40px;
                    padding: 0 10px;
                    font-size: 12px;
                }

                .admin-route-main {
                    padding: 24px 16px 36px;
                }

                .admin-route-content .admin-list-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
                }
            }
        </style>
    @endif
</head>
<body class="d-flex flex-column min-vh-100">
    @if(request()->routeIs('admin.dashboard'))
        <main class="flex-grow-1">
            @yield('content')
        </main>
    @elseif(request()->routeIs('admin.*'))
        <div class="admin-route-shell">
            @include('admin.partials.sidebar')
            <main class="admin-route-main">
                @include('layouts.alkeshub-header')
                <div class="admin-route-content">
                    @yield('content')
                </div>
            </main>
        </div>
    @elseif(request()->routeIs('profile'))
        @include('layouts.alkeshub-header')
        <main class="flex-grow-1">
            @yield('content')
        </main>
    @elseif(request()->routeIs('vendor.dashboard'))
        @include('layouts.alkeshub-header')
        <main class="flex-grow-1">
            @yield('content')
        </main>
        @include('layouts.footer')
    @else
        @include('layouts.header')
        <main class="flex-grow-1">
            @yield('content')
        </main>
        @include('layouts.footer')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
