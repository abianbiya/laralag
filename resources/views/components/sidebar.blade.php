<div>
    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="index" class="logo logo-dark">
                <span class="logo-sm" style="font-size: 18px; font-weight: bold; text-transform: uppercase">
                    <span style="color: var(--tb-heading-color);">{{ config('laralag.app_short_name', substr(config('app.name'), 0, 2)) }}</span>
                </span>
                <span class="logo-lg" style="font-size: 22px; font-weight: bold; text-transform: uppercase">
                    <span style="color: var(--tb-heading-color);">{{ config('laralag.app_name') }}</span>.
                </span>
            </a>
            <a href="{{ route(config('laralag.home_route')) ?? url('/') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="26">
                </span>
                <span class="logo-lg">
                    <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="26">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title"><span data-key="t-menu">@lang('translation.menu')</span></li>
                    <li class="nav-item">
                        <a href="{{ route(config('laralag.home_route')) }}" class="nav-link menu-link"> <i class="bi bi-speedometer2"></i> <span data-key="t-dashboard">Dashboard</span> </a>
                    </li>

                    @foreach ($menus as $item)
                        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">{{ $item->nama }}</span></li>
                        @foreach ($item->menu as $menu)
                            @if(count($menu->module->where('is_tampil', 1)) > 0)
                                @if (count($menu->module->where('is_tampil', 1)) == 1)
                                    @php $module = $menu->module[0]; @endphp
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="{{ route($module->routing) }}">
                                            <i class="{{ $menu->icon }}"></i> <span data-key="t-widgets">{{ $menu->nama }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="#{{ str($menu->nama)->slug() }}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="{{ str($menu->nama)->slug() }}">
                                            <i class="{{ $menu->icon }}"></i> <span data-key="t-pages">{{ $menu->nama }}</span>
                                        </a>
                                        <div class="collapse menu-dropdown" id="{{ str($menu->nama)->slug() }}">
                                            <ul class="nav nav-sm flex-column">
                                                @foreach ($menu->module->where('is_tampil', 1) as $module)
                                                    <li class="nav-item">
                                                        <a href="{{ route($module->routing) }}" class="nav-link" data-key="t-starter"> {{ $module->nama }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>
</div>
