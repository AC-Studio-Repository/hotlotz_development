@foreach($inputs as $label => $name)
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ $label }}</label>
            <div class="input-group" style="width: 100%">
                {{ Form::text($name.'_start_time', null, [
                        'class' => 'form-control form-control-md ' . $name. '_start_time' . ($errors->has($name. '_start_time') ? ' is-invalid' : ''),
                        'id' => $name.'_start_time',
                    ])
                }}
                <span class="input-group-addon">
                    <i class="fas fa-clock"></i>
                </span>
            </div>
            {{
               Form::hidden($name.'_start',null,['id'=>$name.'_start'])
            }}

            @if ($errors->has($name.'_start_time'))
                <div class="invalid-feedback">{{ $errors->first($name.'_start_time') }}</div>
            @endif
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">&nbsp;</label>
            <div class="input-group" style="width: 100%">
                {{ Form::text($name.'_end_time', null, [
                        'class' => 'form-control form-control-md ' . $name . '_end_time' . ($errors->has($name. '_end_time') ? ' is-invalid' : ''),
                        'id' => $name.'_end_time',
                    ])
                }}
                <span class="input-group-addon">
                    <i class="fas fa-clock"></i>
                </span>
            </div>
            {{
               Form::hidden($name.'_end',null,['id'=>$name.'_end'])
            }}

            @if ($errors->has($name.'_end_time'))
                <div class="invalid-feedback">{{ $errors->first($name.'_end_time') }}</div>
            @endif
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">&nbsp;</label>
            <div class="input-group">
                <label class="checkbox-inline form-control-label" for="is_closed_{{$name}}">
                    @php
                        $is_closed_day = "is_closed_".$name;
                    @endphp

                    {{ Form::checkbox('is_closed_'.$name, 'Y', ($sys_config->$is_closed_day == 'Y')?true:false, [
                            'id' => "is_closed_".$name,
                        ]) 
                    }}
                    If close, please check
                    &nbsp;
                </label>
            </div>

            @if ($errors->has('is_closed_'.$name))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('is_closed_'.$name) }}</div>
            @endif
        </div>
    </div>
@endforeach