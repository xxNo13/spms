<header class="position-fixed shadow-sm w-100 bg-body" id="header" style="z-index: 1;">
    <nav class="navbar navbar-expand navbar-light">
        <div class="container-fluid">
            <a class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <div class="collapse navbar-collapse position-fixed end-0 mx-4" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown me-3 ">
                        <livewire:notification-livewire />
                    </li>
                </ul>
                <div class="dropdown">
                    <a href="" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex align-items-center">
                            <div class="user-name text-end me-3">
                                <h6 class="my-auto text-gray-600">{{ Auth::user()->name }}</h6>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ asset('/images/faces/1.jpg') }}">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li>
                            <h6 class="dropdown-header">Hello, {{ strtok(Auth::user()->name, ' ') }}!</h6>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i
                                    class="icon-mid bi bi-person me-2"></i> My
                                Profile</a></li>
                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <li>
                                <a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                    {{ __('API Tokens') }}
                                </a>
                            </li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
