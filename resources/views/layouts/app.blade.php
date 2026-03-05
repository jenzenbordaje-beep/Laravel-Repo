<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            padding-top: 20px;
        }
        .sidebar a, .sidebar button {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .sidebar a:hover, .sidebar button:hover {
            background-color: #34495e;
        }
        .sidebar a.active {
            background-color: #3498db;
            border-left: 4px solid #2980b9;
        }

        /* dropdown submenu links indentation */
        .sidebar .collapse a {
            padding-left: 40px;
        }

        /* toggle buttons show arrow */
        .sidebar button.sidebar-toggle::after {
            content: '▾';
            float: right;
            transition: transform 0.2s;
        }
        .sidebar button.sidebar-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }
        .navbar-brand {
            padding: 0 20px;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }
        .top-navbar {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .badge-role {
            display: inline-block;
            padding: 5px 10px;
            background-color: #3498db;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .sidebar .section-title {
            padding: 15px 20px;
            font-weight: bold;
            color: #95a5a6;
            text-transform: uppercase;
            font-size: 12px;
            margin-top: 20px;
        }
        .alert-dismissible {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            width: 350px;
        }
        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #2c3e50;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-card .value {
            font-size: 28px;
            color: #3498db;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
            }
        }
    </style>
    @yield('extra-css')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="navbar-brand">📦 Inventory</div>

        <a href="{{ route('dashboard') }}" class="@if(Route::currentRouteName() == 'dashboard') active @endif">
            📊 Dashboard
        </a>

        @if(Auth::user()->isEmployee())
            <div class="section-title">Employee</div>
            <a href="{{ route('equipment.index') }}" class="@if(Route::currentRouteName() == 'equipment.index') active @endif">📋 Available Equipment</a>
            <a href="{{ route('requests.create') }}" class="@if(Route::currentRouteName() == 'requests.create') active @endif">➕ Request Equipment</a>
            <a href="{{ route('requests.index') }}" class="@if(Route::currentRouteName() == 'requests.index') active @endif">📝 My Requests</a>
            <a href="{{ route('activity-logs.index') }}" class="@if(Route::currentRouteName() == 'activity-logs.index') active @endif">📜 Activity Log</a>
        @endif

        @php
            $showAdminModules = Auth::user()->isAdmin() || Auth::user()->isSuperAdmin();
        @endphp

        @if($showAdminModules)
            <div class="section-title">Administration</div>

            <!-- Employee Module dropdown -->
            <button class="sidebar-toggle @if(in_array(Route::currentRouteName(), ['admin.users.create','requests.index','activity-logs.index'])) active @endif"
                    data-bs-toggle="collapse" data-bs-target="#employeeModule" aria-expanded="{{ in_array(Route::currentRouteName(), ['admin.users.create','requests.index','activity-logs.index']) ? 'true' : 'false' }}">
                👥 Employee Module
            </button>
            <div class="collapse @if(in_array(Route::currentRouteName(), ['admin.users.index','admin.users.create','requests.index','activity-logs.index'])) show @endif" id="employeeModule">
                @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.users.create') }}" class="@if(Route::currentRouteName() == 'admin.users.create') active @endif">➕ Create User</a>
                @endif
                <a href="{{ route('requests.index') }}" class="@if(Route::currentRouteName() == 'requests.index') active @endif">✅ Approve Requests</a>
                <a href="{{ route('activity-logs.index') }}" class="@if(Route::currentRouteName() == 'activity-logs.index') active @endif">📜 Activity Logs</a>
            </div>

            <!-- Equipment Module dropdown -->
            <button class="sidebar-toggle @if(in_array(Route::currentRouteName(), ['equipment.index','equipment.create'])) active @endif"
                    data-bs-toggle="collapse" data-bs-target="#equipmentModule" aria-expanded="{{ in_array(Route::currentRouteName(), ['equipment.index','equipment.create']) ? 'true' : 'false' }}">
                📦 Equipment Module
            </button>
            <div class="collapse @if(in_array(Route::currentRouteName(), ['equipment.index','equipment.create'])) show @endif" id="equipmentModule">
                <a href="{{ route('equipment.index') }}" class="@if(Route::currentRouteName() == 'equipment.index') active @endif">📋 View / Edit Equipment</a>
                <a href="{{ route('equipment.create') }}" class="@if(Route::currentRouteName() == 'equipment.create') active @endif">➕ Add Equipment</a>
                <a href="{{ route('equipment.index', ['archived' => 1]) }}" class="@if(request()->query('archived')) active @endif">🗄️ Archived Equipment</a>
            </div>
        @endif

        <div class="section-title">Account</div>
        <form method="POST" action="{{ route('logout') }}" style="padding: 0;">
            @csrf
            <button style="padding: 12px 20px;">🚪 Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="user-info">
                <span>{{ Auth::user()->name }}</span>
                <span class="badge-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span>
            </div>
        </div>

        <!-- Alerts -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Errors:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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

        <!-- Page Content -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
</body>
</html>
