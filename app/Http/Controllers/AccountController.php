<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\User;
use App\Services\AccountProfileService;
use App\Services\UserAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        private AccountProfileService $accountProfile,
        private UserAuditLogger $auditLogger,
    ) {}

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

        $changedFields = $this->accountProfile->update($user, $request->validated(), $request->file('profile_photo'));

        $this->auditLogger->record(
            $user,
            'Profile Updated',
            'Account',
            $changedFields === []
                ? 'User saved account details with no visible changes.'
                : 'User updated account fields: '.implode(', ', $changedFields).'.',
        );

        return redirect()
            ->route('account')
            ->with('status', 'Your account details were updated.');
    }
}
