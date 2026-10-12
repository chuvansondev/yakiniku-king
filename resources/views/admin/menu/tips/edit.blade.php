@include('admin.menu.partials.resource-form-layout', [
    'title' => __('Sửa bí kíp'),
    'action' => route('admin.menu.tips.update', $tip),
    'method' => 'PUT',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => $tip,
        'contentLabel' => __('Nội dung bí kíp'),
        'statusLabel' => __('Hiển thị bí kíp'),
    ],
    'submitLabel' => __('Cập nhật bí kíp'),
    'backUrl' => route('admin.menu.tips.index'),
])
