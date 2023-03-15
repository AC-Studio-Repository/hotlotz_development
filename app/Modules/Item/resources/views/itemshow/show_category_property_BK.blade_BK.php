<?php 
    $itemproperties = isset($item->category_data)?$item->category_data:[];
    // dd($itemproperties);
?>
@foreach ($categoryproperties as $index => $property)
    <?php
        $is_required = '';
        $req_span = '';
        if($property->is_required == 'Required'){
            // $is_required = "required";
            $req_span = "<span style='color:red;'>*</span>";
        }

        $property_value = [];
        if( strlen($property->value) > 0 && ($property->field_type == 'radio' || $property->field_type == 'checkbox') ){
            // print_r($property->value); echo "<br>";
            if (strpos($property->value, ',') !== false) {
                $property_value = explode(',',$property->value);
            }else{
                $property_value = [$property->value];
            }
            // print_r($property_value);echo "<br>";
        }
    ?>

    @if($property->field_type == 'text')
    
        <div class="form-group row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group"> 
                {{ Form::hidden('key_'.$index, $property->key, ['id'=>'key_'.$index])}}                
                {{ Form::text('value_'.$index, old('value_'.$index, isset($itemproperties[$property->key])?$itemproperties[$property->key]:$property->value), [
                        'class' => 'form-control',
                        'id'=>'pid_'.$index,
                        $is_required,
                        'data-parsley-required-message'=>"This value is required.",
                        'disabled'
                    ])
                }}
            </div>
        </div>

    @elseif($property->field_type == 'radio')
        <div class="form-group row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group">
                @if(count($property_value) > 0)
                    @foreach($property_value as $value)
                        <label class="radio-inline" for="">
                            {{ Form::radio('value_'.$index, $value, (isset($itemproperties[$property->key]) && $itemproperties[$property->key] == $value)?true:false, [
                                    'id' => "value_".$index, 
                                    $is_required, 
                                    'data-parsley-required-message'=>"This value is required.",
                                    'data-parsley-multiple'=>"value_".$index,
                                    'data-parsley-errors-container'=>"#radio_error_".$index,
                                    'disabled'=>'true'
                                ]) 
                            }}
                            {{ $value }}
                            &nbsp;
                        </label>
                    @endforeach
                @endif
                <div id="radio_error_{{$index}}"></div>
            </div>
        </div>
    @else
        <div class="form-group row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group">
                @if(count($property_value) > 0)
                    @foreach($property_value as $value)
                        <label class="checkbox-inline" for="">
                            {{ Form::checkbox('value_'.$index, $value, (isset($itemproperties[$property->key]) && $itemproperties[$property->key] == $value)?true:false, [
                                    'id' => "value_".$index, 
                                    $is_required, 
                                    'data-parsley-required-message'=>"This value is required.",
                                    'data-parsley-multiple'=>"value_".$index,
                                    'data-parsley-errors-container'=>"#checkbox_error_".$index,
                                    'disabled'
                                ]) 
                            }}
                            {{ $value }}
                            &nbsp;
                        </label>
                    @endforeach
                @endif
                <div id="checkbox_error_{{$index}}"></div>
            </div>
        </div>

    @endif

@endforeach