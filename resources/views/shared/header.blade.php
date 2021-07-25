<!DOCTYPE html>
<html>

<head>
    <title>Custom Auth in Laravel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">

    <script src="{{ url('/assets/js/jQuery/jquery-3.6.0.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        nav.navbar.mainNavBar,
        nav.navbar.mainNavBar .nav-link,
        nav.navbar.mainNavBar .navbar-brand {
            background-color: #e3f2fd;
            transition: background 500ms
        }

        body {
            background: white;
            transition: background 500ms
        }

        body.darkmode-on {
            background: #1a1a2e;
        }

        .darkmode-on nav.navbar.mainNavBar,
        .darkmode-on nav.navbar.mainNavBar .nav-link,
        .darkmode-on nav.navbar.mainNavBar .navbar-brand {
            background: #16213e;
            color: #e3f2fd;
        }

        .dark-mode-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: auto 20px
        }

        .dark-mode-container>input.form-check-input {
            height: 20px;
            width: 40px;
        }

        .dark-mode-label {
            font-size: 8px;
            font-weight: bold;
            margin-left: -40px
        }

    </style>
    <script>
        function changeTheme(el) {
            if (el.checked) {
                document.getElementsByTagName('body')[0].classList.add(
                    "darkmode-on");
            } else {
                document.getElementsByTagName('body')[0].classList.remove(
                    "darkmode-on");
            }
        }
    </script>
</head>

<body class="
    @if (Auth::user() && Auth::user()->preferences != '[]' &&
    json_decode(Auth::user()->preferences)->darkmode === 'on') darkmode-on @endif
    ">

    <nav class="navbar navbar-light navbar-expand-lg mb-5 mainNavBar" style="">
        <div class="container">
            <a class="navbar-brand mr-auto" href="{{ route('web.home') }}">Generic laravel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between d-flex " id="navbarNav">
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('web.admin.index') }}">Admin</a>
                        </li>
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('web.admin.settings') }}">Settings</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('web.logout') }}" method="POST">
                                @csrf
                                <a class="nav-link" href="#" onclick="this.parentElement.submit()">Logout</a>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('web.login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('web.register') }}">Register</a>
                        </li>
                    @endguest
                </ul>
            </div>


            {{-- <div class="dark-mode-container form-check form-switch">
                <input class="form-check-input" type="checkbox" onclick="changeTheme(this)" id="flexSwitchCheckDefault"
                    name="settings[darkmode]" checked="">
                <label class="form-check-label dark-mode-label" for="flexSwitchCheckDefault">Dark
                    mode</label>
            </div> --}}

        </div>
    </nav>

    @yield('content')

</body>

</html>
