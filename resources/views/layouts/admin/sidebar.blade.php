<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu"
                        title="Main"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dataproduct') }}"
                        class="nav-link {{ request()->is('dataproduct') ? 'active' : '' }}">
                        <i class="icon-store2"></i>
                        <span>
                            Data Product
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent') }}" class="nav-link {{ request()->is('agent') ? 'active' : '' }}">
                        <i class="icon-collaboration"></i>
                        <span>
                            Data Agent
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor') }}" class="nav-link {{ request()->is('vendor') ? 'active' : '' }}">
                        <i class="icon-accessibility"></i>
                        <span>
                            Data Vendor
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('operasional') }}"
                        class="nav-link {{ request()->is('operasional') ? 'active' : '' }}">
                        <i class="icon-accessibility2"></i>
                        <span>
                            Data Operasional
                        </span>
                    </a>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ request()->is('beli') || request()->is('jualpagi') || request()->is('jualsore') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-cart"></i> <span>Transaksi</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="User pages">
                        <li class="nav-item"><a href="{{ route('beli') }}"
                                class="nav-link {{ request()->is('beli') ? 'active' : '' }}">Barang Datang</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('jualpagi') }}"
                                class="nav-link {{ request()->is('jualpagi') ? 'active' : '' }}">Jual Pagi</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('jualsore') }}"
                                class="nav-link {{ request()->is('jualsore') ? 'active' : '' }}">Jual Sore</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ request()->is('datahutangvendor') || request()->is('datahutangagent') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-drawer-out"></i> <span>Data Hutang</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="User pages">
                        <li class="nav-item"><a href="{{ url('datahutangvendor') }}"
                                class="nav-link {{ request()->is('datahutangvendor') ? 'active' : '' }}"
                                class="nav-link">Hutang Vendor</a>
                        </li>
                        <li class="nav-item"><a href="{{ url('datahutangagent') }}"
                                class="nav-link {{ request()->is('datahutangagent') ? 'active' : '' }}"
                                class="nav-link">Hutang Agent</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ request()->is('karyawan') || request()->is('gaji') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-users4"></i><span>Data Karyawan</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="User pages">
                        <li class="nav-item"><a href="{{ url('karyawan') }}"
                                class="nav-link {{ request()->is('karyawan') ? 'active' : '' }}"
                                class="nav-link">Karyawan</a>
                        </li>
                        <li class="nav-item"><a href="{{ url('gaji') }}"
                                class="nav-link {{ request()->is('gaji') ? 'active' : '' }}" class="nav-link">Gaji</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ request()->is('laporan') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-file-stats"></i><span>Laporan</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="User pages">
                        <li class="nav-item"><a href="{{ url('laporan') }}"
                                class="nav-link {{ request()->is('laporan') ? 'active' : '' }}" class="nav-link">Stok
                                Barang</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ request()->is('user') || request()->is('role') || request()->is('permission') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-user-lock"></i> <span>User
                            management</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="User pages">
                        <li class="nav-item"><a href="{{ url('user') }}"
                                class="nav-link {{ request()->is('user') ? 'active' : '' }}" class="nav-link">User
                                list</a>
                        </li>
                        <li class="nav-item"><a href="{{ url('role') }}"
                                class="nav-link {{ request()->is('role') ? 'active' : '' }}" class="nav-link">Role
                            </a></li>
                        <li class="nav-item"><a href="{{ url('permission') }}"
                                class="nav-link {{ request()->is('permission') ? 'active' : '' }}"
                                class="nav-link">Permission</a></li>
                    </ul>
                </li>

                <!-- /page kits -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
