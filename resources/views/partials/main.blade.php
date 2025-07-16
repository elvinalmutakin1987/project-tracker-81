<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            height: 100vh;
            /* Tambahkan tinggi penuh viewport */
            background-color: #343a40;
            color: black;
            transition: all 0.3s;
            overflow-y: auto;
            /* Scroll vertikal */
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 56px;
            /* Agar tidak tertutup navbar */
        }

        .sidebar a {
            color: black;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #0d6efd;
            color: white;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .content {
            flex: 1;
            padding: 60px 20px 80px;
            transition: margin-left 0.3s;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
        }

        .sidebar-select {
            background-color: #616161;
            color: white
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger fixed-top">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <button class="btn btn-outline-light me-2" id="toggleSidebar">
                    ☰
                </button>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('home') }}">PT. Media Bersama
                        Sukses</a>
                </li>
            </ul>
            <div class="d-flex">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="dropdown02"
                            data-bs-toggle="dropdown" aria-expanded="false">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown02">
                            <li><a class="dropdown-item" href="#">Change Password</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar pt-5 position-fixed bg-light">
            <a href="{{ route('home') }}" class="py-3"
                style="{{ request()->routeIs('home') ? 'background-color: black; color: white' : '' }}">Home</a>
            @if (Auth::user()->hasAnyPermission('task_board'))
                <a href="{{ route('task_board.index') }}"
                    style="{{ request()->routeIs('task_board.*') ? 'background-color: black; color: white' : '' }}">
                    Task Board
                </a>
            @endif
            @if (Auth::user()->hasPermissionTo('project'))
                <a href="{{ route('project.index') }}"
                    style="{{ request()->routeIs('project.*') ? 'background-color: black; color: white' : '' }}">
                    Project
                </a>
            @endif
            @if (Auth::user()->hasPermissionTo('work_order'))
                <a href="#">Work Order</a>
            @endif
            @if (Auth::user()->hasPermissionTo('assignment'))
                <a href="#">Assignment</a>
            @endif
            @if (Auth::user()->hasPermissionTo('tool_kit'))
                <a href="#">Toolkit</a>
            @endif
            @if (Auth::user()->hasPermissionTo('report'))
                <a href="#">Report</a>
            @endif
            @if (Auth::user()->hasPermissionTo('setting'))
                <a href="{{ route('setting') }}"
                    style="{{ request()->routeIs(['setting', 'work_type.*', 'role.*', 'user.*']) ? 'background-color: black; color: white' : '' }}">Setting
                </a>
            @endif
            <a href="#">About</a>
            <a href="{{ route('logout') }}">Logout</a>
        </div>

        @yield('content')


    </div>

    <!-- Footer -->
    {{-- <footer class="footer fixed-bottom position-fixed bg-light">
        <small>&copy; 2025 <a href="https://mbscctv.com" target="_blank">PT. Media Bersama Sukses</a>. Author :
            Elvin</small>
    </footer> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '0';
            } else {
                mainContent.style.marginLeft = '250px';
            }
        });
    </script>

    @stack('javascript')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>

</html>
