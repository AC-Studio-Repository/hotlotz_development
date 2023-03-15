<?php

Breadcrumbs::register('blog_article.blog_articles.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.bloglist');
    $breadcrumbs->push(__('Press Coverage'), route('blog_article.blog_articles.index'));
});

Breadcrumbs::register('blog_article.blog_articles.show', function ($breadcrumbs, $blog_article) {
    $breadcrumbs->parent('blog_article.blog_articles.index');
    $breadcrumbs->push(__(':name', ['name' => $blog_article->title]), route('blog_article.blog_articles.show', $blog_article));
});

Breadcrumbs::register('blog_article.blog_articles.edit', function ($breadcrumbs, $blog_article) {
    $breadcrumbs->parent('blog_article.blog_articles.show', $blog_article);
    $breadcrumbs->push(__('Edit'), route('blog_article.blog_articles.edit', $blog_article));
});

Breadcrumbs::register('blog_article.blog_articles.create', function ($breadcrumbs) {
    $breadcrumbs->parent('blog_article.blog_articles.index');
    $breadcrumbs->push(__('Create'));
});