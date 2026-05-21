<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendPromotionEmailRequest;
use App\Http\Requests\Admin\StorePromotionRequest;
use App\Http\Requests\Admin\UpdatePromotionRequest;
use App\Mail\PromotionCodeMail;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.promotions', [
            'promotions' => Promotion::query()
                ->withCount('orders')
                ->withSum('orders', 'total')
                ->latest()
                ->get(),
        ]);
    }

    public function store(StorePromotionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($data['kind'] === Promotion::KIND_AD) {
            $data['code'] = $this->makeAdCode();
            $data['discount_type'] = Promotion::DISCOUNT_FIXED;
            $data['discount_value'] = 0;
            $data['announcement_title'] = null;
            $data['announcement_body'] = null;
            $data['announcement_cta'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('promotions', 'public');
        }

        unset($data['image']);

        Promotion::query()->create($data);

        return redirect()
            ->route('admin.promotions')
            ->with('status', 'Promotion created.');
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $promotion->update($data);

        return redirect()
            ->route('admin.promotions')
            ->with('status', 'Promotion schedule updated.');
    }

    public function toggle(Promotion $promotion): RedirectResponse
    {
        $promotion->forceFill([
            'is_active' => ! $promotion->is_active,
        ])->save();

        return redirect()
            ->route('admin.promotions')
            ->with('status', 'Promotion status updated.');
    }

    public function email(SendPromotionEmailRequest $request, Promotion $promotion): RedirectResponse
    {
        if ($promotion->kind !== Promotion::KIND_DISCOUNT) {
            abort(404);
        }

        Mail::to($request->validated('email'))->send(new PromotionCodeMail($promotion));

        return redirect()
            ->route('admin.promotions')
            ->with('status', 'Promo code sent to '.$request->validated('email').'.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        if ($promotion->image_path !== null) {
            Storage::disk('public')->delete($promotion->image_path);
        }

        $promotion->delete();

        return redirect()
            ->route('admin.promotions')
            ->with('status', 'Promotion deleted.');
    }

    private function makeAdCode(): string
    {
        do {
            $code = 'AD-'.Str::upper(Str::random(8));
        } while (Promotion::query()->where('code', $code)->exists());

        return $code;
    }
}
