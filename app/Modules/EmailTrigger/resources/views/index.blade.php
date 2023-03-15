@extends('appshell::layouts.default')

@section('title')
    {{ __('Email Trigger') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

        </div>

        <div class="card-block">
            <div class="row">
                <div class="form-group row">
                    <label class="form-control-label col-md-4">Choose Email Template</label>
                    <div class="col-md-6 form-group">
                        {{ Form::hidden('invisible', 'secret', array('id' => 'invisible_id')) }}
                        <!-- <div class="input-group"> -->
                            @foreach($email_trigger as $value)
                                <label class="checkbox-inline" for="">
                                    {{ Form::checkbox('chk_email_template', $value->id, false, [
                                            'id' => "value_".$value->id,
                                            'data-parsley-required-message'=>"This value is required.",
                                            'data-parsley-multiple'=>"value_".$value->id,
                                            'data-parsley-errors-container'=>"#checkbox_error_".$value->id,
                                            'class'=>'template-list'
                                        ])
                                    }}
                                    {{ $value->title }}
                                    &nbsp;
                                </label>
                                <hr />
                            @endforeach
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" onclick="send_email_trigger();">{{ __('Send Event') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

@stop

@section('scripts')

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $('.template-list').on('change', function() {
        $('.template-list').not(this).prop('checked', false);
    });

    function send_email_trigger()
    {
        var selected_tempate = 0;
        $('input[type=checkbox]:checked').each(function () {
            selected_tempate = $(this).attr('value');
        });

        $.ajax({
            url: "/manage/emailtriggers/sendEmailEventAjax",
            type: 'post',
            data:{selected_tempate, _token },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1'){
                    window.location.href = "{{ route('email_trigger.emailtriggers.index')}}";
                }
            }
        });

    }
</script>

@stop
