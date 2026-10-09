@include('admin.menu.partials.resource-form-layout', [
    'title' => 'Sửa bí kíp',
    'action' => route('admin.menu.tips.update', $tip),
    'method' => 'PUT',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => $tip,
        'contentLabel' => 'Nội dung bí kíp',
        'statusLabel' => 'Hiển thị bí kíp',
    ],
    'submitLabel' => 'Cập nhật bí kíp',
    'backUrl' => route('admin.menu.tips.index'),
])
