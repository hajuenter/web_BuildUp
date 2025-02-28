<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('petugas/inputCPB*') || Request::is('petugas/cpb/edit/*') ? '' : 'collapsed' }}"
                href="{{ route('petugas.inputcpb') }}">
                <i class="bi bi-clipboard-plus"></i>
                <span>Input Data CPB</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar -->
