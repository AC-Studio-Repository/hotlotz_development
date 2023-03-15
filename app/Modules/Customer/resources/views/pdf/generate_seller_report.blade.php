@php
    use \App\Modules\Item\Models\Item;
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>

    @font-face {
        font-family: 'JosefinSans-Regular';
        src: url({{ storage_path('fonts\JosefinSans-Regular.ttf') }}) format("truetype");
        font-weight: 300;
        font-style: normal;
    }

    .page-break {
        page-break-after: always;
    }

    .borderless td, .borderless th {
        border: none;
    }
    table {
        border-collapse: initial;
    }
    body {
        font-family: "JosefinSans-Regular";
    }
</style>

</head>
<body>
    <table width = "100%" border = "0">
        <tr valign = "top">
            <td width = "50%">
                <table style="width:95%;padding: 5px 10px 2px 10px;">
                    <tr>
                        <td width="20%">
                            <img src="{{ $logo }}" alt="logo" style="margin-left:-1px;margin-top:5px;">
                        </td>
                        <td colspan="7">
                            <p style="margin-top:20px;margin-left:20px;float:right">
                                {{ $data['receiveDate'] }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr>
    <table style="width:100%;padding: 5px 10px 2px 10px;">
        <tr>
            <td style="float:left;width:5%">#</td>
            <td style="float:left;width:15%"></td>
            <td width="10%" style="float:left;">Item Number</td>
            <td width="10%" style="float:left;">Item Name</td>
            <td style="float:left;">Estimate</td>
            <td style="float:left;">Reserve</td>
            <td style="float:left;">Status</td>
            <td style="float:left;">Hammer Price/Result Price</td>
        </tr>

        @php $i = 1; @endphp
        @foreach($items as $key => $item)
        <tr>
            <td style="float:left;">
                <p style="margin-top:-5px;">{{ $i }}</p>
            </td>
            <td>
                <img src="{{ $itemImage[$key] }}"
                alt="logo" style="margin-left:-1px;margin-top:5px;" width="100px" height="100px">
            </td>
            <td>
                <p style="margin-top:-1px;">
                    {{ $item->item_number }}
                </p>
            </td>
            <td>
                <p style="margin-top:-1px;">
                    {{ $item->name }}
                </p>
            </td>
            <td>
                <p style="margin-top:-1px;">
                    ${{ number_format($item->low_estimate) }}/${{ number_format($item->high_estimate) }}
                </p>
            </td>
            <td>
                <p style="margin-top:-1px;">
                    ${{ ($item->is_reserve == 'Y' && $item->reserve != null)?number_format($item->reserve):0 }}
                </p>
            </td>
            <td>
                <p style="margin-top:-1px;">
                    @if($item->status == Item::_SOLD_)
                        <span class="badge badge-pill badge-warning">{{ __($item->status) }}</span>
                    @elseif($item->status == Item::_PAID_)
                        <span class="badge badge-pill badge-info">{{ __($item->status) }}</span>
                    @elseif($item->status == Item::_SETTLED_)
                        <span class="badge badge-pill badge-secondary">{{ __($item->status) }}</span>
                    @else
                        <span class="badge badge-pill badge-success">{{ __($item->status) }}</span>
                    @endif
                </p>
            </td>
            <td>
                <p style="margin-top:-1px;">
                    @if($item->buyer_id > 0)
                    <div class="mb-3">
                        Hammer Price <br>
                        ${{ ($item->sold_price != null)?number_format($item->sold_price):0.00 }}
                    </div>
                    <div>
                        Result Price <br>
                        ${{ (isset($item->total) && $item->total != null)?number_format($item->total,2,'.',','):0.00 }}
                    </div>
                    @endif
                </p>
            </td>
        </tr>
        @php $i++; @endphp
        @endforeach
    </table>
</body>
</html>
