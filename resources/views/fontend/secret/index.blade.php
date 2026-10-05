@extends('fontend.layouts.app')

@section('title', $pageTitle)

@push('styles')
    <style>
        .secret-page-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #f6eee4 0%, #fbf8f4 100%);
            border: 1px solid rgba(96, 70, 54, 0.08);
            box-shadow: 0 18px 38px rgba(36, 28, 20, 0.05);
        }

        .secret-page-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(185, 118, 61, 0.16), transparent 32%);
            pointer-events: none;
        }

        .secret-page-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.8rem;
            border-radius: 999px;
            background: rgba(18, 18, 18, 0.92);
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .secret-page-panel {
            position: relative;
            min-height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            border-radius: 1.05rem;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(96, 70, 54, 0.08);
        }

        .secret-page-panel-inner {
            width: 100%;
            max-width: 300px;
            padding: 1rem 1.15rem;
            background: rgba(255, 255, 255, 0.9);
            border-left: 4px solid #b36d2b;
            border-radius: 0.8rem;
        }

        .secret-page-kicker {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #8f5d35;
        }

        .secret-page-text {
            margin: 0.7rem 0 0;
            line-height: 1.6;
            font-weight: 600;
            color: #2b2521;
        }

        .secret-tip-row {
            padding-block: 1.5rem;
            border-bottom: 1px solid rgba(96, 70, 54, 0.12);
        }

        .secret-tip-image {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            border-radius: 1rem;
        }

        .secret-tip-content {
            color: #51463e;
            line-height: 1.8;
            white-space: normal;
        }

        .secret-tip-content p:last-child {
            margin-bottom: 0;
        }

        .secret-recipes-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 310px), 1fr));
            gap: 1.5rem;
        }

        .secret-recipe-card {
            overflow: hidden;
            height: 100%;
            border: 1px solid rgba(96, 70, 54, 0.1);
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 12px 30px rgba(36, 28, 20, 0.07);
            transition: transform 180ms ease, box-shadow 180ms ease;
        }

        .secret-recipe-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 38px rgba(36, 28, 20, 0.12);
        }

        .secret-recipe-image,
        .secret-recipe-placeholder {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: #f5f0e9;
        }

        .secret-recipe-image-button {
            display: block;
            width: 100%;
            padding: 0;
            border: 0;
            background: transparent;
        }

        .secret-recipe-image-button:focus-visible,
        .secret-recipe-link:focus-visible {
            outline: 3px solid #b36d2b;
            outline-offset: 4px;
        }

        .secret-recipe-image {
            object-fit: cover;
            object-position: center 42%;
            transition: transform 300ms ease;
        }

        .secret-recipe-image-button {
            overflow: hidden;
        }

        .secret-recipe-card:hover .secret-recipe-image {
            transform: scale(1.035);
        }

        .secret-recipe-modal-image {
            display: block;
            width: 100%;
            max-height: min(60vh, 620px);
            margin-bottom: 1.5rem;
            border-radius: 1rem;
            background: #f5f0e9;
            object-fit: contain;
        }

        .secret-recipe-placeholder {
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #f6eee4, #ead7c1);
            color: #8f5d35;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 1.25rem;
        }

        .secret-recipe-body {
            display: flex;
            min-height: 220px;
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .secret-recipe-description {
            display: -webkit-box;
            overflow: hidden;
            color: #6b625b;
            line-height: 1.7;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        .secret-recipe-link {
            margin-top: auto;
            color: #8b4b20;
            font-weight: 700;
            text-decoration: none;
        }

        .secret-recipe-link:hover,
        .secret-recipe-link:focus-visible {
            color: #542b13;
            text-decoration: underline;
        }

        @media (prefers-reduced-motion: reduce) {
            .secret-recipe-card {
                transition: none;
            }

            .secret-recipe-card:hover {
                transform: none;
            }

            .secret-recipe-image {
                transition: none;
            }

            .secret-recipe-card:hover .secret-recipe-image {
                transform: none;
            }
        }
    </style>
@endpush

@section('content')
    <section class="container py-5">
        <section class="secret-page-hero p-4 p-md-5 mb-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="secret-page-badge">Yakiniku King</span>
                    <h1 class="display-6 fw-bold mt-3 mb-3">{{ $pageTitle }}</h1>
                    <p class="lead text-muted mb-0">{{ $pageDescription }}</p>
                </div>
                <!--<div class="col-lg-5">
                    <div class="secret-page-panel">
                        <div class="secret-page-panel-inner">
                            <span class="secret-page-kicker">
                                @if ($articleType === \App\Enums\ArticleType::Recipe)
                                    {{ __('Công thức') }}
                                @else
                                    {{ __('Bí kíp') }}
                                @endif
                            </span>
                            <p class="secret-page-text">
                                @if ($articleType === \App\Enums\ArticleType::Recipe)
                                    {{ __('Tối ưu vị giác, phong cách nấu và hương vị hoàn hảo cho bữa ăn của bạn.') }}
                                @else
                                    {{ __('Mẹo hay giúp thưởng thức thịt nướng ngon hơn, chuẩn vị và dễ làm.') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>-->
            </div>
        </section>

        <div class="{{ $articleType === \App\Enums\ArticleType::Tip ? 'secret-tips-list' : 'secret-recipes-list' }}">
            @forelse ($articles as $article)
                @if ($articleType === \App\Enums\ArticleType::Tip)
                    <article class="row align-items-center g-4 g-lg-5 secret-tip-row">
                        <div class="col-md-6 {{ $loop->even ? 'order-md-2' : '' }}">
                            @if ($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="secret-tip-image" alt="{{ localized_text($article, 'title') }}" loading="lazy">
                            @endif
                        </div>
                        <div class="col-md-6 {{ $loop->even ? 'order-md-1' : '' }}">
                            <h2 class="h3 fw-bold mb-3">{{ localized_text($article, 'title') }}</h2>
                            @if ($article->published_at)
                                <p class="small text-muted mb-3">{{ $article->published_at->format('d/m/Y') }}</p>
                            @endif
                            @if (localized_text($article, 'short_description'))
                                <p class="lead">{{ localized_text($article, 'short_description') }}</p>
                            @endif
                            <div class="secret-tip-content">{!! nl2br(e(localized_text($article, 'content'))) !!}</div>
                        </div>
                    </article>
                @else
                    <article class="secret-recipe-card">
                        <button type="button" class="secret-recipe-image-button" data-bs-toggle="modal" data-bs-target="#recipe-modal-{{ $article->getKey() }}" aria-label="{{ __('Xem công thức: :title', ['title' => localized_text($article, 'title')]) }}">
                            @if ($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="secret-recipe-image" alt="{{ localized_text($article, 'title') }}" loading="lazy">
                            @else
                                <span class="secret-recipe-placeholder" aria-hidden="true">Yakiniku King</span>
                            @endif
                        </button>

                        <div class="secret-recipe-body">
                            @if ($article->published_at)
                                <p class="small text-uppercase text-muted mb-2">{{ $article->published_at->format('d/m/Y') }}</p>
                            @endif
                            <h2 class="h4 fw-bold mb-3">{{ localized_text($article, 'title') }}</h2>
                            @if (localized_text($article, 'short_description'))
                                <p class="secret-recipe-description mb-4">{{ localized_text($article, 'short_description') }}</p>
                            @endif
                            <button class="secret-recipe-link border-0 bg-transparent p-0" type="button" data-bs-toggle="modal" data-bs-target="#recipe-modal-{{ $article->getKey() }}">
                                {{ __('Xem công thức') }} <span aria-hidden="true">&rarr;</span>
                            </button>
                        </div>
                    </article>

                    <div class="modal fade" id="recipe-modal-{{ $article->getKey() }}" tabindex="-1" aria-labelledby="recipe-modal-title-{{ $article->getKey() }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content border-0 rounded-4 overflow-hidden">
                                <div class="modal-header">
                                    <h2 class="modal-title fs-4 fw-bold" id="recipe-modal-title-{{ $article->getKey() }}">{{ localized_text($article, 'title') }}</h2>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Đóng') }}"></button>
                                </div>
                                <div class="modal-body p-4 p-md-5">
                                    @if ($article->published_at)
                                        <p class="small text-muted">{{ $article->published_at->format('d/m/Y') }}</p>
                                    @endif
                                    @if ($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" class="secret-recipe-modal-image" alt="{{ localized_text($article, 'title') }}">
                                    @endif
                                    @if (localized_text($article, 'short_description'))
                                        <p class="lead">{{ localized_text($article, 'short_description') }}</p>
                                    @endif
                                    <div class="secret-tip-content">{!! nl2br(e(localized_text($article, 'content'))) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12">
                    <p class="text-muted">{{ __('Nội dung đang được cập nhật.') }}</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
