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
                        href="{{ url('/sales-orders') }}"
                        class="btn btn-default"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-auto">
                    <h1 class="m-0">{{ $purchase->name }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            @foreach ($purchase->lineItems as $lineItem)
                                <div
                                    class="row align-items-start @if (!$loop->first) border-top pt-2 @else pt-0 @endif @if (!$loop->last) pb-2 @endif">
                                    <div class="col-auto">
                                        <img
                                            src="{{ $lineItem->product->featured_image_url }}"
                                            alt="{{ $lineItem->name }}"
                                            width="40"
                                        />
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-6 col-sm-8">
                                                <div class="row">
                                                    <div class="col-12 col-sm-8">
                                                        <a
                                                            href="{{ url('/catalogs/' . $lineItem->product_id) }}">{{ $lineItem->name }}</a>
                                                        @if ($lineItem->sku)
                                                            <div>{{ __('SKU') }}: {{ $lineItem->sku }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-12 col-sm text-sm-right">
                                                        {{ $lineItem->formatted_price }} *
                                                        {{ $lineItem->formatted_quantity }}</div>
                                                </div>
                                            </div>
                                            <div class="col text-right">{{ $lineItem->formatted_total_price }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">{{ __('Quantity') }}</div>
                                <div class="col-auto ml-auto">{{ $purchase->formatted_quantity }}</div>
                            </div>
                            <div class="row text-bold">
                                <div class="col">{{ __('Total') }}</div>
                                <div class="col-auto ml-auto">{{ $purchase->formatted_total_price }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <dl>
                                <dt>{{ __('Status') }}</dt>
                                <dd>{{ $purchase->formatted_status }}</dd>
                                <dt>{{ __('Payment status') }}</dt>
                                <dd>{{ $purchase->formatted_paid }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <dl>
                                <dt>{{ __('Name') }}</dt>
                                <dd>{{ $purchase->name }}</dd>
                                <dt>{{ __('User') }}</dt>
                                <dd>
                                    <div>{{ $purchase->user->name }}</div>
                                    <div>{{ $purchase->user->email }}</div>
                                </dd>
                                <dt>{{ __('Has attachment') }}</dt>
                                <dd>
                                    <div>{{ $purchase->formatted_has_attachment }}</div>
                                    @if ($purchase->hasAttachment())
                                        <a
                                            href="#"
                                            target="_blank"
                                        >{{ __('Show attachment') }}</a>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
