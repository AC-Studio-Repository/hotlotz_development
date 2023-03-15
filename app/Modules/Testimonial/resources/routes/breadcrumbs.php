<?php

Breadcrumbs::register('testimonial.testimonials.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Testimonial'), route('testimonial.testimonials.index'));
});

Breadcrumbs::register('testimonial.testimonials.show', function ($breadcrumbs, $faqcategory) {
    $breadcrumbs->parent('testimonial.testimonials.index');
    $breadcrumbs->push(__(':name', ['name' => $faqcategory->title]), route('testimonial.testimonials.show', $faqcategory));
});

Breadcrumbs::register('testimonial.testimonials.edit', function ($breadcrumbs, $faqcategory) {
    $breadcrumbs->parent('testimonial.testimonials.show', $faqcategory);
    $breadcrumbs->push(__('Edit'), route('testimonial.testimonials.edit', $faqcategory));
});

Breadcrumbs::register('testimonial.testimonials.create', function ($breadcrumbs) {
    $breadcrumbs->parent('testimonial.testimonials.index');
    $breadcrumbs->push(__('Create'));
});