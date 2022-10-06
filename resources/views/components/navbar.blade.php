<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a
                class="nav-link"
                data-widget="pushmenu"
                href="#"
                role="button"
            ><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a
                class="nav-link"
                data-toggle="dropdown"
                href="#"
            >
                <img
                    src="{{ \App\Helpers\GravatarHelper::generateUrl(Request::user()->name) }}"
                    class="img-circle"
                    alt="User"
                    height="100%"
                />
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item">{{ Auth::user()->name }}</span>
                <div class="dropdown-divider"></div>
                <a
                    href="{{ url('/profile') }}"
                    class="dropdown-item"
                >{{ __('Your profile') }}</a>
                <a
                    href="{{ url('/profile/password') }}"
                    class="dropdown-item"
                >{{ __('Change Password') }}</a>
                <div class="dropdown-divider"></div>
                <a
                    href="#"
                    class="dropdown-item"
                    onclick="document.getElementById('signout').click()"
                >{{ __('Sign out') }}</a>
                <form
                    action="{{ url('/auth/signout') }}"
                    method="POST"
                    style="display: none;"
                >
                    @csrf
                    <button
                        type="submit"
                        id="signout"
                    ></button>
                </form>
            </div>
        </li>
    </ul>
</nav>
