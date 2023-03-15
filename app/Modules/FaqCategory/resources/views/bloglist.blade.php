@extends('appshell::layouts.default')

@section('title')
    {{ __('Media Coverage') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
            <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('blog_post.blog_posts.index') }}" class="btn btn-outline-primary">{{ __('Blog Posts') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Blog Posts') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('blog_article.blog_articles.index') }}" class="btn btn-outline-primary">{{ __('Articles') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Articles') }}</a></td>
                    @endcan
                </tr>
            </table>
        </div>
    </div>

@stop
