@extends('appshell::layouts.default')

@section('title')
    {{ __('Marketplace Banner Details') }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('marketplace_banner.marketplace_banners.edit', $marketplace_banner) }}" class="btn btn-outline-info">{{ __('Edit Marketplace Banner') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $marketplace_banner->id }}" data-main_title="{{ $marketplace_banner->main_title }}" >{{ __('Delete Marketplace Banner') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('marketplace_banner::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var marketplace_banner_id = $(this).attr('data-id');
            var name = $(this).attr('data-main_title');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/marketplace_banners/'+marketplace_banner_id,
                    type: 'delete',
                    data: {
                        "id": marketplace_banner_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('marketplace_banner.marketplace_banners.index')}}";
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
