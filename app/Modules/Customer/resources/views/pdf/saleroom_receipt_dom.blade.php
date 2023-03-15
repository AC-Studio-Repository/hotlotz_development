
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
      <table width = "100%">
         <tr>
            <td width = "50%">
                 <table style="width:95%;padding: 5px 10px 2px 10px;">
                    <tr>
                        <td> <img src="{{ $logo }}"
                                alt="logo" style="margin-left:-1px;margin-top:5px;"></td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top:20px;font-size:30px;">
                                Receipt
                            </p>
                        </td>
                    </tr>
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
            </td>
            </tr>
        </table>
        <hr>
        <table style="width:95%;padding: 5px 10px 2px 10px;">
            <tr>
                <td style="float:left;width:5%">#</td>
                <td style="float:left;width:25%"></td>
                <td style="float:left;">Item Name</td>
                <td style="float:right;width:15%">Item Code</td>
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
                    <p style="margin-top:-1px;font-size:18px;">
                        {{ $item->name }}
                    </p>
                </td>
                <td style="float:right;">
                    <p style="margin-top:-1px;font-size:18px;">
                        {{ $item->item_number }}
                    </p>
                </td>
            </tr>
            @php $i++; @endphp
            @endforeach
        </table>
        <hr>
        <br>
        @if($additional_note)
        <p style="font-size:20px;">Additional Information</p>
        <p style="font-size:15px;margin-bottom:3px;">
            {{ $additional_note }}
        </p>
        @endif
        <p style="font-size:20px;">
            Received into the saleroom by {{ $receiveBy }} on {{ $receiveDate }}
        </p>

</body>
</html>