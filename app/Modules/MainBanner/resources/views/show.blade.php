@extends('appshell::layouts.default')

@section('title')
    {{ __('Main Banner Details') }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('main_banner.main_banners.edit', $main_banner) }}" class="btn btn-outline-info">{{ __('Edit Main Banner') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $main_banner->id }}" data-main_title="{{ $main_banner->main_title }}" >{{ __('Delete Main Banner') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('main_banner::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var main_banner_id = $(this).attr('data-id');
            var name = $(this).attr('data-main_title');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/main_banners/'+main_banner_id,
                    type: 'delete',
                    data: {
                        "id": main_banner_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('main_banner.main_banners.index')}}";
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
