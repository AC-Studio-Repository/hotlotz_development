
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

    body {
        font-family: "JosefinSans-Regular";
    }
    table {
      font-family: "JosefinSans-Regular";
      border-collapse: collapse;
      width: 100%;
    }

    td, th {
      text-align: left;
      padding: 8px;
      border-bottom:1px solid #dddddd;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }

</style>

</head>
<body>
    <img src="{{ $logo }}"
    alt="logo" style="margin-left:-1px;margin-top:5px;"  width="150px" height="25px" >
    <hr>    
      <table width = "100%" border = "0">
         <tr valign = "top">
            <table>
                <tr style="font-size:13px;">
                    <td>Image of Lot</td>
                    <td>Lot number</td>
                    <td>Title</td>
                    <td>Sold / Unsold</td>
                    <td>Item Reference</td>
                    <td>Seller Name</td>
                    <td>Opening Bid</td>
                    <td>Number of Bid</td>
                    <td>Hammer Result</td>
                    <td>Hammer + Premium Result</td>
                    <td>Buyer Name</td>
                </tr>
                  <tbody>
                    @foreach($saleReports as $item)
                    <tr style="font-size:11px;">
                        <td>
                            <img src="{{ $item['item_image'] }}"
                            alt="logo" style="margin-left:-1px;margin-top:5px;"  width="90px" height="90px" >

                        </td>
                        <td>
                            <div class="text-muted">
                                {{  __($item['lot_number'] ?? 'N/A') }}
                            </div>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ __($item['item_name'])}}
                            </span>
                        </td>
                        <td>
                            <div class="text-muted">
                                {{ __($item['item_status']) }}
                            </div>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                               {{ __($item['item_number'])}}
                            </span>
                        </td>
                         <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ __($item['seller'])}}
                            </span>
                        </td>
                        <td>
                            <div class="text-muted">
                                {{ __($item['starting_bid'])}}
                            </div>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $item['no_of_bid'] }}
                            </span>
                        </td>
                        <td>
                            <div class="text-muted">
                                ${{ __($item['hammar_price'])}}
                            </div>
                        </td>
                        <td>
                            <div class="text-muted">
                                ${{ __($item['total'])}}
                            </div>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                               {{ __($item['buyer'])}}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
         </tr>
      </table>
</body>

</html>
