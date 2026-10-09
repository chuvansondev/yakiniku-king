@extends('frontend.layouts.app')

@section('title', $pageTitle)

@section('content')
    <article class="container py-5">
        <a class="text-danger text-decoration-none" href="{{ url()->previous() }}">&larr; {{ __('Quay lại') }}</a>

        <div class="mt-4" style="max-width: 850px;">
            <h1>{{ localized_text($article, 'title') }}</h1>

            @if ($article->published_at)
                <p class="text-muted">{{ $article->published_at->format('d/m/Y') }}</p>
            @endif

            @if ($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" class="img-fluid rounded mb-4" alt="{{ localized_text($article, 'title') }}">
            @endif

            @if (localized_text($article, 'short_description'))
                <p class="lead">{{ localized_text($article, 'short_description') }}</p>
            @endif

            <div>{!! nl2br(e(localized_text($article, 'content'))) !!}</div>
        </div>
    </article>
@endsection