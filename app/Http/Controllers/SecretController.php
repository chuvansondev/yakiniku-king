<?php

namespace App\Http\Controllers;

use App\Enums\ArticleType;
use App\Services\SecretService;
use Illuminate\View\View;

class SecretController extends Controller
{
    public function recipes(SecretService $secretService): View
    {
        return view('frontend.secret.index', [
            'pageTitle' => __('Công thức'),
            'pageDescription' => __('Khám phá những công thức ngon để thưởng thức cùng thịt nướng.'),
            'articles' => $secretService->recipes(),
            'articleType' => ArticleType::Recipe,
        ]);
    }

    public function tips(SecretService $secretService): View
    {
        return view('frontend.secret.index', [
            'pageTitle' => __('Bí kíp ăn ngon'),
            'pageDescription' => __('Bí quyết thưởng thức thịt nướng trọn vị hơn.'),
            'articles' => $secretService->tips(),
            'articleType' => ArticleType::Tip,
        ]);
    }

    public function recipe(string $recipe, SecretService $secretService): View
    {
        $recipe = $secretService->recipe($recipe);

        return view('frontend.secret.show', [
            'article' => $recipe,
            'pageTitle' => localized_text($recipe, 'title'),
        ]);
    }

    public function tip(string $tip, SecretService $secretService): View
    {
        $tip = $secretService->tip($tip);

        return view('frontend.secret.show', [
            'article' => $tip,
            'pageTitle' => localized_text($tip, 'title'),
        ]);
    }

}
