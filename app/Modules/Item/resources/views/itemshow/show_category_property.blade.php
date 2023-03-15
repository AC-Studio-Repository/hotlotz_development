<?php 
    $itemproperties = isset($item->category_data)?$item->category_data:[];
    //dd($itemproperties);
?>

<input type="hidden" name="count_cat_property" value="{{ count($categoryproperties) }}">

@foreach ($categoryproperties as $index => $property)
    <?php
        $is_required = '';
        $req_span = '';
        if($property->is_required == 'Required'){
            // $is_required = "required";
            $req_span = "<span style='color:red;'>*</span>";
        }

        $property_value = [];
        if( strlen($property->value) > 0 && $property->field_type != 'text' ){
            // print_r($property->value); echo "<br>";
            // if (strpos($property->value, ',') !== false) {
                $property_value = explode(',',$property->value);
            // }
            // else{
            //     $property_value = [$property->value];
            // }
            // print_r($property_value);echo "<br>";

            if( count($property_value) > 0 && ($property->field_type == 'checkbox' || $property->field_type == 'dropdown' || $property->field_type == 'dropdown&checkbox') ){
                $dropdown_data = [];
                foreach ($property_value as $key => $value) {
                    $dropdown_data[$value] = $value;
                }
            }
        }
        
    ?>

    @if($property->field_type == 'text')    
        <div class="form-row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group"> 
                {{ Form::hidden('key_'.$index, $property->key, ['id'=>'key_'.$index])}}                
                {{ Form::text('value_'.$index, isset($itemproperties[$property->key])?$itemproperties[$property->key]:$property->value, [
                        'class' => 'form-control',
                        'id'=>'pid_'.$index,
                        $is_required,
                        'data-index'=>$index,
                        'disabled'
                    ])
                }}
            </div>
        </div>
    @endif

    @if($property->field_type == 'radio')
        <div class="form-row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group">
                {{ Form::hidden('key_'.$index,$property->key,['id'=>'key_'.$index])}}
                @if(count($property_value) > 0)
                    @foreach($property_value as $key => $value)
                        <label class="radio-inline" for="">
                            {{ Form::radio('value_'.$index, $value, (isset($itemproperties[$property->key]) && $itemproperties[$property->key] == $value)?true:false, [
                                    'id' => "value_".$index, 
                                    $is_required, 
                                    'data-index'=>$index,
                                    'data-parsley-multiple'=>"value_".$index,
                                    'data-parsley-errors-container'=>"#radio_error_".$index,
                                    'disabled'
                                ]) 
                            }}
                            {{ $value }}
                            &nbsp;
                        </label>
                        @if( ($key+1)/4 == 0 )
                            </br>
                        @endif
                    @endforeach
                @endif
                <div id="radio_error_{{$index}}"></div>
            </div>
        </div>
    @endif

    @if($property->field_type == 'checkbox')
        @php
            $itemproperty_arr = null;
            if(isset($itemproperties[$property->key])){
                $itemproperty_arr = explode(',',$itemproperties[$property->key]);
            }
        @endphp
        <div class="form-row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group">
                {{ Form::hidden('key_'.$index,$property->key,['id'=>'key_'.$index])}}
                    @if(count($property_value) > 0)
                        @foreach($property_value as $key => $value)
                            <label class="checkbox-inline" for="">
                                {!! Form::checkbox('value_'.$index.'[]', $value, (isset($itemproperties[$property->key]) && in_array( $value, $itemproperty_arr ) )?true:false, [
                                        'class' => 'checkbox',
                                        'id' => "value_".$index, 
                                        $is_required,
                                        'data-index'=>$index,
                                        'data-parsley-multiple'=>"value_".$index,
                                        'data-parsley-errors-container'=>"#checkbox_error_".$index,
                                        'disabled'
                                    ]) 
                                !!}
                                {{ $value }}
                                &nbsp;
                            </label>

                                @if( ($key+1)/4 == 0 )
                                    </br>
                                @endif
                        @endforeach
                    @endif
            </div>
            <div id="checkbox_error_{{$index}}"></div>
        </div>
    @endif

    @if($property->field_type == 'dropdown')
        <div class="form-row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group">    
                {{ Form::select('value_'.$index, [''=>'--- Please Select ---'] + $dropdown_data, isset($itemproperties[$property->key])?$itemproperties[$property->key]:null, [
                        'class' => 'form-control selectbox',
                        'id'=>'pid_'.$index,
                        $is_required,
                        'data-index'=>$index,
                        'disabled'
                    ])
                }}
            </div>
        </div>
    @endif

    @if($property->field_type == 'dropdown&checkbox')
        <div class="form-row">
            <label class="form-control-label col-md-4">{{ $property->key }} {!! $req_span !!}</label>
            <div class="col-md-6 form-group">
                {{ Form::text('value_'.$index.'[]', isset($itemproperties[$property->key])?$itemproperties[$property->key]:null, [
                        'class' => 'form-control multiselect',
                        'id'=>'pid_'.$index,
                        'multiple'=>'multiple',
                        'data-index'=>$index,
                        'disabled'
                    ])
                }}
            </div>
        </div>
    @endif

@endforeach

