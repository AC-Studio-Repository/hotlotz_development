@extends('appshell::layouts.default')

@section('title')
    {{ __('Add New Case Study') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        {!! Form::model($case_study, ['route' => 'case_study.case_study.store']) !!}

        <div class="card-block">
            <div class="container">

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Name') }}</label>

                        {{ Form::text('name', "", [
                                'class' => 'form-control form-control-md' . ($errors->has('name') ? ' is-invalid' : ''),
                                'required',
                                'id' => 'name',
                                'placeholder' => __('Name')
                            ])
                        }}

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif

                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Abstract') }}</label>
                        {{ Form::textarea('abstract', "", [
                                    'class' => 'form-control form-control-md' . ($errors->has('description') ? ' is-invalid' : ''),
                                    'required',
                                    'id' => 'abstract',
                                    'placeholder' => __('Abstract')
                                ])
                            }}

                        @if ($errors->has('abstract'))
                            <div class="invalid-feedback">{{ $errors->first('abstract') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Link') }}</label>
                        {{ Form::text('link', "", [
                                    'class' => 'form-control form-control-md' . ($errors->has('url') ? ' is-invalid' : ''),
                                    'required',
                                    'id' => 'link',
                                    'placeholder' => __('Link')
                                ])
                            }}

                        @if ($errors->has('link'))
                            <div class="invalid-feedback">{{ $errors->first('link') }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
            <a href="{{route('case_study.case_study.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
    {!! Form::close() !!}
@stop
