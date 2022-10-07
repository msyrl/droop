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
        <form
            action="{{ url('/my/cart/checkout') }}"
            method="POST"
            enctype="multipart/form-data"
            id="cart-checkout-module"
        >
            <div class="container">
                <div class="row">
                    <div class="col-12">

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
                                    id="attachments"
                                    accept="application/pdf"
                                    multiple
                                />
                            </div>
                        </div>
                        <div class="card">
                            <div
                                class="card-body"
                                id="cart-summary"
                            ></div>
                            <script type="text/html" id="cart-summary-tmpl">
                                <div class="row mb-2">
                                    <div class="col">{{ __('Quantity') }}</div>
                                    <div class="col-auto ml-auto" id="cart_quantity"><%= cartQuantity.toLocaleString('id') %></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col">{{ __('Line items') }}</div>
                                    <div class="col-auto ml-auto" id="cart_line_items_price"><%= cartLineItemsPrice.toLocaleString('id') %></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col">{{ __('Additional charges') }}</div>
                                    <div class="col-auto ml-auto" id="cart_additional_charges_price"><%= cartAdditionalChargesPrice.toLocaleString('id') %></div>
                                </div>
                                <div class="row text-bold">
                                    <div class="col">{{ __('Total') }}</div>
                                    <div class="col-auto ml-auto" id="cart_total_price"><%= cartTotalPrice.toLocaleString('id') %></div>
                                </div>
                            </script>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >{{ __('Proceed') }}</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            var CartCheckout = (function() {
                var $el = $('#cart-checkout-module');
                var $attachments = $el.find('#attachments');
                var $cartSummary = $el.find('#cart-summary');

                var cartSummaryTmpl = $el.find('#cart-summary-tmpl').html();

                var defaultAdditionalPrice = {{ \App\Models\SalesOrder::getDefaultAdditionalCharge() }};
                var totalAttachment = 0;
                var cartQuantity = {{ $cart->quantity }};
                var cartLineItemsPrice = {{ $cart->total_price }};
                var cartAdditionalChargesPrice = 0;
                var cartTotalPrice = 0;

                $attachments.on('change', function() {
                    totalAttachment = this.files.length;

                    calculate();
                    render();
                });

                function calculate() {
                    cartAdditionalChargesPrice = totalAttachment * defaultAdditionalPrice;
                    cartTotalPrice = cartLineItemsPrice + cartAdditionalChargesPrice;
                }

                function render() {
                    $cartSummary.html(
                        ejs.render(cartSummaryTmpl, {
                            cartQuantity: cartQuantity,
                            cartLineItemsPrice: cartLineItemsPrice,
                            cartAdditionalChargesPrice: cartAdditionalChargesPrice,
                            cartTotalPrice: cartTotalPrice,
                        })
                    )
                }

                function init() {
                    render();
                }

                init();
            })();
        </script>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
