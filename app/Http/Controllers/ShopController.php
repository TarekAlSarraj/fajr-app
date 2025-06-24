<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;




class ShopController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        // Load all items and map them with purchase info
        $items = Item::all()->map(function ($item) use ($user) {
            $lastPurchase = $user->purchases()->where('item_id', $item->id)->latest()->first();
            $canBuy = true;
            $lockedUntil = $lastPurchase->locked_until ?? null;

            if ($item->is_unique && $lastPurchase) {
                $canBuy = false;
            }

            if ($lockedUntil && now()->lt($lockedUntil)) {
                $canBuy = false;
            }

            return (object)[
                'id'           => $item->id,
                'name'         => $item->name,
                'category'     => $item->category,
                'description'  => $item->description,
                'price'        => $item->price_in_gems,
                'is_unique'    => $item->is_unique,
                'lock_days'    => $item->lock_days,
                'image_path'   => $item->image_path,
                'can_buy'      => $canBuy,
                'locked_until' => $lockedUntil,
            ];
        });

        // Group items by category
        $itemsByCategory = $items->groupBy('category');

        // Reorder: put 'في رحاب الله' first, keep others as they are
        $orderedItemsByCategory = collect();

        if ($itemsByCategory->has('في رحاب الله')) {
            $orderedItemsByCategory->put('في رحاب الله', $itemsByCategory->get('في رحاب الله'));
            $itemsByCategory->forget('في رحاب الله');
        }

        // Merge the rest of the categories after the first one
        $orderedItemsByCategory = $orderedItemsByCategory->merge($itemsByCategory);

        return view('user.shop', [
            'itemsByCategory' => $orderedItemsByCategory,
            'user' => $user
        ]);
    }



    public function buyItem(Item $item)
    {
        $user = auth()->user();

        $lastPurchase = $user->purchases()->where('item_id', $item->id)->latest()->first();

        // Check if unique and already bought
        if ($item->is_unique && $lastPurchase) {
            return back()->with('error', 'لا يمكنك شراء هذا الخيار أكثر من مرّة');
        }

        // Check locked_until directly instead of recalculating
        if ($lastPurchase && $lastPurchase->locked_until && now()->lt($lastPurchase->locked_until)) {
            return back()->with('error', 'عليك الانتظار حتى ' . $lastPurchase->locked_until->translatedFormat('Y-m-d H:i'));
        }

        // Check for enough gems
        if ($user->gems < $item->price_in_gems) {
            return back()->with('error', 'لا تمتلك جواهر كافية لشراء هذا الخيار.');
        }

        DB::transaction(function () use ($user, $item) {
            if($item->name === 'Freeze Streak'){
                $this->freezeStreak();
            }
            $user->decrement('gems', $item->price_in_gems);

            Purchase::create([
                'user_id'      => $user->id,
                'item_id'      => $item->id,
                'purchased_at' => now(),
                'locked_until' => $item->lock_days > 0 ? now()->addDays($item->lock_days) : null,
            ]);
        });

        return back()->with('success', 'تم الشراء بنجاح!');
    }

    private function freezeStreak()
    {
        $user = auth()->user();

        // How many days to freeze? Example: 1 day
        $freezeDays = 1;

        $frozenUntil = now()->addDays($freezeDays);

        $user->streak_frozen_until = $frozenUntil;
        $user->save();

        return back()->with('success', '✅ تم تفعيل تجميد السلسلة حتى ' . $frozenUntil->translatedFormat('Y-m-d'));
    }



}