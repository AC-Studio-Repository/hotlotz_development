@extends('appshell::layouts.default')

@section('title')
    {{ __('Blog Post Details') }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('blog_post.blog_posts.edit', $blog_post) }}" class="btn btn-outline-info">{{ __('Edit Blog Post') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $blog_post->id }}" data-main_title="{{ $blog_post->main_title }}" >{{ __('Delete Blog Post') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('blog_post::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var blog_post_id = $(this).attr('data-id');
            var name = $(this).attr('data-main_title');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/blog_posts/'+blog_post_id,
                    type: 'delete',
                    data: {
                        "id": blog_post_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('blog_post.blog_posts.index')}}";
                            });
                        }else {
                            bootbox.alert(response.message);
                            return false;
                        }
                    }
                });
            }
        });
    });

</script>

@stop
