<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ Route::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Route::is('clustering') || Route::is('clustering.detail') ? 'active' : '' }} has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Clustering</span>
                    </a>
                    <ul class="submenu " style="display: {{ Route::is('clustering') || Route::is('clustering.detail') ? 'block' : 'none' }}">
                        <li class="submenu-item ">
                            <a href="{{ route('clustering') }}">Profil Risiko</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="{{ route('clustering.detail', ['cluster' => 0]) }}">Cluster 0</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="{{ route('clustering.detail', ['cluster' => 1]) }}">Cluster 1</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="{{ route('clustering.detail', ['cluster' => 2]) }}">Cluster 2</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="{{ route('clustering.detail', ['cluster' => 3]) }}">Cluster 3</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ Route::is('screening') ? 'active' : '' }}">
                    <a href="{{ route('screening') }}" class='sidebar-link'>
                        <i class="bi bi-clipboard-check"></i>
                        <span>Skrining Stunting</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
