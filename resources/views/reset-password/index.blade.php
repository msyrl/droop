<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title>AdminLTE 3 | Recover Password</title>

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
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <form
                    action="{{ route('password.update') }}"
                    method="POST"
                >
                    @csrf
                    <input
                        type="hidden"
                        name="token"
                        value="{{ $request->route('token') }}"
                    />
                    <input
                        type="hidden"
                        name="email"
                        value="{{ Request::old('email', $request->get('email')) }}"
                    />
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
                    <div class="input-group mb-3">
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('Confirm Password') }}"
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
                        <div class="col-12">
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >{{ __('Change password') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ url('/auth/signin') }}">{{ __('Sign In') }}</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
</body>

</html>
