<!-- Page sidebar start-->
<aside class="page-sidebar">
    <div class="main-sidebar" id="main-sidebar">
        <ul class="sidebar-menu" id="simple-bar">
            <li class="sidebar-list {{ isActiveRoute('dashboard.index') }}">
                <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <h6 class="f-w-600">Dashboards</h6>
                </a>
            </li>
            <li class="sidebar-list {{ isActiveRoute('dashboard.devices.*') }}">
                <a class="sidebar-link" href="{{ route('dashboard.devices.index') }}">
                    <i class="fa-solid fa-desktop"></i>
                    <h6 class="f-w-600">Devices</h6>
                </a>
            </li>
            <li class="sidebar-list {{ isActiveRoute('dashboard.banners.*') }}">
                <a class="sidebar-link" href="{{ route('dashboard.banners.index') }}">
                    <i class="fa-solid fa-image"></i>
                    <h6 class="f-w-600">Banners</h6>
                </a>
            </li>
            <li class="sidebar-list {{ isActiveRoute('dashboard.vouchers.*') }}">
                <a class="sidebar-link" href="{{ route('dashboard.vouchers.index') }}">
                    <i class="fa-solid fa-tags"></i>
                    <h6 class="f-w-600">Vouchers</h6>
                </a>
            </li>
            <li class="sidebar-list {{ isActiveRoute('dashboard.products.*') }}">
                <a class="sidebar-link" href="{{ route('dashboard.products.index') }}">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <h6 class="f-w-600">Products</h6>
                </a>
            </li>

            <li class="sidebar-list {{ isActiveRoute('dashboard.users.*') }}">
                <a class="sidebar-link" href="{{ route('dashboard.users.index') }}">
                    <i class="fa-solid fa-users"></i>
                    <h6 class="f-w-600">Users</h6>
                </a>
            </li>

            <li class="sidebar-list">
                <a class="sidebar-link" href="{{ route('dashboard.products.index') }}">
                    <i class="fa-solid fa-gear"></i>
                    <h6 class="f-w-600">Settings</h6>
                </a>
            </li>

{{--            <li class="sidebar-list"> <i class="fa-solid fa-thumbtack"></i><a class="sidebar-link" href="javascript:void(0)">--}}
{{--                    <svg class="stroke-icon">--}}
{{--                        <use href="../assets/svg/iconly-sprite.svg#Star-kit"></use>--}}
{{--                    </svg>--}}
{{--                    <h6 class="f-w-600">Starter kit</h6><i class="iconly-Arrow-Right-2 icli"> </i></a>--}}
{{--                <ul class="sidebar-submenu">--}}
{{--                    <li><a class="submenu-title" href="javascript:void(0)">color version<i class="iconly-Arrow-Right-2 icli"> </i></a>--}}
{{--                        <ul class="according-submenu">--}}
{{--                            <li> <a href="index.html">Layout Light</a></li>--}}
{{--                            <li> <a href="layout-dark.html">Layout Dark</a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li><a class="submenu-title" href="javascript:void(0)">Page layout<i class="iconly-Arrow-Right-2 icli"> </i></a>--}}
{{--                        <ul class="according-submenu">--}}
{{--                            <li> <a href="boxed.html">Boxed</a></li>--}}
{{--                            <li> <a href="layout-rtl.html">Rtl</a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
        </ul>
    </div>
</aside>
<!-- Page sidebar end-->
