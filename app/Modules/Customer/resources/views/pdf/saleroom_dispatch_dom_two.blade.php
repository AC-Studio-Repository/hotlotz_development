
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
    <table width="100%" border="0">
        <tr >
            <td width="40%">
                <table style="width:95%;padding: 5px 10px 2px 10px;" border="0">
                    <tr>
                        <td>
                            <img src="{{ $logo }}" alt="logo" style="margin-left:-1px;margin-top:5px;">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:10px;font-size:30px;">
                                Item Collection
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="60%">
                <table style="width:95%;border-spacing: 0px;margin-bottom: 20px;margin-left: 30px;margin-right: -15px;" border="1">
                    <tr>
                        <td style="padding:5px 0px 5px 0px;font-size:25px;text-align: center;">PAID</td>
                        <td style="padding:5px 0px 5px 0px;font-size:25px;text-align: center;">UNSOLD</td>
                        <td style="padding:5px 0px 5px 0px;font-size:25px;text-align: center;">DECLINED</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-left: 5px;padding-bottom: 30px;font-size:12px;">Collection Date:</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <table style="width:100%;padding: 5px 1px 2px 10px;">
                <tr>
                    <td>
                        <p style="margin-top:-2px;font-size:18px;">Paddle Number</p>

                    </td>
                    <td>
                        <p style="margin-top:-1px;font-size:18px;float:right">
                            {{ $customer->ref_no }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin-top:-2px;font-size:18px;">First Name</p>

                    </td>
                    <td>
                        <p style="margin-top:-1px;font-size:18px;float:right">
                            {{ $customer->firstname ?? 'N/A' }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin-top:-2px;font-size:18px;">Last Name</p>

                    </td>
                    <td>
                        <p style="margin-top:-1px;font-size:18px;float:right">
                            {{ $customer->lastname ?? 'N/A'}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin-top:-2px;font-size:18px;"> Mobile Number</p>

                    </td>
                    <td>
                        <p style="margin-top:-1px;font-size:18px;float:right">
                            {{ $customer->phone ?? 'N/A'}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin-top:-2px;font-size:18px;">Email</p>

                    </td>
                    <td>
                        <p style="margin-top:-1px;font-size:18px;float:right">
                            {{ $customer->email }}
                        </p>
                    </td>
                </tr>
                @if($customer->company_name != null)
                <tr>
                    <td>
                        <p style="margin-top:-2px;font-size:18px;">Company</p>

                    </td>
                    <td>
                        <p style="margin-top:-1px;font-size:18px;float:right">
                            {{ $customer->company_name ?? 'N/A' }}
                        </p>
                    </td>
                </tr>
                @endif
            </table>
        </tr>
    </table>
    <hr>
    <table style="width:95%;padding: 5px 10px 2px 10px;">
        <tr>
            <td style="float:left;width:5%">#</td>
            <td style="float:left;width:25%">Image</td>
            <td style="float:left;">Name</td>
            <td style="width:15%">Collected by</td>
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
                    <span style="font-size: 14px;">{{ $item->item_number }}</span><br>
                    <span style="font-size:18px;font-weight: bold;">{{ $item->name }}</span>
                    <br>
                    <span style="font-size: 14px;">{{ $info[$key] }}</span>
                </p>
            </td>
            <td>
                <table style="width:95%;border-spacing: 0px;" border="0.1">
                    <tr>
                        <td style="padding-bottom: 50px;font-size:12px;">Initials</td>
                    </tr>
                </table>
            </td>
        </tr>
        @php $i++; @endphp
        @endforeach
    </table>
    <hr>
    <br>
    <table width="100%" border="0">
        <tr>
            <td style="font-size:18px;padding-bottom: 50px;">Collected by</td>
        </tr>
        <tr>
            <td width="20%;">
                <hr>
                <p style="margin-top:-1px;font-size:14px;text-align: center;">Name</p>
            </td>
            <td width="15%"></td>
            <td width="20%;">
                <hr>
                <p style="margin-top:-1px;font-size:14px;text-align: center;">Signature</p>
            </td>
            <td width="15%"></td>
            <td width="20%;">
                <hr>
                <p style="margin-top:-1px;font-size:14px;text-align: center;">Date</p>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table width="100%" style="border-spacing: 0px;" border="1">
        <tr>
            <td style="padding-bottom: 50px;">
                <p style="margin-top:-1px;font-size:14px;">
                    Remarks: {{ $additional_note }}
                </p>
            </td>
        </tr>
    </table>
    <br>
    <p style="font-size:12px;">
        We are located at:<br>
        Hotlotz, Cendex Center #01-15, 120 Lower Delta Rd, 169208<br>
        <br>
        Do you have items to sell?<br>
        Our world class bidding platform is powered by technology from our<br>
        partner, market leader thesaleroom.com. This means that all our sales<br>
        are internationally marketed, providing Singapore-based sellers with<br>
        full access to global buyers.<br>
        <br>
        Can we help? email us at hello@hotlotz.com
    </p>
</body>
</html>
