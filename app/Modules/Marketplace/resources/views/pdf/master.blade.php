<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>
        @section('title')
            PDF View -
        @show
    </title>
</head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        @font-face {
          font-family: "JosefinSans-Regular";
          src: url(/fonts/js-regular.woff2?a142b3c8d74a3ce1c7f99786bf2e6146) format("woff2"), url(/fonts/js-regular.woff?678a7e7df0b300635e83edca092a25ab) format("woff");
          font-weight: normal;
          font-style: normal;
        }

        html, body {
            height: 100%;
            font-family: 'JosefinSans-Regular';
        }
        @media print {
            .pagebreak {
                clear: both;
                page-break-after: always;
            }
        }
    </style>

    @stack('styles')

    <body>

    @yield('content')

    @stack('scripts')

    </body>
</html>