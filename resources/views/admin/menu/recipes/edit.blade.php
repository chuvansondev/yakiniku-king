@include('admin.menu.partials.resource-form-layout', [
    'title' => __('Sửa công thức'),
    'action' => route('admin.menu.recipes.update', $recipe),
    'method' => 'PUT',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => $recipe,
        'contentLabel' => __('Nội dung công thức'),
        'statusLabel' => __('Hiển thị công thức'),
    ],
    'submitLabel' => __('Cập nhật công thức'),
    'backUrl' => route('admin.menu.recipes.index'),
])
