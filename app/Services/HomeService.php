<?php

namespace App\Services;

use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

class HomeService
{
    public function __construct(private readonly BannerRepositoryInterface $banners) {}

    public function banners(): Collection
    {
        $banners = PublicDataCache::remember(PublicDataCache::BANNERS, 'homepage', fn () => $this->banners->homepageBanners());
        $banners = clone $banners;
        $banners->transform(fn (Banner $banner) => $banner->setAttribute('video_embed_url', $this->youtubeEmbedUrl($banner->video_url)));

        return $banners;
    }

    private function youtubeEmbedUrl(?string $url): ?string
    {
        if (! $url) return null;
        $parsedUrl = parse_url($url);
        if (! is_array($parsedUrl)) return null;

        $host = strtolower($parsedUrl['host'] ?? '');
        $path = $parsedUrl['path'] ?? '';
        $videoId = null;

        if ($host === 'youtu.be') {
            $videoId = explode('/', trim($path, '/'))[0] ?? null;
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            parse_str($parsedUrl['query'] ?? '', $query);
            if ($path === '/watch') $videoId = $query['v'] ?? null;
            elseif (preg_match('#^/(?:embed|shorts|live)/([^/]+)#', $path, $matches) === 1) $videoId = $matches[1];
        }

        if (! is_string($videoId) || preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) !== 1) return null;

        return 'https://www.youtube-nocookie.com/embed/'.$videoId.'?autoplay=1&mute=1&loop=1&playlist='.$videoId.'&playsinline=1&controls=0&rel=0&enablejsapi=1';
    }
}
