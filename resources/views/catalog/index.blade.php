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
                    <h1 class="m-0">{{ __('Catalogs') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
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
                                        placeholder="{{ __('Search products') }}"
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
                                            'sort' => 'price',
                                            'direction' => 'asc',
                                        ]) }}"
                                        class="dropdown-item {{ Request::get('sort') == 'price' && Request::get('direction') == 'asc' ? 'active' : '' }}"
                                    >{{ __('Price ascending') }}</a>
                                    <a
                                        href="{{ Request::fullUrlWithQuery([
                                            'sort' => 'price',
                                            'direction' => 'desc',
                                        ]) }}"
                                        class="dropdown-item {{ Request::get('sort') == 'price' && Request::get('direction') == 'desc' ? 'active' : '' }}"
                                    >{{ __('Price descending') }}</a>
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
                <div class="col-12">
                    <div class="row justify-content-center align-items-stretch -mx-2">
                        @forelse ($products as $product)
                            <div class="col-6 col-md-4 col-lg-3 p-2">
                                <div class="card mb-0">
                                    <form
                                        action="{{ url('/my/cart') }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="{{ $product->id }}"
                                        >
                                        <a href="{{ url('/catalogs/' . $product->id) }}">
                                            <img
                                                src="{{ $product->featured_image_url }}"
                                                alt="{{ $product->name }}"
                                                class="mx-auto"
                                                style="object-position: center; object-fit: cover; width: 100%; height: 200px;"
                                            />
                                        </a>
                                        <div class="card-body h-100 p-2">
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <p class="card-text text-bold">{{ $product->formatted_price }}</p>
                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >{{ __('Add to cart') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <h2 class="text-center">{{ __('Data not found') }}</h2>
                        @endforelse
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {!! $products->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
