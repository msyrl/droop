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
                    <h1 class="m-0">{{ $salesOrder->name }}</h1>
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
                            @foreach ($salesOrder->lineItems as $lineItem)
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
                                                        <a href="#">{{ $lineItem->name }}</a>
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
                                <div class="col-auto ml-auto">{{ $salesOrder->formatted_quantity }}</div>
                            </div>
                            <div class="row text-bold">
                                <div class="col">{{ __('Total') }}</div>
                                <div class="col-auto ml-auto">{{ $salesOrder->formatted_total_price }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <form
                        action="{{ url('/sales-orders/' . $salesOrder->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        novalidate
                    >
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">
                                        <span>{{ __('Status') }}</span>
                                    </label>
                                    <select
                                        name="status"
                                        id="status"
                                        class="form-control"
                                    >
                                        @foreach (\App\Enums\SalesOrderStatusEnum::toValues() as $status)
                                            <option
                                                value="{{ $status }}"
                                                @if ($salesOrder->status == $status) selected @endif
                                            >{{ strtoupper($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="paid">
                                        <span>{{ __('Payment status') }}</span>
                                    </label>
                                    <select
                                        name="paid"
                                        id="paid"
                                        class="form-control"
                                    >
                                        <option
                                            value="0"
                                            @if (!$salesOrder->paid) selected @endif
                                        >{{ __('UNPAID') }}</option>
                                        <option
                                            value="1"
                                            @if ($salesOrder->paid) selected @endif
                                        >{{ __('PAID') }}</option>
                                    </select>
                                </div>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-body">
                            <dl>
                                <dt>{{ __('Name') }}</dt>
                                <dd>{{ $salesOrder->name }}</dd>
                                <dt>{{ __('User') }}</dt>
                                <dd>
                                    <div>{{ $salesOrder->user->name }}</div>
                                    <div>{{ $salesOrder->user->email }}</div>
                                </dd>
                                <dt>{{ __('Has attachment') }}</dt>
                                <dd>
                                    <div>{{ $salesOrder->formatted_has_attachment }}</div>
                                    @if ($salesOrder->hasAttachment())
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
