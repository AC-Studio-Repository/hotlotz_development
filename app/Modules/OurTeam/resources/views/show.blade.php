@extends('appshell::layouts.default')

@section('title')
    {{ __('Team Member Details') }}
@stop

@section('content')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('our_team.our_teams.edit', $our_team) }}" class="btn btn-outline-info">{{ __('Edit Team Member') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $our_team->id }}" data-name="{{ $our_team->name }}" >{{ __('Delete Team Member') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('our_team::_details')
        </div>
    </div>
@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var our_team_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/our_teams/'+our_team_id,
                    type: 'delete',
                    data: {
                        "id": our_team_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('our_team.our_teams.index')}}";
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