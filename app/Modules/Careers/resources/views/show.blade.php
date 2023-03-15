@extends('appshell::layouts.default')

@section('title')
    {{ __('Careers Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ __('Careers') }}
                @slot('subtitle')
                    {{ __('Position') }}
                    {{ $career->position }}
                @endslot
                @slot('subtitle')
                    {{ __('Experience Level') }}
                    {{ $career->expreience_level }}
                @endslot
                @slot('subtitle')
                    {{ __('Post') }}
                    {{ $career->posts }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-countdown',
                    'type' => null
            ])
                
                @slot('subtitle')
                    {{ __('Created since') }}
                    {{ $career->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('careers.careerss.edit', $career) }}" class="btn btn-outline-primary">{{ __('Edit Careers')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['careers.careerss.destroy', $career],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Careers') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ $career->Position }}
            </label>
            <label class="control-label col-md-6">
                {{ $career->expreience_level }}
            </label>
            <label class="control-label col-md-6">
                {{ $career->posts }}
            </label>
            <label class="control-label col-md-6">
                <a href="{{ $career->file_path }}">Uploaded Document</a>
            </label>
        </div>
    </div>
@stop
