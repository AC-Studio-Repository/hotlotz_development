
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
            <table style="width:100%;">
                <tr>
                    <td width="20%"> <img src="{{ $logo }}"
                            alt="logo" style="margin-left:-1px;margin-top:5px;"  width="150px" height="25px" ></td>
                    <td colspan=2>
                        <p style="margin-top:8px;margin-left:20px;font-size:14px;float:right">
                            {{ $data['auction_info'] }}
                        </p>
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <table>
                @for ($i = 0; $i < sizeof($data['results']); $i++)
                 <tr valign= "top">
                    <td width="10%"><img src="{{ $data['results'][$i]['item_image'] }}"
                            alt="logo" style="margin-left:-1px;margin-top:18px;max-width:140px;max-height:130px;border:1px solid #DAD8D8" width="200" height="200"></td>
                    <td>
                        <p style="margin-left:20px;font-size:14px;">
                            {{ $data['results'][$i]['item_name'] }}
                        </p>
                        <p style="margin-left:20px;font-size:12px;margin-top:-5px">
                            {{ \Illuminate\Support\Str::limit($data['results'][$i]['item_description'], 175, $end='...') }}
                        </p>
                        <p style="margin-left:20px;font-size:12px;margin-top:-5px">
                             {{ $data['results'][$i]['dimension'] }}
                        </p>
                          <p style="margin-left:20px;font-size:12px;margin-top:-5px">
                            {{ \Illuminate\Support\Str::limit($data['results'][$i]['item_condition'], 175, $end='...') }}
                        </p>
                    </td>
                    <td width="25%">
                        <p style="margin-left:20px;font-size:14px;">
                            Lot # {{ $data['results'][$i]['lot_number'] }}
                        </p>
                        <p style="margin-left:20px;font-size:14px;margin-top:-5px">Opening Bid:</p>
                        <p style="margin-left:20px;font-size:10px;margin-top:-5px">{{ $data['results'][$i]['starting_bid'] }}</p>
                        <p style="margin-left:20px;font-size:14px;margin-top:-5px">Estimate:</p>
                        <p style="margin-left:20px;font-size:10px;margin-top:-5px">{{ $data['results'][$i]['estimate'] }}</p>
                    </td>
                </tr>
                @endfor
            </table>
         </tr>
      </table>
</body>
</html>
