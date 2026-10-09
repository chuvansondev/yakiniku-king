@include('admin.menu.partials.article-identity-fields')
@include('admin.menu.partials.article-description-fields')
@include('admin.menu.partials.image-upload-field', [
    'image' => $article->image ?? null,
    'alt' => $article->title ?? '',
    'showCaption' => true,
])
@include('admin.menu.partials.article-publishing-fields')
