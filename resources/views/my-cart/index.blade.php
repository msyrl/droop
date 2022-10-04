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
                    <h1 class="m-0">{{ __('Cart') }}</h1>
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
                    @if ($cart)
                        <div class="card">
                            <div class="card-body">
                                @foreach ($cart->lineItems as $lineItem)
                                    <x-line-item
                                        :lineItem="$lineItem"
                                        :first="$loop->first"
                                        :last="$loop->last"
                                    />
                                @endforeach
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col">{{ __('Quantity') }}</div>
                                    <div class="col-auto ml-auto">{{ $cart->formatted_quantity }}</div>
                                </div>
                                <div class="row text-bold">
                                    <div class="col">{{ __('Total') }}</div>
                                    <div class="col-auto ml-auto">{{ $cart->formatted_total_price }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <h3>{{ __('Your cart is empty') }}</h3>
                            <p>{!! __('Go to :link page', [
                                'link' => '<a href="' . url('/catalogs') . '">catalog</a>',
                            ]) !!}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
