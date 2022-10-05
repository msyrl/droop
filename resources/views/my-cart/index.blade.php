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
                                                <div class="col-8">
                                                    <form
                                                        action="{{ url('/my/cart') }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('PUT')
                                                        <input
                                                            type="hidden"
                                                            name="product_id"
                                                            value="{{ $lineItem->product_id }}"
                                                        />
                                                        <div class="row">
                                                            <div class="col-12 col-sm">
                                                                <a
                                                                    href="{{ url('/catalogs/' . $lineItem->product_id) }}"
                                                                    target="_blank"
                                                                >{{ $lineItem->name }}</a>
                                                                @if ($lineItem->sku)
                                                                    <div>{{ __('SKU') }}: {{ $lineItem->sku }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-12 col-sm-2">
                                                                {{ $lineItem->formatted_price }}
                                                            </div>
                                                            <div class="col-12 col-sm-auto">
                                                                <div
                                                                    class="input-group mb-3 quantity-wrapper"
                                                                    style="width: 170px;"
                                                                >
                                                                    <div class="input-group-prepend">
                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-default quantity-increment"
                                                                        >
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input
                                                                        type="number"
                                                                        name="quantity"
                                                                        value="{{ $lineItem->quantity }}"
                                                                        class="form-control text-center quantity"
                                                                        min="1"
                                                                    />
                                                                    <div class="input-group-append">
                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-default quantity-decrement"
                                                                        >
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-auto">
                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-primary"
                                                                >
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-outline-danger"
                                                                    onclick="document.getElementById('btn-delete-line-item-{{ $lineItem->id }}').click()"
                                                                >
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col text-right">
                                                    {{ $lineItem->formatted_total_price }}
                                                </div>
                                            </div>
                                        </div>
                                        <form
                                            action="{{ url('/my/cart') }}"
                                            method="POST"
                                            style="display: none;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <input
                                                type="hidden"
                                                name="product_id"
                                                value="{{ $lineItem->product_id }}"
                                            />
                                            <button
                                                type="submit"
                                                id="btn-delete-line-item-{{ $lineItem->id }}"
                                            ></button>
                                        </form>
                                    </div>
                                @endforeach
                                <script>
                                    var Quantity = (function() {
                                        $('.quantity-increment').on('click', function() {
                                            var $quantityWrapper = $(this).closest('.quantity-wrapper');
                                            var $quantity = $quantityWrapper.find('.quantity');
                                            var previousQuantity = parseInt($quantity.val());

                                            $quantity.val(previousQuantity + 1);
                                        });

                                        $('.quantity-decrement').on('click', function() {
                                            var $quantityWrapper = $(this).closest('.quantity-wrapper');
                                            var $quantity = $quantityWrapper.find('.quantity');
                                            var previousQuantity = parseInt($quantity.val());

                                            if (previousQuantity > 1) {
                                                $quantity.val(previousQuantity - 1);
                                            }
                                        });

                                        $('.quantity').on('change', function() {
                                            if ($(this).val() > 0) {
                                                return;
                                            }

                                            $(this).val(1);
                                        });
                                    })();
                                </script>
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
                        <a
                            href="{{ url('/my/cart/checkout') }}"
                            class="btn btn-primary"
                        >{{ __('Checkout') }}</a>
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
