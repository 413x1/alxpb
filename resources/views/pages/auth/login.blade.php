<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanaPhoto</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&amp;display=swap" rel="stylesheet">

    <!-- App css -->
    <link rel="stylesheet" href="{{ asset('assets/theme/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/theme/css/color-1.css') }}" media="screen">
</head>
<body>
<!-- tap on top starts-->
<div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>
<!-- tap on tap ends-->
<!-- loader-->
<div class="loader-wrapper">
    <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
</div>
<!-- login page start-->
<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-12 p-0">
            <div class="login-card login-dark">
                <div>
                    <div class="login-main">
                        <form class="theme-form" action="{{ route('login.auth') }}" method="POST">
{{--                            <h2 class="text-center mb-5">Sign in to dashboard</h2>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-form-label">Email Address</label>--}}
{{--                                <input class="form-control" type="email" name="email" required="" placeholder="example@mail.com">--}}
{{--                            </div>--}}
                            @csrf
                            <h2 class="text-center mb-5">Sign in to dashboard</h2>
                            <div class="form-group">
                                <label class="col-form-label">Username</label>
                                <input class="form-control" type="text" name="username" required="" placeholder="username">
                            </div>

                            <div class="form-group">
                                <label class="col-form-label">Password</label>
                                <div class="form-input position-relative">
                                    <input class="form-control" type="password" name="password" required="" placeholder="*********">
                                    <div class="show-hide"><span class="show"></span></div>
                                </div>
                            </div>
                            <div class="form-group mb-0 checkbox-checked">
                                <div class="form-check checkbox-solid-info">
                                    <input class="form-check-input" id="solid6" type="checkbox" name="remember">
                                    <label class="form-check-label" for="solid6">Remember me!</label>
                                </div><a class="link" href="">Forgot password?</a>
                                <div class="text-end mt-3">
                                    <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jquery-->
    <script src="{{ asset('assets/theme/js/vendors/jquery/jquery.min.js') }}"></script>
    <!-- bootstrap js-->
    <script src="{{ asset('assets/theme/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/theme/js/vendors/bootstrap/dist/js/popper.min.js') }}" defer=""></script>
    <!--fontawesome-->
    <script src="{{ asset('assets/theme/js/vendors/font-awesome/fontawesome-min.js') }}"></script>
    <!-- password_show-->
    <script src="{{ asset('assets/theme/js/password.js') }}"></script>
    <!-- custom script -->
    <script src="{{ asset('assets/theme/js/script.js') }}"></script>
</div>
</body>
</html>
