<nav class="sidebar">
    <div class="logo d-flex justify-content-between">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
        </a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    
    <ul id="sidebar_menu">
        @php
            use App\Helpers\MenuHelper;
            $currentRoute = request()->route()?->getName();
            $userMenus = MenuHelper::getUserMenus(auth()->id());
        @endphp

        {{-- Dynamic Menus based on user permissions --}}
        @if($userMenus->count() > 0)
            {!! MenuHelper::buildMenu($userMenus, $currentRoute) !!}
        @else
            <li class="text-center text-muted py-3">No menus available</li>
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