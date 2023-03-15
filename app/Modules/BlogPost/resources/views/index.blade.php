@extends('appshell::layouts.default')

@section('title')
    {{ __('Blog Posts') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('blog_post.blog_posts.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Blog Post') }}
            </a>
        </div>
    </div>

    {!! Form::model($blog_post, ['route' => ['blog_post.blog_posts.blog_post_reordering',$blog_post], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="blog_posts" class="list-group col-md-12">
                    @foreach($blog_posts as $key => $blogpost)
                        <div class="blogpost list-item mb-1" data-id="{{ $blogpost->order }}">
                            <img onclick="" lazyload="on" class="list_wh" src="{{ $blogpost->full_path }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="blogpost_id[]" value="{{ $blogpost->id }}" >

                            <span class="blogpost-description">
                                <a href="{{ route('blog_post.blog_posts.show', ['blog_post' => $blogpost ]) }}" target="_blank">{{ $blogpost->title }}</a>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" id="btnBlogPostReorder">{{ __('Update Order') }}</button>
        </div>

    {!! Form::close() !!}
    
</div>
@stop

@section('scripts')

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<style type="text/css">
    .list-item {
        position: relative;
        display: block;
        padding: .75rem 1.25rem;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
    }
    .list_wh {        
        width: 150px;
        height: 150px;
    }
</style>

<script type="text/javascript">
    $(function(){

        var sortable = new Sortable(blog_posts,{
            group: "blog_posts",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop