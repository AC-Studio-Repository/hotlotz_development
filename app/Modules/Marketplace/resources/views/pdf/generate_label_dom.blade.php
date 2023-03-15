
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
                            <td>
                            <img style="margin-top:5px;margin-left:4px;" onclick="imagepreview(this)" lazyload="on" src="{{ $logo }}" width="175px"
                                    height="32px" alt="logo"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-top:5px;margin-left:6px;font-size:18px;">
                                {{ $data[$i]['title'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-top:-4px;margin-left:6px;font-size:13px;">
                                {{ $data[$i]['category_name'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-top:-4px;height:40px;margin-left:6px;font-size:13px;">
                                    {{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}
                                </p>
                            </td>
                            <br>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top:-2px;margin-bottom:2px;margin-left:6px;font-size:13px;">
                                BUY NOW PRICE | {{ $data[$i]['price'] }}</p>
                            </td>
                            <td>
                                <p style="margin-top:-2px;margin-bottom:2px;margin-left:6px;font-size:13px;">
                                 {{ $data[$i]['item_number'] }}</p>
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
                             <td>
                            <img style="margin-top:5px;margin-left:4px;" onclick="imagepreview(this)" lazyload="on" src="{{ $logo }}" width="175px"
                                    height="32px" alt="logo"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-top:5px;margin-left:6px;font-size:18px;">
                                {{ $data[$i]['title'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-top:-4px;margin-left:6px;font-size:13px;">
                                {{ $data[$i]['category_name'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-top:-4px;height:40px;margin-left:6px;font-size:13px;">
                                    {{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}
                                </p>
                            </td>
                            <br>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top:-2px;margin-bottom:2px;margin-left:6px;font-size:13px;">
                                BUY NOW PRICE | {{ $data[$i]['price'] }}</p>
                            </td>
                            <td>
                                <p style="margin-top:-2px;margin-bottom:2px;margin-left:6px;font-size:13px;">
                                 {{ $data[$i]['item_number'] }}</p>
                            </td>
                        </tr>

                    </table>
                </td>
                @else
                <td width="50%"></td>
                @endif
            </tr>
            </table>
            @if($i >= 7 && ($i + 1) % 8 == 0)
            <div class="page-break"></div>
            @else
            <br>
            @endif
            @endfor
    </body>
</html>
