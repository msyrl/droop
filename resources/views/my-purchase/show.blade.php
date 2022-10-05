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
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Statuses') }}</h3>
                        </div>
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
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <dl>
                                <dt>{{ __('Name') }}</dt>
                                <dd>{{ $purchase->name }}</dd>
                                <dt>{{ __('User') }}</dt>
                                <dd>
                                    <div>{{ $purchase->user->name }}</div>
                                    <div>{{ $purchase->user->email }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <x-card-attachments :attachments="$purchase->attachments" />
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
