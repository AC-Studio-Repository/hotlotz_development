@extends('appshell::layouts.default')

@section('title')
    {{ __('Media Resource Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ __('Media Resource') }}
                @slot('subtitle')
                    {{ __('Title') }}
                    {{ $media_resource->media_resource }}
                @endslot
                @slot('subtitle')
                    {{ __('Date') }}
                    {{ $media_resource->date }}
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
                    {{ $media_resource->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('media_resource.media_resources.edit', $media_resource) }}" class="btn btn-outline-primary">{{ __('Edit Media Resource')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['media_resource.media_resources.destroy', $media_resource],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Media Resource') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ $media_resource->title }}
            </label>
            <label class="control-label col-md-6">
                {{ $media_resource->display_date }}
            </label>
            <label class="control-label col-md-6">
                <a href="{{ $media_resource->file_path }}">Uploaded Document</a>
            </label>
        </div>
    </div>
@stop
