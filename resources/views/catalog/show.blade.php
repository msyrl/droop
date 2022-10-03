<x-app>
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
                        href="{{ url('/catalogs') }}"
                        class="btn btn-default"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-auto">
                    <h1 class="m-0">{{ $product->name }}</h1>
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
                            <div class="row">
                                <div
                                    class="col-12 col-sm-6 mb-3"
                                    id="product-image-module"
                                >
                                    <img
                                        src="{{ $product->featured_image_url }}"
                                        alt="{{ $product->name }}"
                                        class="product-image"
                                    />
                                    <div class="product-image-thumbs">
                                        <div class="product-image-thumb active">
                                            <img
                                                src="{{ $product->featured_image_url }}"
                                                alt="{{ $product->name }}"
                                            />
                                        </div>
                                        @foreach ($product->images as $image)
                                            <div class="product-image-thumb">
                                                <img
                                                    src="{{ $image->getUrl() }}"
                                                    alt="{{ $product->name }}"
                                                />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <script>
                                    var ProductImage = (function() {
                                        var $el = $('#product-image-module');
                                        var $productImage = $el.find('.product-image');
                                        var $productImageThumb = $el.find('.product-image-thumb');

                                        $productImageThumb.on('click', function() {
                                            var $this = $(this);
                                            var $currentImage = $this.find('img');

                                            $productImage.attr('src', $currentImage.attr('src'));
                                            $productImageThumb.removeClass('active');
                                            $this.addClass('active');
                                        });
                                    })();
                                </script>
                                <div class="col-12 col-sm-6 mb-3">
                                    <h3 c;ass="mb-3">{{ $product->name }}</h3>
                                    <p>{!! $product->description !!}</p>
                                    <p class="h4 mb-3 text-bold">{{ $product->formatted_price }}</p>
                                    <form
                                        action="{{ url('/cart/products') }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="{{ $product->id }}"
                                        />
                                        <div
                                            class="input-group mb-3"
                                            id="quantity-module"
                                            style="width: 170px;"
                                        >
                                            <div class="input-group-prepend">
                                                <button
                                                    type="button"
                                                    class="btn btn-default"
                                                    id="quantity-increment"
                                                >
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <input
                                                type="number"
                                                name="quantity"
                                                id="quantity"
                                                value="1"
                                                class="form-control text-center"
                                                min="1"
                                            />
                                            <div class="input-group-append">
                                                <button
                                                    type="button"
                                                    class="btn btn-default"
                                                    id="quantity-decrement"
                                                >
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <script>
                                            var Quantity = (function() {
                                                var $el = $('#quantity-module');
                                                var $increment = $el.find('#quantity-increment');
                                                var $decrement = $el.find("#quantity-decrement");
                                                var $quantity = $el.find('#quantity');

                                                var quantity = 1;

                                                $increment.on('click', function() {
                                                    incrementQuantity();

                                                    $quantity.val(quantity);
                                                });

                                                $decrement.on('click', function() {
                                                    decrementQuantity();

                                                    $quantity.val(quantity);
                                                });

                                                $quantity.on('change', function() {
                                                    if ($(this).val() > 0) {
                                                        return;
                                                    }

                                                    quantity = 1;

                                                    $quantity.val(quantity);
                                                });

                                                function incrementQuantity() {
                                                    quantity++;
                                                }

                                                function decrementQuantity() {
                                                    if (quantity > 1) {
                                                        quantity--;
                                                    }
                                                }
                                            })();
                                        </script>
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >{{ __('Add to cart') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
