@extends('appshell::layouts.default')

@section('title')
    {{ __('What We Sell Details') }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('what_we_sell.what_we_sells.edit', $what_we_sell) }}" class="btn btn-outline-info">{{ __('Edit What We Sell') }}</a>
            
            <a href="{{ route('what_we_sell.what_we_sells.highlight_list', $what_we_sell) }}" class="btn btn-outline-primary">{{ __('Highlight List') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $what_we_sell->id }}" data-main_title="{{ $what_we_sell->main_title }}" >{{ __('Delete What We Sell') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('what_we_sells::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var what_we_sell_id = $(this).attr('data-id');
            var name = $(this).attr('data-main_title');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/what_we_sells/'+what_we_sell_id,
                    type: 'delete',
                    data: {
                        "id": what_we_sell_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('what_we_sell.what_we_sells.index')}}";
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
