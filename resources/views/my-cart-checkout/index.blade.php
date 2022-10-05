<x-app>
    @if ($errors->any())
        <script>
            toastr.error('{{ $errors->first() }}');
        </script>
    @endif
    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-auto">
                    <a
                        href="{{ url('/my/cart') }}"
                        class="btn btn-default"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-auto">
                    <h1 class="m-0">{{ __('Checkout') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">{{ __('Quantity') }}</div>
                                <div class="col-auto ml-auto">{{ $cart->formatted_quantity }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">{{ __('Line items') }}</div>
                                <div class="col-auto ml-auto">{{ $cart->formatted_line_items_price }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">{{ __('Additional charges') }}</div>
                                <div class="col-auto ml-auto">{{ $cart->formatted_total_additional_charges_price }}</div>
                            </div>
                            <div class="row text-bold">
                                <div class="col">{{ __('Total') }}</div>
                                <div class="col-auto ml-auto">{{ $cart->formatted_total_price }}</div>
                            </div>
                        </div>
                    </div>
                    <form
                        action="{{ url('/my/cart/checkout') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <span>{{ __('Attachments') }}</span>
                                    <span class="text-danger">*</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <input
                                    type="file"
                                    name="attachments[]"
                                    class="form-control-file"
                                    accept="application/pdf"
                                    multiple
                                />
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >{{ __('Proceed') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
