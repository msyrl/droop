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
    <title>{{ Config::get('app.name') }} | {{ __('Verify Notification') }}</title>

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
    <!-- Theme style -->
    <link
        rel="stylesheet"
        href="/css/alt/adminlte.light.min.css"
    >
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.min.js"></script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">{{ Config::get('app.name') }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <form
                    action="{{ url('/auth/verify-notification') }}"
                    method="POST"
                >
                    @csrf
                    <div class="input-group mb-3">
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror @if (Session::has('status')) is-valid @endif"
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
                        @if (Session::has('status'))
                            <div class="valid-feedback">{{ Session::get('status') }}</div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >{{ __('Resend') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ url('/auth/signin') }}">{{ __('Sign In') }}</a>
                </p>
                <p class="mb-0">
                    <a
                        href="{{ url('/auth/register') }}"
                        class="text-center"
                    >{{ __('Register a new membership') }}</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
</body>

</html>
