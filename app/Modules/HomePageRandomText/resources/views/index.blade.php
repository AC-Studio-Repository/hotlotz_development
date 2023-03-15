@extends('appshell::layouts.default')

@section('title')
    {{ __('Ticker Display') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('home_page_random_text.home_page_random_texts.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Ticker Display') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($random_texts as $random_text)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('home_page_random_text.home_page_random_texts.show', $random_text) }}">{{ $random_text->title }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                               {{ $random_text->description }}
                            </span>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('home_page_random_text.home_page_random_texts.edit',$random_text)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['home_page_random_text.home_page_random_texts.destroy',$random_text->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $random_text->title])
                                                ])
                                        !!}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
