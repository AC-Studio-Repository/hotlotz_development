<?php

Breadcrumbs::register('document.documents.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Document'), route('document.documents.index'));
});

Breadcrumbs::register('document.documents.show', function ($breadcrumbs, $document) {
    $breadcrumbs->parent('document.documents.index');
    $breadcrumbs->push(__(':name', ['name' => $document->title]));
});

Breadcrumbs::register('document.documents.edit', function ($breadcrumbs, $document) {
    if($document->document_type != null){
        $breadcrumbs->parent('document.documents.get_documents', $document->document_type);
    }
    else {
        $breadcrumbs->parent('document.documents.index');
    }
    $breadcrumbs->push(__($document->title));
    $breadcrumbs->push(__('Edit'), route('document.documents.edit', $document));
});

Breadcrumbs::register('document.documents.create', function ($breadcrumbs) {
    $breadcrumbs->parent('document.documents.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('document.documents.get_documents', function ($breadcrumbs, $doc_type) {
    $breadcrumbs->parent('document.documents.index');
    $breadcrumbs->push(__(ucfirst($doc_type)), route('document.documents.get_documents', $doc_type));
});

Breadcrumbs::register('document.documents.create_document', function ($breadcrumbs, $doc_type) {
    $breadcrumbs->parent('document.documents.get_documents', $doc_type);
    $breadcrumbs->push(__('Create'));
});

// Breadcrumbs::register('document.documents.edit_document', function ($breadcrumbs, $document, $doc_type) {
//     $breadcrumbs->parent('document.documents.get_documents', $doc_type);
//     $breadcrumbs->push(__($document->title));
//     $breadcrumbs->push(__('Edit'), route('document.documents.edit_document', [$document, $doc_type]));
// });

?>
