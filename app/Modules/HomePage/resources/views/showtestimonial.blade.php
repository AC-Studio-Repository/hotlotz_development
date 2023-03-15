@extends('appshell::layouts.default')

@section('title')
    {{ __('Home Page Testimonial') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        <div class="card-block">
            <div class="row">
                <div class="form-group row">
                    <label class="form-control-label col-md-4">Choose Testimonial</label>
                    <div class="col-md-6 form-group">
                        {{ Form::hidden('invisible', 'secret', array('id' => 'invisible_id')) }}
                        <!-- <div class="input-group"> -->
                            @foreach($testimonial as $value)
                                <label class="checkbox-inline" for="">
                                    {{ Form::checkbox('chk_value_'.$value->id, $value->id, (in_array($value->id, $homepage_testimonial))?true:false, [
                                            'id' => "value_".$value->id,
                                            'data-parsley-required-message'=>"This value is required.",
                                            'data-parsley-multiple'=>"value_".$value->id,
                                            'data-parsley-errors-container'=>"#checkbox_error_".$value->id
                                        ])
                                    }}
                                    {{ $value->quote }} by {{$value->author}}
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

            <button class="btn btn-success" onclick="saveTestimonial_info();">{{ __('Save Info') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
@stop

@section('scripts')


<script type="text/javascript">

    var _token = $('input[name="_token"]').val();

    function saveTestimonial_info()
    {
        var selected = [];
        $('input[type=checkbox]:checked').each(function () {
            selected.push($(this).attr('value'));
        });

        var selected_testimonial = JSON.stringify(selected);

        $.ajax({
            url: "/manage/home_pages/storeTestimonialAjax",
            type: 'post',
            data:{selected_testimonial, _token },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1'){
                    window.location.href = "{{ route('home_page.home_pages.showtestimonial')}}";
                }
            }
        });

    }

</script>

@stop
