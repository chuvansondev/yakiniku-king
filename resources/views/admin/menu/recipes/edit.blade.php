@include('admin.menu.partials.resource-form-layout', [
    'title' => 'Sửa công thức',
    'action' => route('admin.menu.recipes.update', $recipe),
    'method' => 'PUT',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => $recipe,
        'contentLabel' => 'Nội dung công thức',
        'statusLabel' => 'Hiển thị công thức',
    ],
    'submitLabel' => 'Cập nhật công thức',
    'backUrl' => route('admin.menu.recipes.index'),
])
