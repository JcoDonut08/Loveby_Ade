<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\User;
use App\Services\AccountProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(private AccountProfileService $accountProfile) {}

    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('pages.account', [
            'user' => $user,
            'profilePhotoUrl' => $user->profilePhotoUrl(),
        ]);
    }

    public function update(UpdateAccountRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->accountProfile->update($user, $request->validated(), $request->file('profile_photo'));

        return redirect()
            ->route('account')
            ->with('status', 'Your account details were updated.');
    }
}
