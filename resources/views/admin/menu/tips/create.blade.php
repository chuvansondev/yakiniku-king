@include('admin.menu.partials.resource-form-layout', [
    'title' => 'Thêm bí kíp',
    'action' => route('admin.menu.tips.store'),
    'method' => 'POST',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => null,
        'contentLabel' => 'Nội dung bí kíp',
        'statusLabel' => 'Hiển thị bí kíp',
    ],
    'submitLabel' => 'Lưu bí kíp',
    'backUrl' => route('admin.menu.tips.index'),
])
