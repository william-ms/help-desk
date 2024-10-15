<ul class="pc-navbar">
    @foreach ($CategoriesAndMenusForSidebar as $Category)
        @if (!$Category->menus->isEmpty())
            <li class="pc-item pc-caption">
                <label>{{ $Category->name }}</label>
            </li>

            @foreach ($Category->menus as $Menu)
                @if ($Menu->route == 'dashboard')
                    <li class="pc-item pc-not-caption {{ request()->routeIs($Menu->route . '.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('admin.' . $Menu->route . '.index') ? route('admin.' . $Menu->route . '.index') : '' }}" class="pc-link {{ request()->routeIs($Menu->route . '.*') ? 'active' : '' }}">
                            <span class="pc-micon"><i class="{{ $Menu->icon }}"></i></span>
                            <span class="pc-mtext">{{ $Menu->name }}</span>
                        </a>
                    </li>
                @else
                    @can($Menu->route . '.index')
                        <li class="pc-item pc-not-caption {{ request()->routeIs($Menu->route . '.*') ? 'active' : '' }}">
                            <a href="{{ Route::has('admin.' . $Menu->route . '.index') ? route('admin.' . $Menu->route . '.index') : '' }}" class="pc-link {{ request()->routeIs($Menu->route . '.*') ? 'active' : '' }}">
                                <span class="pc-micon"><i class="{{ $Menu->icon }}"></i></span>
                                <span class="pc-mtext">{{ $Menu->name }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
            @endforeach
        @endif
    @endforeach
</ul>
