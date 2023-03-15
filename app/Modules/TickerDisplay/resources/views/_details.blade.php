<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Main Title') }} <span style="color:red;">*</span></label>
        {{ Form::text('title', $ticker_display->title ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Sub Title') }} <span style="color:red;">*</span></label>
        {{ Form::textarea('description', $ticker_display->description ?? null, [
                'class' => 'form-control',
                'rows' => 5,
                'disabled'
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Link') }}</label>
        {{ Form::text('link', $ticker_display->link ?? null, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
</div>