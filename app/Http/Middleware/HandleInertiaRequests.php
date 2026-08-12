<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $currency = session('currency', config('currencies.default'));
        $locale = session('locale', app()->getLocale());

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user()
                    ? $request->user()->only('id', 'name', 'email', 'role', 'avatar_path')
                    : null,
            ],
            'user' => $request->user()
                ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'avatar_url' => $request->user()->avatar_url,
                    'role' => $request->user()->role,
                ]
                : null,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
            'locale' => $locale,
            'currency' => $currency,
            'currencySymbol' => config('currencies.symbols.' . $currency, '$'),
        ];
    }
}
