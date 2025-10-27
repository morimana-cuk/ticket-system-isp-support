<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- <li class="nav-item">
            <a class="nav-link " href="index.html">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li> --}}
        @if (Auth::check() && in_array(Auth::user()->role, ['Admin', 'CS']))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.index') }}">
                    <i class="bi bi-person"></i><span>Pelanggan</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('ticket.index') }}">
                <i class="bi bi-bug"></i><span>Lapor Masalah</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" href="components-alerts.html">
                <i class="bi bi-person"></i><span>Pegawai</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="components-alerts.html">
                <i class="bi bi-person"></i><span>Akun</span>
            </a>
        </li> --}}
        <!-- End Dashboard Nav -->

    </ul>

</aside>
