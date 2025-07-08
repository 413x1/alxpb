<!-- Page header start -->
<header class="page-header row">
    <div class="logo-wrapper d-flex align-items-center col-auto">
        <a href="{{ route('dashboard.index') }}">
            {{--                <img class="light-logo img-fluid" src="../assets/images/logo/logo1.png" alt="logo" />--}}
            {{--                <img class="dark-logo img-fluid" src="../assets/images/logo/logo-dark.png" alt="logo" />--}}
        </a>
        <a class="close-btn toggle-sidebar" href="javascript:void(0)">
            <i class="fa-solid fa-bars"></i>
        </a>
    </div>
    <div class="page-main-header col">
        <div class="header-left">
        </div>
        <div class="nav-right">
            <ul class="header-right">
                <li>
                    <a class="full-screen" href="javascript:void(0)">
                        <i class="fa-solid fa-arrows-up-down-left-right"></i>
                    </a>
                </li>
                <li>
                    <form action="{{ route('login.logout') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-pill btn-light text-dark exit-btn" href="javascript:void(0)">
                            <i class="fa-solid fa-power-off"></i>
                        </button>

                    </form>
                </li>
                <li class="profile-nav">
                    <div class="user-wrap">
                        <div class="user-img"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="user-content">
                            <h6>{{ auth()->user()->name }}</h6>
                            <p class="mb-0">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- Page header end-->
