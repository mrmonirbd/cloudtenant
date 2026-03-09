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
        @foreach(getmenu() as $menu)
            @if($menu->section == 'header')
                <li class="menu-header">{{ $menu->header_text }}</li>
            @else
                <li class="{{ $menu->children->count() > 0 ? 'has-submenu' : '' }}">
                    <a href="{{ $menu->route ? route($menu->route) : '#' }}">
                        @if($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endif
                        <span>{{ $menu->name }}</span>
                        @if($menu->children->count() > 0)
                            <span class="arrow"></span>
                        @endif
                    </a>
                    @if($menu->children->count() > 0)
                        <ul>
                            @foreach($menu->children as $child)
                                <li>
                                    <a href="{{ $child->route ? route($child->route) : '#' }}">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }}"></i>
                                        @endif
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>