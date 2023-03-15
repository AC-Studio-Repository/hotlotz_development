<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Email Title') }}</label>
        {{ Form::text('title', old('title', isset($email_template->title)?$email_template->title:null), [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                'placeholder' => __('Email Title'),
                'id'=>'title',
                
            ])
        }}

        @if ($errors->has('title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Email Description') }}</label>
        {{ Form::textarea('description', old('description', isset($email_template->description)?$email_template->description:null), [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                'placeholder' => __('Email Description'),
                'id'=>'description',
                'rows' => 5
            ])
        }}

        @if ($errors->has('description'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <textarea name="content" id="summernote">{{ $email_template->content }}</textarea>
    </div>
</div>