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
            $currentRoute = request()->route() ? request()->route()->getName() : '';
            $role = auth()->check() ? auth()->user()->role : 'guest';
            $menus = getMenusByRole($role);
        @endphp

        {{-- Dashboard --}}
        <li class="{{ $currentRoute === 'dashboard' ? 'mm-active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/menu-icon/dashboard.svg') }}" alt="Dashboard">
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Headers from menus --}}
        @foreach($menus->where('section', 'header') as $header)
            <li class="menu-header">{{ $header->header_text }}</li>
        @endforeach

        {{-- Main Menus --}}
        @php
            $mainMenus = $menus->where('section', 'main');
        @endphp
        
        @if($mainMenus->count() > 0)
            {!! buildMenu($mainMenus, $currentRoute) !!}
        @endif

        {{-- Logout --}}
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