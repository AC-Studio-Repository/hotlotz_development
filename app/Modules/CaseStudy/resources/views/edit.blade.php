@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $case_study->name }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        {!! Form::model(['route' => 'case_study.case_study.store', 'method' => 'POST']) !!}
        <div class="card-block">
            <div class="container">

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Name') }}</label>

                        {{ Form::text('name', $case_study->name, [
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
                        {{ Form::textarea('abstract', $case_study->abstract, [
                                    'class' => 'form-control form-control-md' . ($errors->has('description') ? ' is-invalid' : ''),
                                    'required',
                                    'id' => 'abstract',
                                    'placeholder' => __('Tagline')
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
                        {{ Form::text('link', $case_study->link, [
                                    'class' => 'form-control form-control-md' . ($errors->has('url') ? ' is-invalid' : ''),
                                    'required',
                                    'id' => 'link',
                                    'placeholder' => __('Link')
                                ])
                            }}

                        @if ($errors->has('link'))
                            <div class="invalid-feedback">{{ $errors->first('url') }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
            <a href="{{route('internal_advert.internal_advert.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
    {!! Form::close() !!}
@stop
