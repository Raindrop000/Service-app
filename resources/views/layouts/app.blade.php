<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulia Jaya Motor - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --deep-blue: #0f172a;
            --sidebar-bg: #1e293b;
            --light-gray: #f8fafc;
            --border-color: #e2e8f0;
            --accent-color: #3b82f6;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray);
            color: #334155;
            overflow-x: hidden;
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0; z-index: 100;
            transition: all 0.3s;
        }
        .sidebar .brand {
            height: 70px;
            background-color: var(--deep-blue);
            display: flex; align-items: center;
            padding: 0 20px; color: #fff;
            font-weight: 700; font-size: 1.1rem;
        }
        .sidebar-menu { padding: 20px 0; list-style: none; margin: 0; }
        .sidebar-menu li a {
            display: flex; align-items: center;
            padding: 12px 25px; color: #94a3b8;
            text-decoration: none; transition: all 0.2s;
            font-weight: 500;
        }
        .sidebar-menu li a:hover, .sidebar-menu li.active a {
            color: #fff; background-color: rgba(255,255,255,0.05);
            border-left: 4px solid var(--accent-color);
        }
        .sidebar-menu li a i { margin-right: 15px; font-size: 1.1rem; width: 20px; }
        .main-wrapper { margin-left: 260px; min-height: 100vh; transition: all 0.3s; }
        .top-navbar {
            height: 70px; background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center;
            justify-content: space-between; padding: 0 30px;
        }
        .content-body { padding: 30px; }
        @media (max-width: 991.98px) {
            .sidebar { margin-left: -260px; }
            .sidebar.active { margin-left: 0; }
            .main-wrapper { margin-left: 0; }
            .main-wrapper.active { margin-left: 260px; }
        }
    </style>
</head>
<body>

    <nav class="sidebar" id="sidebarNav">
        <div class="brand">
            <i class="fa-solid fa-car-wrench text-primary me-2"></i> MULIA JAYA MOTOR
        </div>
        <ul class="sidebar-menu">
    <li class="{{ Request::is('/') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i>Dashboard</a>
    </li>
    
    <li class="{{ Request::is('customers*') ? 'active' : '' }}">
        <a href="{{ route('customers.index') }}"><i class="fa-solid fa-users"></i>Pelanggan</a>
    </li>
    
    <li class="{{ Request::is('vehicles*') ? 'active' : '' }}">
        <a href="{{ route('vehicles.index') }}"><i class="fa-solid fa-car"></i>Kendaraan</a>
    </li>
    
    <li class="{{ Request::is('services*') ? 'active' : '' }}">
        <a href="{{ route('services.index') }}"><i class="fa-solid fa-screwdriver-wrench"></i>Data Service</a>
    </li>
    
    <li class="{{ Request::is('spareparts*') ? 'active' : '' }}">
        <a href="{{ route('spareparts.index') }}"><i class="fa-solid fa-boxes-stacked"></i>Spareparts</a>
    </li>
    
    <li class="{{ Request::is('invoices*') ? 'active' : '' }}">
        <a href="{{ route('invoices.index') }}"><i class="fa-solid fa-file-invoice-dollar"></i>Invoices</a>
    </li>
    
    <li class="{{ Request::is('reminder*') ? 'active' : '' }}">
        <a href="{{ route('reminder.index') }}"><i class="fa-solid fa-bell text-warning"></i>Reminder Service</a>
    </li>
</ul>
    </nav>

    <div class="main-wrapper" id="mainWrapper">
        <header class="top-navbar">
            <button class="btn btn-light d-lg-none" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="h5 m-0 d-none d-lg-block fw-semibold text-dark">Mulia Jaya Motor Apps</div>
            <div class="fw-medium small"><i class="fa-solid fa-user-circle me-1"></i> Admin Bengkel</div>
        </header>

        <main class="content-body">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fitur Toggle Sidebar Responsif Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebarNav').classList.toggle('active');
            document.getElementById('mainWrapper').classList.toggle('active');
        });
    </script>
    @stack('scripts')
</body>
</html>