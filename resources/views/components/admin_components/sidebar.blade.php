<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.dashboard') ? '' : 'collapsed' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Menu -->
        @php
            $isMenuActive =
                Request::routeIs('admin.berita') ||
                Request::routeIs('admin.add.berita') ||
                Request::routeIs('admin.edit.berita') ||
                Request::routeIs('admin.jadwal') ||
                Request::routeIs('admin.add.jadwal') ||
                Request::routeIs('admin.edit.jadwal');
        @endphp

        <li class="nav-item">
            <a class="nav-link {{ $isMenuActive ? '' : 'collapsed' }}" data-bs-target="#menu-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Menu</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="menu-nav" class="nav-content collapse {{ $isMenuActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.berita') }}"
                        class="{{ Request::routeIs('admin.berita', 'admin.add.berita', 'admin.edit.berita') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Berita</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.jadwal') }}"
                        class="{{ Request::routeIs('admin.jadwal', 'admin.add.jadwal', 'admin.edit.jadwal') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Jadwal</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Menu -->

        <!-- Data -->
        @php
            $isDataActive =
                Request::routeIs('admin.data_cpb') ||
                Request::routeIs('admin.user.petugas.add') ||
                // Request::routeIs('admin.add.data_cpb') ||
                // Request::routeIs('admin.edit.data_cpb') ||
                Request::routeIs('admin.data_role');
            // Request::routeIs('admin.add.data_petugas') ||
            // Request::routeIs('admin.edit.data_petugas') ||
            // Request::routeIs('admin.data_user') ||
            // Request::routeIs('admin.add.data_user') ||
            // Request::routeIs('admin.edit.data_user');
        @endphp

        <li class="nav-item">
            <a class="nav-link {{ $isDataActive ? '' : 'collapsed' }}" data-bs-target="#data-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-database"></i><span>Data</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-nav" class="nav-content collapse {{ $isDataActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.data_cpb') }}"
                        class="{{ Request::routeIs('admin.data_cpb') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Data CPB</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.data_role') }}"
                        class="{{ Request::routeIs('admin.data_role', 'admin.user.petugas.add') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Data Petugas dan User</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Data -->

    </ul>
</aside><!-- End Sidebar -->
