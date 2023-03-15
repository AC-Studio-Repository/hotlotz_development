
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
</style>

</head>
<body>
    @for($i = 0; $i <= sizeof($data); $i++)
      <table width = "100%" border = "0">

         <tr valign = "top">
            @if(isset($data[$i]))
            <td width = "50%">
                 <table style="width:95%;padding: 5px 10px 2px 10px;border:1px solid #616D7E;">
                    <tr>
                        <td> <img onclick="imagepreview(this)" lazyload="on" src="{{ $logo }}" width="105px" height="18px"
                                alt="logo" style="margin-left:-1px;margin-top:5px;"></td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:5px;font-size:12px;">
                                {{ $data[$i]['title'] }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:-2px;font-size:10px;">Closing {{ $data[$i]['close_date'] }}</p>
                            <p style="margin-top:-1px;font-size:12px;">
                                Lot {{ $data[$i]['lot_number'] }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:-1px;font-size:10px;">
                                 <div style="height:20px;font-size:10px;">
                                    {{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}
                                </div>
                            </p>
                            <p style="margin-top:-1px;font-size:10px;">Estimate | {{ $data[$i]['estimate'] }}</p>
                            <p style="margin-top:-1px;font-size:10px;">Opening Bid | {{ $data[$i]['starting_bid'] }}

                            </p>
                            <p style="margin-top:-25px;font-size:10px;text-align:right">
                                {{ $data[$i]['item_number'] }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            @endif
            @php $i++ @endphp
            @if(isset($data[$i]))
            <td width = "50%">
                 <table style="width:95%;padding: 5px 10px 2px 10px;border:1px solid #616D7E;">
                    <tr>
                        <td>
                        <img onclick="imagepreview(this)" lazyload="on" src="{{ $logo }}" width="105px" height="18px"
                                alt="logo" style="margin-left:-1px;margin-top:5px;"></td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:5px;font-size:12px;">
                                {{ $data[$i]['title'] }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:-2px;font-size:10px;">Closing {{ $data[$i]['close_date'] }}</p>
                            <p style="margin-top:-1px;font-size:12px;">
                                Lot {{ $data[$i]['lot_number'] }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:-1px;font-size:10px;">
                                <div style="height:20px;font-size:10px;">
                                    {{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}
                                </div>
                            </p>
                            <p style="margin-top:-1px;font-size:10px;">Estimate | {{ $data[$i]['estimate'] }}</p>
                            <p style="margin-top:-1px;font-size:10px;">Opening Bid | {{ $data[$i]['starting_bid'] }}
                            </p>
                              <p style="margin-top:-25px;font-size:10px;text-align:right">
                                {{ $data[$i]['item_number'] }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            @else
            <td width = "50%"></td>
            @endif
         </tr>

      </table>
      @if($i >= 7 && ($i + 1) % 8 == 0)
        <div class="page-break"></div>
        @else
        <br>
        <br>
        @endif
    @endfor

</body>
</html>