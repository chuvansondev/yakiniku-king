@include('admin.menu.partials.resource-form-layout', [
    'title' => 'Thêm công thức',
    'action' => route('admin.menu.recipes.store'),
    'method' => 'POST',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => null,
        'contentLabel' => 'Nội dung công thức',
        'statusLabel' => 'Hiển thị công thức',
    ],
    'submitLabel' => 'Lưu công thức',
    'backUrl' => route('admin.menu.recipes.index'),
])
