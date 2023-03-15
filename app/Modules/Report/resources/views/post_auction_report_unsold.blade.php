@extends('appshell::layouts.default')

@section('title')
    {{ __('Post Auction Report (Unsold)') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

        </div>

        <div class="card-block">

            <div class="row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Search Auction') }}</label>
                    {{ Form::text('search_text', null, [
                            'class'=>'form-control', 'id'=>'search_text'
                        ])
                    }}
                </div>
                <div class="col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">&nbsp;</label>
                    <div class="form-group">
                        <button type="button" class="btn btn-outline-primary" id="btnSearch" onclick="loadTable()">{{ __('Search') }}</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div style="overflow-x: auto;overflow-y: auto;" id="table_view">

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('scripts')
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>
<script>
    const api = '/manage/auctions/search/title?closed=yes';

    const auctions = [];

    fetch(api)
        .then(response => response.json())
        .then(blob => auctions.push(...blob));

    $( "#search_text" ).autocomplete({
        source: auctions
    });

    function loadTable() {
        $.ajax({
            url: '/manage/reports/unsold_post_auction_table',
            type: 'get',
            data: {
                "seller_id": $('#filter_seller').val(),
                "search_text": $('#search_text').val(),
            },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    $('#table_view').html(response.html);
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                    $('#filter_seller').select2();
                }else{
                     bootbox.alert('Report not found');
                }
            }
        });
    }

    function download_table_as_csv(table_id, separator = ',') {
        // Select rows from table_id
        var rows = document.querySelectorAll('table#' + table_id + ' tr');
        // Construct csv
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
            for (var j = 0; j < cols.length; j++) {
                // Clean innertext to remove multiple spaces and jumpline (break csv)
                var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                data = data.replace(/"/g, '""');
                // Push escaped string
                row.push('"' + data + '"');
            }
            csv.push(row.join(separator));
        }
        var csv_string = csv.join('\n');
        // Download it
        var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

</script>
@stop