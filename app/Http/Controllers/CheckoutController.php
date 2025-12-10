<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $cart = $this->getCartItems();
        if ($cart['items']->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Кошик порожній.']);
        }

        return view('checkout.index', $cart);
    }

    public function store(Request $request)
    {
        $cart = $this->getCartItems();
        if ($cart['items']->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Кошик порожній.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::transaction(function () use ($validated, $cart) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'address' => $validated['address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total' => $cart['grandTotal'],
                'status' => 'new',
                'coupon_code' => $cart['coupon']?->code,
                'discount' => $cart['discount'],
            ]);

            foreach ($cart['items'] as $item) {
                $order->items()->create([
                    'instrument_id' => $item['instrument']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('checkout.success', $order)->with('status', 'Замовлення оформлено.');
    }

    public function success(Order $order)
    {
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    private function getCartItems(): array
    {
        $cart = session('cart', []);
        $ids = array_keys($cart);
        $instruments = Instrument::whereIn('id', $ids)->get()->keyBy('id');

        $items = collect($cart)->map(function ($row, $id) use ($instruments) {
            $instrument = $instruments[$id] ?? null;
            if (!$instrument) {
                return null;
            }

            return [
                'instrument' => $instrument,
                'quantity' => $row['quantity'],
                'price' => $instrument->effective_price,
            ];
        })->filter();

        $subtotal = $items->sum(fn ($item) => $item['price'] * $item['quantity']);

        $couponData = session('coupon');
        $coupon = null;
        $discount = 0;
        if ($couponData) {
            $coupon = Coupon::where('code', $couponData['code'])->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->type === 'percent'
                    ? round($subtotal * ($coupon->amount / 100), 2)
                    : min($subtotal, (float) $coupon->amount);
            } else {
                session()->forget('coupon');
            }
        }

        $grandTotal = max($subtotal - $discount, 0);

        return [
            'items' => $items,
            'total' => $subtotal,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'coupon' => $coupon,
        ];
    }
}
