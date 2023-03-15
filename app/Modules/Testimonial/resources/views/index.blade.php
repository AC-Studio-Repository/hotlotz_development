@extends('appshell::layouts.default')

@section('title')
    {{ __('Testimonial') }}
@stop

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-danger">
        {{ Session::get('message') }}
        </div>
    @endif

    <!-- @if (Session::has('delete'))
        <div class="alert alert-success">
        {{ Session::get('delete') }}
        </div>
    @endif -->

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <a href="{{ route('testimonial.testimonials.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Testimonial') }}
                </a>
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Quote') }}</th>
                    <th>{{ __('Author Name') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($testimonials as $testimonial)
                    <tr>
                        <td>
                            @can('view content managements')
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('testimonial.testimonials.show', $testimonial) }}">{{ $testimonial->quote }}</a>
                            </span>
                            @else
                                <span class="text-muted">
                                    {{ $testimonial->quote }}
                                </span>
                            @endcan
                        </td>
                        <td>
                            @can('view content managements')
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('testimonial.testimonials.show', $testimonial) }}">{{ $testimonial->author }}</a>
                            </span>
                             @else
                                <span class="text-muted">
                                    {{ $testimonial->author }}
                                </span>
                            @endcan
                        </td>
                        <td>
                            <div class="mt-2">
                                    <a href="{{route('testimonial.testimonials.edit',$testimonial)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>

                                    {!! Form::open(['route' => ['testimonial.testimonials.destroy',$testimonial->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' =>  __('Are you sure to delete this testimonial :name?', ['name' => $testimonial->name])
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
            @if($testimonials->hasPages())
                <hr>
                <nav>
                    {{ $testimonials->links() }}
                </nav>
            @endif
        </div>
    </div>

@stop
