<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $salesOrder->name }}</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2),
        .invoice-box table tr td:nth-child(3) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr td.total {
            border-top: 2px solid #eee;
            font-weight: bold;
            text-align: right;
        }

        .invoice-box table tr td.payment-method {
            vertical-align: bottom;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table
            cellpadding="0"
            cellspacing="0"
        >
            <tr class="top">
                <td colspan="3">
                    <table>
                        <tr>
                            <td class="title">
                                {{ __('Invoice') }}
                            </td>
                            <td>
                                {{ __('Invoice') }} #: {{ $salesOrder->name }}<br />
                                {{ __('Created') }}: {{ $salesOrder->created_at->format('F d, Y') }}<br />
                                {{ __('Due') }}: {{ $salesOrder->due_date->format('F d, Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="3">
                    <table>
                        <tr>
                            <td width="50%">
                                {{ Config::get('company.name') }}<br />
                                {{ Config::get('company.address') }}<br />
                                {{ config::get('company.phone') }}
                            </td>
                            <td>
                                {{ $salesOrder->user->name }}<br />
                                {{ $salesOrder->user->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>{{ __('Items') }}</td>
                <td>{{ __('Price') }}</td>
                <td width="250px">{{ __('Total') }}</td>
            </tr>
            @foreach ($salesOrder->lineItems as $lineItem)
                <tr class="item @if ($loop->last) last @endif">
                    <td>
                        <div>{{ $lineItem->name }}</div>
                        <div>SKU: {{ $lineItem->sku }}</div>
                    </td>
                    <td>{{ $lineItem->formatted_price }} * {{ $lineItem->formatted_quantity }}</td>
                    <td>{{ $lineItem->formatted_total_price }}</td>
                </tr>
            @endforeach
            <tr>
                <td
                    colspan="2"
                    rowspan="3"
                    class="payment-method"
                >
                    <div>{{ __('For payment please transfer to:') }}</div>
                    <div style="font-weight: bold;">{{ Config::get('company.account.bank') }}
                        {{ Config::get('company.account.number') }} | {{ Config::get('company.account.name') }}</div>
                </td>
                <td class="total">{{ __('Subtotal') }}: {{ $salesOrder->formatted_total_line_items_price }}</td>
            </tr>
            <tr>
                <td class="total">{{ __('Additional charges') }}:
                    {{ $salesOrder->formatted_total_additional_charges_price }}</td>
            </tr>
            <tr>
                <td class="total">{{ __('Total') }}: {{ $salesOrder->formatted_total_price }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
