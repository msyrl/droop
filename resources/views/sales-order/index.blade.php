<x-app>
    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row justify-content-between mb-2">
                <div class="col-auto">
                    <h1 class="m-0">{{ __('Sales order') }}</h1>
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <form
                                        action=""
                                        method="GET"
                                    >
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                            <input
                                                type="search"
                                                name="search"
                                                class="form-control"
                                                value="{{ Request::get('search') }}"
                                                placeholder="{{ __('Search sales orders') }}"
                                            />
                                        </div>
                                    </form>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button
                                            type="button"
                                            class="btn btn-default"
                                            data-toggle="dropdown"
                                        >
                                            <i class="fas fa-sort"></i>
                                            <span>{{ __('Sort') }}</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'name',
                                                    'direction' => 'asc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'name' && Request::get('direction') == 'asc' ? 'active' : '' }}"
                                            >{{ __('Name ascending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'name',
                                                    'direction' => 'desc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'name' && Request::get('direction') == 'desc' ? 'active' : '' }}"
                                            >{{ __('Name descending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'quantity',
                                                    'direction' => 'asc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'quantity' && Request::get('direction') == 'asc' ? 'active' : '' }}"
                                            >{{ __('Quantity ascending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'quantity',
                                                    'direction' => 'desc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'quantity' && Request::get('direction') == 'desc' ? 'active' : '' }}"
                                            >{{ __('Quantity descending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'total_price',
                                                    'direction' => 'asc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'total_price' && Request::get('direction') == 'asc' ? 'active' : '' }}"
                                            >{{ __('Total price ascending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'total_price',
                                                    'direction' => 'desc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'total_price' && Request::get('direction') == 'desc' ? 'active' : '' }}"
                                            >{{ __('Total price descending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'created_at',
                                                    'direction' => 'asc',
                                                ]) }}"
                                                class="dropdown-item {{ Request::get('sort') == 'created_at' && Request::get('direction') == 'asc' ? 'active' : '' }}"
                                            >{{ __('Date created ascending') }}</a>
                                            <a
                                                href="{{ Request::fullUrlWithQuery([
                                                    'sort' => 'created_at',
                                                    'direction' => 'desc',
                                                ]) }}"
                                                class="dropdown-item {{ (Request::get('sort') == 'created_at' && Request::get('direction') == 'desc') || (!Request::filled('sort') && !Request::filled('direction')) ? 'active' : '' }}"
                                            >{{ __('Date created descending') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Date created') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Payment Status') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Total price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salesOrders as $salesOrder)
                                        <tr>
                                            <td class="align-middle">
                                                <a
                                                    href="{{ url('/sales-orders/' . $salesOrder->id) }}">{{ $salesOrder->name }}</a>
                                            </td>
                                            <td class="align-middle">{{ $salesOrder->created_at }}</td>
                                            <td class="align-middle">{{ $salesOrder->formatted_status }}</td>
                                            <td class="align-middle">{{ $salesOrder->formatted_paid }}</td>
                                            <td class="align-middle">{{ $salesOrder->formatted_quantity }}</td>
                                            <td class="align-middle">{{ $salesOrder->formatted_total_price }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                colspan="6"
                                                class="text-center"
                                            >{{ __('Data not found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            {!! $salesOrders->withQueryString()->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
