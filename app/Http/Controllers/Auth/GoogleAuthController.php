<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\FavoriteService;
use App\Services\GoogleAccountService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class GoogleAuthController extends Controller
{
    private CartService $cart;

    private FavoriteService $favorites;

    private GoogleAccountService $googleAccounts;

    public function __construct(
        CartService $cart,
        FavoriteService $favorites,
        GoogleAccountService $googleAccounts
    ) {
        $this->cart = $cart;
        $this->favorites = $favorites;
        $this->googleAccounts = $googleAccounts;
    }

    public function redirect(Request $request): RedirectResponse
    {
        return $this->googleProvider()
            ->with([
                'prompt' => 'select_account',
            ])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()
                ->route('login')
                ->with('status', 'Google sign in was cancelled. Please try again.');
        }

        try {
            $googleUser = $this->googleProvider()->user();
        } catch (InvalidStateException $e) {
            Log::warning('Google OAuth invalid state.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->with('status', 'Your Google sign in session expired. Please try again from the same browser tab.');
        } catch (ConnectException $e) {
            Log::error('Google OAuth connection failed.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->with('status', 'Google sign in could not reach Google. Please check your internet connection and restart the dev server.');
        } catch (RequestException $e) {
            $responseBody = $e->hasResponse()
                ? (string) $e->getResponse()->getBody()
                : null;

            Log::error('Google OAuth request failed.', [
                'message' => $e->getMessage(),
                'response' => $responseBody,
                'redirect_uri' => config('services.google.redirect'),
                'client_id' => config('services.google.client_id'),
            ]);

            return redirect()
                ->route('login')
                ->with('status', 'Google sign in failed. Please check your Google OAuth client ID, secret, and redirect URI.');
        } catch (Throwable $e) {
            Log::error('Google OAuth unexpected error.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->with('status', 'Google sign in failed. Please try again.');
        }

        $user = $this->googleAccounts->findOrCreateUser($googleUser);

        Auth::login($user);

        $request->session()->regenerate();

        $this->cart->mergeSessionIntoUser($request->session(), $user);
        $this->favorites->mergeSessionIntoUser($request->session(), $user);

        return redirect()->intended(route('home'));
    }

    private function googleProvider()
    {
        $this->disableProxyEnvironment();

        /** @var GoogleProvider $provider */
        $provider = Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'));

        if (method_exists($provider, 'setHttpClient')) {
            $provider->setHttpClient(new Client([
                'timeout' => 15,
                'connect_timeout' => 10,
                'proxy' => '',
                'curl' => [
                    CURLOPT_PROXY => '',
                    CURLOPT_NOPROXY => '*',
                ],
            ]));
        }

        return $provider;
    }

    private function disableProxyEnvironment(): void
    {
        foreach ([
            'HTTP_PROXY',
            'HTTPS_PROXY',
            'ALL_PROXY',
            'http_proxy',
            'https_proxy',
            'all_proxy',
        ] as $key) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        }

        putenv('NO_PROXY=*');
        $_ENV['NO_PROXY'] = '*';
        $_SERVER['NO_PROXY'] = '*';
    }
}
