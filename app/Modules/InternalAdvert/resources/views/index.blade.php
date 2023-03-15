@extends('appshell::layouts.default')

@section('title')
    {{ __('Internal Advert') }}
@stop

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-danger">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            <div class="card-actionbar">
                <a href="{{ route('internal_advert.internal_advert.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create New Advert') }}
                </a>
            </div>
        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Tagline') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th style="width: 10%">&nbsp;</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($internal_adverts as $internal_advert)
                        <tr>
                            <td>

                            @can('view content managements')
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('internal_advert.internal_advert.detail', $internal_advert) }}">
                                    {{ $internal_advert->name }}
                                </a>
                            </span>
                            @else
                                <span class="font-lg mb-3 font-weight-bold">
                                    {{ $internal_advert->name }}
                                </span>
                            @endcan
                            </td>
                            <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $internal_advert->description }}
                            </span>
                            </td>
                            <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $internal_advert->url }}
                            </span>
                            </td>
                            <td>
                                <div class="mt-2">
                                    <a href="{{route('internal_advert.internal_advert.edit',$internal_advert)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>

                                    {!! Form::open(['route' => ['internal_advert.internal_advert.destroy',$internal_advert],
                                                    'method' => 'DELETE',
                                                    'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $internal_advert->name])
                                                    ])
                                            !!}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>

@stop

@section('scripts')

    <!-- Parsley CSS -->
    <link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
    <!-- Parsley JS -->
    <script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

@stop
