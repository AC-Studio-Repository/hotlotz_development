@extends('appshell::layouts.default')

@section('title')
    {{ __('Precious Stone, Precious Metal Report') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        @yield('title')

    </div>

    {!! Form::model($items, ['id'=>"itemFilterForm", 'route'  => ['report.reports.pspm_filter'], 'method' => 'POST', 'data-parsley-validate'=>"true"]) !!}
    <div class="card-block">
        <div class="row">
            <div class="form-group col-12 col-md-3 col-xl-3">
                <label class="form-control-label">{{ __('Start Date') }}</label>
                <input type="datetime-local" id="start_date" name="start_date">
            </div>
            <div class="form-group col-12 col-md-3 col-xl-3">
                <label class="form-control-label">{{ __('End Date') }}</label>
                <input type="datetime-local" id="end_date" name="end_date">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-12 col-md-12 col-xl-12 text-right">
                <button type="button" class="btn btn-md btn-outline-primary" id="btnSearch">{{ __('Search') }}</button>
                <button type="button" class="btn btn-md btn-outline-success float-right" id="btnResetAll">Reset All</button>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="form-group col-12 col-md-2 col-xl-2">
                <label class="form-control-label">{{ __('Per page') }}</label>
                 {{ Form::select('per_page', ['10'=>'10', '50'=>'50', '100'=>'100'], null, [
                        'class'=>'form-control', 'id'=>'per_page'
                    ])
                }}
            </div>

            {!! Form::close() !!}
            <div class="col-12 col-md-10 col-xl-10 mt-2">
                <div class="btn-group float-right ml-3">
                    <button type="button" class="btn btn-outline-success dropdown-toggle mb-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Generate Report
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" onclick="download_table_as_csv('pspm');" href="#">CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-block" id="divItemList">
        @include('report::extends.pspm_table')
    </div>

    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />

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
    var _token = $('input[name="_token"]').val();

    $(function(){

        $('#btnResetAll').click(function(){
            location.reload();
        });

        $('#btnSearch').click(function(){
            loadTable();
        });

        $('#per_page').change(function(){
            loadTable();
        });

    });

    function loadTable(page=null)
    {
        var url = "/manage/reports/pspm_filter";
        if(page != null){
            url = "/manage/reports/pspm_filter?page="+page;
        }
        $.ajax({
            url:url,
            type: 'post',
            data: $('#itemFilterForm').serialize()+"&_token="+_token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    $('#divItemList').html(response.html);
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                }
            }
        });
    }

    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);

        $('li').removeClass('active');
        $(this).parent().addClass('active');
        loadTable(page);
    });

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