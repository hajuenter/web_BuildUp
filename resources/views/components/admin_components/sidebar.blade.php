<!-- ======= Sidebar ======= -->
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
                Request::routeIs('admin.jadwal');
        @endphp

        <li class="nav-item">
            <a class="nav-link {{ $isMenuActive ? '' : 'collapsed' }}" data-bs-target="#forms-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Menu</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse {{ $isMenuActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.berita') }}"
                        class="{{ Request::routeIs('admin.berita') || Request::routeIs('admin.add.berita') || Request::routeIs('admin.edit.berita') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Berita</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.jadwal') }}"
                        class="{{ Request::routeIs('admin.jadwal') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Jadwal</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Menu -->

    </ul>
</aside><!-- End Sidebar -->
