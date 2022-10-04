@props(['lineItem', 'first', 'last'])

<div
    class="row align-items-start @if (!$first) border-top pt-2 @else pt-0 @endif @if (!$last) pb-2 @endif">
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
                            href="{{ url('/catalogs/' . $lineItem->product_id) }}"
                            target="_blank"
                        >{{ $lineItem->name }}</a>
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
