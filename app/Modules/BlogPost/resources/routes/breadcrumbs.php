<?php

Breadcrumbs::register('blog_post.blog_posts.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.bloglist');
    $breadcrumbs->push(__('Blog Posts'), route('blog_post.blog_posts.index'));
});

Breadcrumbs::register('blog_post.blog_posts.show', function ($breadcrumbs, $blog_post) {
    $breadcrumbs->parent('blog_post.blog_posts.index');
    $breadcrumbs->push(__(':name', ['name' => $blog_post->title]), route('blog_post.blog_posts.show', $blog_post));
});

Breadcrumbs::register('blog_post.blog_posts.edit', function ($breadcrumbs, $blog_post) {
    $breadcrumbs->parent('blog_post.blog_posts.show', $blog_post);
    $breadcrumbs->push(__('Edit'), route('blog_post.blog_posts.edit', $blog_post));
});

Breadcrumbs::register('blog_post.blog_posts.create', function ($breadcrumbs) {
    $breadcrumbs->parent('blog_post.blog_posts.index');
    $breadcrumbs->push(__('Create'));
});
