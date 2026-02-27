<nav class="sidebar">
    <div class="logo d-flex justify-content-between">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="{{ config('app.name') }}">
        </a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    
    <ul id="sidebar_menu">
        @php
            $currentRoute = Route::currentRouteName();
        @endphp

        {{-- Dashboard Menu (Static) --}}
        {{-- <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/menu-icon/dashboard.svg') }}" alt="Dashboard">
                <span>Dashboard</span>
            </a>
        </li> --}}

        {{-- Display Headers if any --}}
        {{-- @if(isset($menuHeaders) && $menuHeaders->count() > 0)
            @foreach($menuHeaders as $header)
                <li class="menu-header">{{ $header->header_text }}</li>
            @endforeach
        @endif --}}

        {{-- Dynamic Menus from Database --}}
        @if(isset($dynamicMenus) && $dynamicMenus->count() > 0)
            @foreach($dynamicMenus as $menu)
                @if($menu->children->count() > 0)
                    {{-- Parent Menu with Submenus --}}
                    <li class="mm-dropdown {{ $menu->children->contains(function($child) use ($currentRoute) {
                        return $currentRoute === $child->route;
                    }) ? 'mm-active' : '' }}">
                        <a class="has-arrow" href="#" aria-expanded="false">
                            @if($menu->icon)
                                <i class="bi {{ $menu->icon }}" style="font-size: 26px;"></i>
                             
                            @endif
                            <span>{{ $menu->name }}</span>
                        </a>
                        <ul class="mm-collapse">
                            @foreach($menu->children as $child)
                                <li class="{{ $currentRoute === $child->route ? 'active' : '' }}">
                                    <a href="{{ $child->getUrlAttribute() }}" 
                                       class="{{ $currentRoute === $child->route ? 'active' : '' }}">
                                        @if($child->icon)
                                            @if(strpos($child->icon, 'bi-') === 0)
                                                <i class="bi {{ $child->icon }}"></i>
                                            @endif
                                        @endif
                                        {{ $child->name }} 
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    {{-- Single Menu Item --}}
                    <li class="{{ $currentRoute === $menu->route ? 'mm-active' : '' }}">
                        <a href="{{ $menu->getUrlAttribute() }}">
                            @if($menu->icon)
 
                                    <i class="bi {{ $menu->icon }}"></i>
                            @endif
                            <span>{{ $menu->name }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        @else
            {{-- Fallback Menus if database is empty --}}
            {{-- @if(auth()->user() && auth()->user()->role === 'admin')
            <li class="{{ request()->routeIs('users.index') ? 'mm-active' : '' }}">
                <a href="{{ route('users.index') }}">
                    <i class="bi bi-people" style="font-size: 26px;"></i>
                    <span>Users</span>
                </a>
            </li>
            @endif --}}
        @endif

        {{-- Logout Menu --}}
        <li class="logout">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right" style="font-size: 26px;"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>