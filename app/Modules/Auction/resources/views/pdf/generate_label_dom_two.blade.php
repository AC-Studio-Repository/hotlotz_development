
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
                    <table style="width:95%;padding: 5px 10px 2px 10px;border:1px solid #616D7E;border-spacing:0px;" border="0">
                        <tr>
                            <td>
                                <img lazyload="on" src="{{ $logo }}" width="200px" height="30px" alt="logo" style="margin-left:-1px;margin-top:5px;">
                                <p style="margin-top:-35px;font-size:20px;text-align:right;margin-bottom: -1px;">
                                    {{ $data[$i]['item_number'] }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top:4px;;font-size:12px;">
                                    {{ $data[$i]['title'] }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top:-2px;font-size:10px;">Closing {{ $data[$i]['close_date'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 1rem;">
                                <p style="margin-top:-1px;">
                                    <div style="height:40px;font-size:18px;">
                                        {{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}
                                    </div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width:70%;border-spacing: 0px;">
                                    <tr>
                                        <td style="font-size:12px;border:1px solid #616D7E;">Estimate</td>
                                        <td style="font-size:10px;border:1px solid #616D7E;">{{ $data[$i]['estimate'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px;border:1px solid #616D7E;">Opening Bid</td>
                                        <td style="font-size:10px;border:1px solid #616D7E;">{{ $data[$i]['starting_bid'] }}</td>
                                    </tr>
                                </table>
                                <p style="margin-top:-45px;margin-bottom: -3px;font-size:35px;font-weight: bold;text-align:right">
                                    #{{ $data[$i]['lot_number'] }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                @endif
                @php $i++ @endphp
                @if(isset($data[$i]))
                <td width = "50%">
                    <table style="width:95%;padding: 5px 10px 2px 10px;border:1px solid #616D7E;border-spacing:0px;">
                        <tr>
                            <td>
                                <img lazyload="on" src="{{ $logo }}" width="200px" height="30px" alt="logo" style="margin-left:-1px;margin-top:5px;">
                                <p style="margin-top:-35px;font-size:20px;text-align:right;margin-bottom: -1px;">
                                    {{ $data[$i]['item_number'] }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top:4px;font-size:12px;">
                                    {{ $data[$i]['title'] }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top:-2px;font-size:10px;">Closing {{ $data[$i]['close_date'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 1rem;">
                                <p style="margin-top:-1px;">
                                    <div style="height:40px;font-size:18px;">
                                        {{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}
                                    </div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width:70%;border-spacing: 0px;">
                                    <tr>
                                        <td style="font-size:12px;border:1px solid #616D7E;">Estimate</td>
                                        <td style="font-size:10px;border:1px solid #616D7E;">{{ $data[$i]['estimate'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px;border:1px solid #616D7E;">Opening Bid</td>
                                        <td style="font-size:10px;border:1px solid #616D7E;">{{ $data[$i]['starting_bid'] }}</td>
                                    </tr>
                                </table>
                                <p style="margin-top:-45px;margin-bottom: -3px;font-size:35px;font-weight: bold;text-align:right">
                                    #{{ $data[$i]['lot_number'] }}
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