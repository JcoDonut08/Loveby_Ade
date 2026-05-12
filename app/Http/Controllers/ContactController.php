<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Models\User;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(private ContactMessageService $contactMessages) {}

    public function index(): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        return view('pages.contact', [
            'adminEmail' => (string) config('mail.from.address'),
            'contactUserName' => $user?->name,
            'contactUserEmail' => $user?->email,
            'contactUsesAuthenticatedUser' => $user instanceof User,
        ]);
    }

    public function store(ContactMessageRequest $request): RedirectResponse
    {
        $this->contactMessages->send($request->validated());

        return redirect()
            ->route('contact')
            ->with('status', 'Your message was sent to the admin.');
    }
}
