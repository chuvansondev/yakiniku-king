<div
    data-vue-banner-form
    data-image-type="{{ \App\Enums\BannerType::Image->value }}"
    data-video-type="{{ \App\Enums\BannerType::Video->value }}"
    data-storage-url="{{ asset('storage') }}"
    data-values="{{ json_encode([
        'title' => old('title', $banner->title ?? ''),
        'title_en' => old('title_en', $banner->title_en ?? ''),
        'type' => old('type', $banner->type ?? \App\Enums\BannerType::Image->value),
        'image' => $banner->image ?? '',
        'video_url' => old('video_url', $banner->video_url ?? ''),
        'link' => old('link', $banner->link ?? ''),
        'sort_order' => old('sort_order', $banner->sort_order ?? 0),
        'status' => (bool) old('status', $banner->status ?? true),
    ]) }}"
></div>
