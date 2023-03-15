
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
        @for($i = 0; $i <= sizeof($data); $i++) <table width="100%" border="0">
            <tr valign="top">
                @if(isset($data[$i]))
                 <td width="50%">
                    <table style="width:95%;padding:1px;border:1px solid #616D7E;">
                        <tr>
                             <td>  &nbsp;&nbsp;
                            <img style="margin-top:5px;" onclick="imagepreview(this)" lazyload="on" src="{{ $logo }}" width="175px"
                                    height="32px" alt="logo"></td>
                            <td>
                                <p style="margin-top:10px;margin-right:10px;text-align:right">
                                    {{ $data[$i]['customer_ref'] }}
                                </p>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:2px;font-size:25px;">
                                    Thank you</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:5px;font-size:20px;">
                                {{ $data[$i]['customer_fullname'] }}</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height:50px;">
                                <center style="font-size:14px;">
                                    {{ \Illuminate\Support\Str::upper($data[$i]['title'] )}}
                                </center>
                            </td>
                            <br>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:10px;font-size:13px;">
                                    AUCTIONS | MARKETPLACE | VALUATIONS</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:10px;font-size:13px;">
                                    hotlotz.com | hello@hotlotz.com | 6254 7616</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:10px;margin-bottom:10px;font-size:13px;">
                                {{ $data[$i]['sale_date'] }} | # {{ $data[$i]['item_number'] }}</center>
                            </td>
                        </tr>

                    </table>
                </td>
                @endif
                @php $i++ @endphp
                @if(isset($data[$i]))
                <td width="50%">
                    <table style="width:95%;padding:1px;border:1px solid #616D7E;">
                        <tr>
                             <td>  &nbsp;&nbsp;
                            <img style="margin-top:5px;" onclick="imagepreview(this)" lazyload="on" src="{{ $logo }}" width="175px"
                                    height="32px" alt="logo"></td>
                            <td>
                                <p style="margin-top:10px;margin-right:10px;text-align:right">
                                    {{ $data[$i]['customer_ref'] }}
                                </p>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:2px;font-size:25px;">
                                    Thank you</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:5px;font-size:20px;">
                                {{ $data[$i]['customer_fullname'] }}</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height:50px;">
                                <center style="font-size:14px;">
                                    {{ \Illuminate\Support\Str::upper($data[$i]['title'] )}}
                                </center>
                            </td>
                            <br>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:10px;font-size:13px;">
                                    AUCTIONS | MARKETPLACE | VALUATIONS</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:10px;font-size:13px;">
                                    hotlotz.com | hello@hotlotz.com | 6254 7616</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center style="margin-top:10px;margin-bottom:10px;font-size:13px;">
                                {{ $data[$i]['sale_date'] }} | # {{ $data[$i]['item_number'] }}</center>
                            </td>
                        </tr>

                    </table>
                </td>
                @else
                <td width="50%"></td>
                @endif
            </tr>
            </table>
            @if($i >= 5 && ($i + 1) % 6 == 0)
            <div class="page-break"></div>
            @else
            <br>
            @endif

            @endfor
    </body>
</html>