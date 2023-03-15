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
    <img src="{{ $logo }}" alt="logo" style="margin-left:-1px;margin-top:5px;" width="150px" height="25px" >
    <hr>    
    <table width = "100%" border = "0">
        <tr valign = "top">
            <table>
                <tr style="font-size:13px;">
                    <td>Name</td>
                    <td>Reference Number</td>
                    <td>Status</td>
                </tr>
                <tbody>
                    @foreach($kycReports as $item)
                    <tr style="font-size:11px;">
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ __($item['name'])}}
                            </span>
                        </td>
                        <td>
                            <div class="text-muted">
                                {{ __($item['ref_no']) }}
                            </div>
                        </td>
                        <td>
                            <div class="text-muted">
                                {{ __($item['status']) }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </tr>
    </table>
</body>

</html>