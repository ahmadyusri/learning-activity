<!-- Sidebar -->
<nav id="sidebar" class="d-print-none">
    <div class="sidebar-header">
        <h3>
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </h3>
    </div>

    <ul class="components">
        <li class="{{ Route::is('user.dashboard') ? 'active' : '' }}">
            <a href="{{ route('user.dashboard') }}">
                <i class="fa-solid fa-dashboard"></i>
                Dashboard
            </a>
        </li>
        <li class="treeview {{ Route::is('user.method.index') || Route::is('user.activity.index') ? 'active' : '' }}">
            <a href="#" data-bs-toggle="collapse" data-bs-target="#SMLA" aria-expanded="false"
                class="dropdown-toggle">
                <i class="fa-solid fa-boxes-stacked"></i>
                Learning Activity
            </a>
            <ul id="SMLA" class="collapse">
                <li class="{{ Route::is('user.method.index') ? 'active' : '' }}">
                    <a href="{{ route('user.method.index') }}">
                        <i class="fa-solid fa-hashtag"></i>
                        Method
                    </a>
                </li>
                <li class="{{ Route::is('user.activity.index') ? 'active' : '' }}">
                    <a href="{{ route('user.activity.index') }}">
                        <i class="fa-solid fa-hashtag"></i>
                        Activity
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
