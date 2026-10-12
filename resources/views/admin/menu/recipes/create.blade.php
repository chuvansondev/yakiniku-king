@include('admin.menu.partials.resource-form-layout', [
    'title' => __('Thêm công thức'),
    'action' => route('admin.menu.recipes.store'),
    'method' => 'POST',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => null,
        'contentLabel' => __('Nội dung công thức'),
        'statusLabel' => __('Hiển thị công thức'),
    ],
    'submitLabel' => __('Lưu công thức'),
    'backUrl' => route('admin.menu.recipes.index'),
])
