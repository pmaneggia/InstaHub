<!-- https://stackoverflow.com/questions/33867603/center-an-element-in-bootstrap-4-navbar -->
<nav class="navbar navbar-expand-sm navbar-light bg-light main-nav sticky-top" style="background-color: #fff;">
    <div class="container justify-content-center">
        <!-- Left Side Of Navbar -->
        <div class="nav navbar-nav flex-fill w-md-100 flex-nowrap">
            <!-- Branding Image -->
            
            @if (Auth::guest())
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/clarity/camera-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">
                <div>{{Session::get('hub')}}</div>
            </a>
            @else
            <a class="navbar-brand" href="{{ url('/home') }}">
                <img src="/clarity/camera-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">
                <div>{{Session::get('hub')}}</div>
            </a>
            @endif
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        @if (!Auth::guest())
        <div class="nav navbar-nav flex-fill justify-content-center d-none d-sm-block">
        <form class="form-inline">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        </form>
        </div>
        @endif
        <!-- Right Side Of Navbar -->
        <ul class="nav navbar-nav flex-fill w-100 justify-content-end collapse navbar-collapse"  id="navbarSupportedContent">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Register</a></li>
            @else
                <form class="form-inline d-inline d-sm-none">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>

                @if (Schema::hasTable('ads'))
                <li class="nav-item"><a class="nav-link"href="{{ url('/business') }}"><img src="/clarity/store-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">&nbsp<span class="d-inline d-sm-none">Busines</span></a></li>
                @endif
                <li class="nav-item"><a class="nav-link"href="{{ url('/user') }}"><img src="/clarity/compass-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">&nbsp<span class="d-inline d-sm-none">Explore</span></a></li>    
                @if ((Auth::user()->allowed('dba') && Session::get('hub', 'root') != 'root') || Auth::user()->allowed('admin'))
                <li class="nav-item"><a class="nav-link"href="{{ url('/sql') }}"><img src="/clarity/storage-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">&nbsp<span class="d-inline d-sm-none">Database</span></a></li>
                @endif
                @if (!Auth::guest() && Session::get('hub', 'root') == 'root' && Auth::user()->allowed('teacher'))
                <li class="nav-item"><a class="nav-link"href="{{ env('DOC_URL') }}"><img src="/clarity/help-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">&nbsp<span class="d-inline d-sm-none">Help</span></a></li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="/clarity/user-line.svg" width="30" height="30" class="d-inline-block align-middle" alt="">&nbsp<span class="d-inline d-sm-none">User</span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/user/{{ Auth::user()->username }}">{{ Auth::user()->name }}</a>
                        @if (Schema::hasTable('photos') && !Session::get('readonly'))
                            <a class="dropdown-item" href="{{ url('/upload') }}">Upload</a>
                        @endif
                        <div>
                            <a class="dropdown-item" href="{{ url('/logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div> 
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>
