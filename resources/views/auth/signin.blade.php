<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="/apple-touch-icon.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="/favicon-32x32.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="/favicon-16x16.png"
    >
    <link
        rel="manifest"
        href="/site.webmanifest"
    >
    <title>{{ Config::get('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    >
    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="/plugins/fontawesome-free/css/all.min.css"
    >
    <!-- icheck bootstrap -->
    <link
        rel="stylesheet"
        href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css"
    >
    <!-- toastr -->
    <link
        rel="stylesheet"
        href="/plugins/toastr/toastr.min.css"
    >
    <!-- Theme style -->
    <link
        rel="stylesheet"
        href="/css/alt/adminlte.light.min.css"
    >
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- toastr -->
    <script src="/plugins/toastr/toastr.min.js"></script>
    <script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.options.positionClass = 'toast-top-center';
        toastr.options.newestOnTop = true;
    </script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.min.js"></script>
</head>

<body class="hold-transition login-page">
    @if (Session::has('status'))
        <script>
            toastr.success('{{ Session::get('status') }}');
        </script>
    @endif
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">{{ Config::get('app.name') }}</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('Sign in to start your session') }}</p>

                <form
                    action="{{ url('/auth/signin') }}"
                    method="POST"
                    novalidate
                >
                    @csrf
                    <div class="input-group mb-3">
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="{{ __('Email') }}"
                            value="{{ Request::old('email') }}"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('Password') }}"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    value="1"
                                >
                                <label for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >{{ __('Sign In') }}</button>
                        </div>
                    </div>
                </form>

                <br />

                <p class="mb-1">
                    <a href="{{ url('/forgot-password') }}">{{ __('I forgot my password') }}</a>
                </p>
                <p class="mb-1">
                    <a href="{{ url('/auth/verify-notification') }}">{{ __('Resend verify notification') }}</a>
                </p>
                <p class="mb-0">
                    <a href="{{ url('/auth/register') }}">{{ __('Register a new membership') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
