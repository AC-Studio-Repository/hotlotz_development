@extends('appshell::layouts.default')

@section('title')
    {{ __('Ticker Display Details') }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('ticker_display.ticker_displays.edit', $ticker_display) }}" class="btn btn-outline-info">{{ __('Edit Ticker Display') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $ticker_display->id }}" data-main_title="{{ $ticker_display->main_title }}" >{{ __('Delete Ticker Display') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('ticker_display::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var ticker_display_id = $(this).attr('data-id');
            var name = $(this).attr('data-main_title');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/ticker_displays/'+ticker_display_id,
                    type: 'delete',
                    data: {
                        "id": ticker_display_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('ticker_display.ticker_displays.index')}}";
                            });
                        }else {
                            bootbox.alert(response.message);
                            return false;
                        }
                    }
                });
            }
        });
    });

</script>

@stop
