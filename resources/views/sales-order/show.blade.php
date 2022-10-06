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
                <div class="col-auto ml-auto">
                    <a
                        href="{{ url('/sales-orders/' . $salesOrder->id . '/invoice') }}"
                        target="_blank"
                        class="btn btn-default"
                    >{{ __('Print as invoice') }}</a>
                    <button
                        form="update-sales-order"
                        type="submit"
                        class="btn btn-primary"
                    >{{ __('Save') }}</button>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <form
            id="update-sales-order"
            action="{{ url('/sales-orders/' . $salesOrder->id) }}"
            method="POST"
            novalidate
        >
            @csrf
            @method('PUT')
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                @foreach ($salesOrder->lineItems as $lineItem)
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
                                    <div class="col-auto ml-auto">{{ $salesOrder->formatted_quantity }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col">{{ __('Line items') }}</div>
                                    <div class="col-auto ml-auto">{{ $salesOrder->formatted_total_line_items_price }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col">{{ __('Additional charges') }}</div>
                                    <div class="col-auto ml-auto">
                                        <input
                                            type="number"
                                            name="total_additional_charges_price"
                                            class="form-control text-right"
                                            min="0"
                                            value="{{ $salesOrder->total_additional_charges_price }}"
                                        />
                                    </div>
                                </div>
                                <div class="row text-bold">
                                    <div class="col">{{ __('Total') }}</div>
                                    <div class="col-auto ml-auto">{{ $salesOrder->formatted_total_price }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
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
                            </div>
                        </div>
                        <x-card-sales-order-information :salesOrder="$salesOrder" />
                        <x-card-sales-order-attachments :attachments="$salesOrder->attachments" />
                    </div>
                </div>
            </div>
        </form>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
