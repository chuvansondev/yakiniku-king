@include('admin.menu.partials.resource-form-layout', [
    'title' => __('Thêm bí kíp'),
    'action' => route('admin.menu.tips.store'),
    'method' => 'POST',
    'fieldsComponent' => 'admin.menu.partials.article-form',
    'fieldsData' => [
        'article' => null,
        'contentLabel' => __('Nội dung bí kíp'),
        'statusLabel' => __('Hiển thị bí kíp'),
    ],
    'submitLabel' => __('Lưu bí kíp'),
    'backUrl' => route('admin.menu.tips.index'),
])
