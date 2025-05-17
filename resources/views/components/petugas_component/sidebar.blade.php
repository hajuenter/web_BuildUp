<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('petugas/inputCPB*') ? '' : 'collapsed' }}"
                href="{{ route('petugas.inputcpb') }}">
                <i class="bi bi-person-lines-fill"></i>
                <span>Input Data CPB</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('petugas/dataCPB*') || Request::is('petugas/cpb/edit/*') ? '' : 'collapsed' }}"
                href="{{ route('petugas.datacpb') }}">
                <i class="bi bi-journal-plus"></i>
                <span>Data CPB</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar -->
