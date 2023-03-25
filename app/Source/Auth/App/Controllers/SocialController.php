<?php

declare(strict_types=1);

namespace App\Source\Auth\App\Controllers;

use App\Http\Controllers\Controller;
use App\Source\Auth\App\Requests\SocialDriverRequest;
use App\Source\Auth\Domain\SocialLogin\SocialLoginBusinessLogic;
use App\Source\Auth\Domain\SocialLoginScopes\SocialLoginScopesBusinessLogic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect(
        SocialLoginScopesBusinessLogic $socialLoginScopesBusinessLogic,
        SocialDriverRequest $request,
        string $driver
    ): RedirectResponse {
        $scopes = $socialLoginScopesBusinessLogic->getScopes($driver);
        $socialite = Socialite::driver($driver);
        if ([] === $scopes) {
            return $socialite->redirect();
        }
        return $socialite
            ->scopes($scopes)
            ->redirect();
    }

    public function callback(
        string $driver,
        SocialDriverRequest $request,
        SocialLoginBusinessLogic $socialLoginBusinessLogic
    ): RedirectResponse {
        $user = $socialLoginBusinessLogic->login($driver);
        Auth::login($user, true);

        return redirect('/');
    }
}
