<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.7.2/css/all.css">

    <link href="{{ asset('midragon/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
        /* @import url('https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@500&display=swap'); */

        tr#table-row:nth-child(odd) {
            background-color: rgb(249 250 251 / 1) !important;
        }

    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="layout-3" style="font-family: 'Inter', sans-serif;">
    <div id="app">
        <div class="main-wrapper container">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <a href="#" class="navbar-brand sidebar-gone-hide">Point Of Sales</a>
                <div class="navbar-nav">
                    <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
                </div>
                <form class="form-inline ml-auto">
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <div class="d-sm-none d-lg-inline-block">Hi, Fahmi Ibrahim</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">Logged in 5 min ago</div>
                            <a href="{{ url('profile') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <a href="#" class="dropdown-item has-icon">
                                <i class="fas fa-bolt"></i> Activities
                            </a>
                            <a href="#" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="dropdown-item has-icon" onclick="event.preventDefault();
                                this.closest('form').submit();">
                                    <i class="far fa-sign-out-alt"></i> Logout
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <nav class="navbar navbar-secondary navbar-expand-lg">
                <div class="container">
                    <ul class="navbar-nav">
                        <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                            <a href="{{ url('dashboard') }}" class="nav-link"><i
                                    class="fas fa-home"></i><span>Dashboard</span></a>
                        </li>
                        <li class="nav-item dropdown
                            {{ (request()->is('daftar-barang')) ? 'active' : '' }}
                            {{ (request()->is('kategori')) ? 'active' : '' }}
                            {{ (request()->is('satuan')) ? 'active' : '' }}
                            {{ (request()->is('daftar-supplier')) ? 'active' : '' }}
                            {{ (request()->is('daftar-customer')) ? 'active' : '' }}
                        ">
                            <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i
                                    class="far fa-clone"></i><span>Master Data</span></a>
                            <ul class="dropdown-menu">
                                <li class="nav-item dropdown
                                    {{ (request()->is('daftar-barang')) ? 'active' : '' }}
                                    {{ (request()->is('kategori')) ? 'active' : '' }}
                                    {{ (request()->is('satuan')) ? 'active' : '' }}
                                "><a href="#" class="nav-link has-dropdown">Data Barang</a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ (request()->is('daftar-barang')) ? 'active' : '' }}"><a
                                                href="{{ url('daftar-barang') }}" class="nav-link">Daftar Barang</a>
                                        </li>
                                        <li class="nav-item {{ (request()->is('kategori')) ? 'active' : '' }}"><a
                                                href="{{ url('kategori') }}" class="nav-link">Kategori</a></li>
                                        <li class="nav-item {{ (request()->is('satuan')) ? 'active' : '' }}"><a
                                                href="{{ url('satuan') }}" class="nav-link">Satuan</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown
                                    {{ (request()->is('daftar-supplier')) ? 'active' : '' }}
                                    {{ (request()->is('daftar-customer')) ? 'active' : '' }}
                                "><a href="#" class="nav-link has-dropdown">Data Data</a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ (request()->is('daftar-supplier')) ? 'active' : '' }}"><a
                                                href="{{ url('daftar-supplier') }}" class="nav-link">Daftar Supplier</a>
                                        </li>
                                        <li class="nav-item {{ (request()->is('daftar-customer')) ? 'active' : '' }}"><a
                                                href="{{ url('daftar-customer') }}" class="nav-link">Daftar Customer</a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item dropdown
                            {{ (request()->is('saldo-awal-barang')) ? 'active' : '' }}
                            {{ (request()->is('stok-masuk')) ? 'active' : '' }}
                            {{ (request()->is('stok-keluar')) ? 'active' : '' }}
                            {{ (request()->is('stok-opname')) ? 'active' : '' }}
                        ">
                            <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i
                                    class="far fa-inventory"></i><span>Persediaan</span></a>
                            <ul class="dropdown-menu">
                                <li class="nav-item {{ (request()->is('saldo-awal-barang')) ? 'active' : '' }}"><a
                                        href="{{ url('saldo-awal-barang') }}" class="nav-link">Saldo Awal Barang</a>
                                </li>
                                <li class="nav-item {{ (request()->is('stok-masuk')) ? 'active' : '' }}"><a
                                        href="{{ url('stok-masuk') }}" class="nav-link">Stok Masuk</a></li>
                                <li class="nav-item {{ (request()->is('stok-keluar')) ? 'active' : '' }}"><a
                                        href="{{ url('stok-keluar') }}" class="nav-link ">Stok
                                        Keluar</a></li>
                                <li class="nav-item {{ (request()->is('stok-opname')) ? 'active' : '' }}"><a
                                        href="{{ url('stok-opname') }}" class="nav-link">Stok Opname</a></li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->is('kartu-stock')) ? 'active' : '' }}">
                            <a href="{{ url('kartu-stock') }}" class="nav-link"><i class="far fa-pallet"></i><span>Kartu
                                    Stock</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('transaksi')) ? 'active' : '' }}">
                            <a href="{{ url('transaksi') }}" class="nav-link"><i
                                    class="far fa-hand-holding-box"></i><span>Transaksi</span></a>
                        </li>
                        <li class="nav-item dropdown
                            {{ (request()->is('laporan/data-barang')) ? 'active' : '' }}
                            {{ (request()->is('laporan/daftar-supplier')) ? 'active' : '' }}
                            {{ (request()->is('laporan/daftar-customer')) ? 'active' : '' }}
                            {{ (request()->is('laporan/saldo-awal-barang')) ? 'active' : '' }}
                            {{ (request()->is('laporan/stok-masuk')) ? 'active' : '' }}
                            {{ (request()->is('laporan/stok-keluar')) ? 'active' : '' }}
                            {{ (request()->is('laporan/stok-opname')) ? 'active' : '' }}
                            {{ (request()->is('laporan/kartu-stok')) ? 'active' : '' }}
                            {{ (request()->is('laporan/transaksi')) ? 'active' : '' }}
                        ">
                            <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i
                                    class="far fa-folders"></i><span>Laporan</span></a>
                            <ul class="dropdown-menu">
                                <li class="nav-item dropdown
                                {{ (request()->is('laporan/data-barang')) ? 'active' : '' }}
                                {{ (request()->is('laporan/daftar-supplier')) ? 'active' : '' }}
                                {{ (request()->is('laporan/daftar-customer')) ? 'active' : '' }}
                                "><a href="#" class="nav-link has-dropdown">Data Data</a>
                                    <ul class="dropdown-menu">
                                        <li
                                            class="nav-item {{ (request()->is('laporan/data-barang')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/data-barang') }}" class="nav-link">Data Barang</a>
                                        </li>
                                        <li
                                            class="nav-item {{ (request()->is('laporan/daftar-supplier')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/daftar-supplier') }}" class="nav-link">Daftar
                                                Supplier</a></li>
                                        <li
                                            class="nav-item {{ (request()->is('laporan/daftar-customer')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/daftar-customer') }}" class="nav-link">Daftar
                                                Customer</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown
                                    {{ (request()->is('laporan/saldo-awal-barang')) ? 'active' : '' }}
                                    {{ (request()->is('laporan/stok-masuk')) ? 'active' : '' }}
                                    {{ (request()->is('laporan/stok-keluar')) ? 'active' : '' }}
                                    {{ (request()->is('laporan/stok-opname')) ? 'active' : '' }}
                                    {{ (request()->is('laporan/kartu-stok')) ? 'active' : '' }}
                                "><a href="#" class="nav-link has-dropdown">Persediaan</a>
                                    <ul class="dropdown-menu">
                                        <li
                                            class="nav-item {{ (request()->is('laporan/saldo-awal-barang')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/saldo-awal-barang') }}" class="nav-link">Saldo Awal
                                                Barang</a></li>
                                        <li
                                            class="nav-item {{ (request()->is('laporan/stok-masuk')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/stok-masuk') }}" class="nav-link">Stok Masuk</a>
                                        </li>
                                        <li
                                            class="nav-item {{ (request()->is('laporan/stok-keluar')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/stok-keluar') }}" class="nav-link">Stok Keluar</a>
                                        </li>
                                        <li
                                            class="nav-item {{ (request()->is('laporan/stok-opname')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/stok-opname') }}" class="nav-link">Stok Opname</a>
                                        </li>
                                        <li
                                            class="nav-item {{ (request()->is('laporan/kartu-stok')) ? 'active' : '' }}">
                                            <a href="{{ url('laporan/kartu-stok') }}" class="nav-link">Kartu Stok</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item {{ (request()->is('laporan/transaksi')) ? 'active' : '' }}"><a
                                        href="#" class="nav-link">Transaksi Penjualan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->is('setting-user')) ? 'active' : '' }}">
                            <a href="{{ url('setting-user') }}" class="nav-link"><i
                                    class="fas fa-cogs"></i><span>Setting User</span></a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="main-content">
                @yield('content')
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{ date('Y') }} <div class="bullet"></div> Create By <a
                        href="https://facebook.com/fahmiibrahimdev">Fahmi Ibrahim</a>
                </div>
                <div class="footer-right">
                    1.0
                </div>
            </footer>
        </div>
    </div>


    @livewireScripts
    <script>
        window.livewire.on("dataStore", () => {
            $("#tambahDataModal").modal("hide");
            $("#ubahDataModal").modal("hide");
        });

    </script>
    <script src="{{ asset('midragon/select2/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('midragon/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>
    <script>
        window.onbeforeunload = function () {
            window.scrollTo(5, 75);
        };

    </script>
    <script src="{{ asset('midragon/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @stack('scripts')
</body>

</html>
