<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Instrument;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $items = $this->mapItems($cart);
        $subtotal = $items->sum(fn ($item) => $item['price'] * $item['quantity']);
        [$coupon, $discount, $grandTotal] = $this->applyCoupon($subtotal);

        return view('cart.index', [
            'items' => $items,
            'total' => $subtotal,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'coupon' => $coupon,
        ]);
    }

    public function store(Request $request, Instrument $instrument)
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $quantity = $data['quantity'] ?? 1;
        $cart = $this->getCart();
        $currentQty = $cart[$instrument->id]['quantity'] ?? 0;
        $cart[$instrument->id] = [
            'quantity' => $currentQty + $quantity,
        ];

        session(['cart' => $cart]);

        return back()->with('status', 'Додано до кошика.');
    }

    public function update(Request $request, Instrument $instrument)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart = $this->getCart();
        if (!isset($cart[$instrument->id])) {
            return back()->withErrors(['cart' => 'Товару нема в кошику.']);
        }

        $cart[$instrument->id]['quantity'] = $data['quantity'];
        session(['cart' => $cart]);

        return back()->with('status', 'Кількість оновлено.');
    }

    public function destroy(Instrument $instrument)
    {
        $cart = $this->getCart();
        unset($cart[$instrument->id]);
        session(['cart' => $cart]);

        return back()->with('status', 'Видалено з кошика.');
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $cart = $this->getCart();
        $items = $this->mapItems($cart);
        $subtotal = $items->sum(fn ($item) => $item['price'] * $item['quantity']);

        $coupon = Coupon::where('code', $data['code'])->first();
        if (!$coupon || !$coupon->isValid()) {
            return back()->withErrors(['coupon' => 'Купон неактивний або прострочений.']);
        }

        $discount = $this->calculateDiscount($coupon, $subtotal);
        if ($discount <= 0) {
            return back()->withErrors(['coupon' => 'Купон не може бути застосований.']);
        }

        session([
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'amount' => $coupon->amount,
            ],
        ]);

        return back()->with('status', 'Купон застосовано.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        return back()->with('status', 'Купон видалено.');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');

        return back()->with('status', 'Кошик очищено.');
    }

    private function getCart(): array
    {
        return session('cart', []);
    }

    private function applyCoupon(float $subtotal): array
    {
        $couponData = session('coupon');
        if (!$couponData) {
            return [null, 0, $subtotal];
        }

        $coupon = Coupon::where('code', $couponData['code'])->first();
        if (!$coupon || !$coupon->isValid()) {
            session()->forget('coupon');
            return [null, 0, $subtotal];
        }

        $discount = $this->calculateDiscount($coupon, $subtotal);
        $grand = max($subtotal - $discount, 0);

        return [$coupon, $discount, $grand];
    }

    private function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0;
        }

        return $coupon->type === 'percent'
            ? round($subtotal * ($coupon->amount / 100), 2)
            : min($subtotal, (float) $coupon->amount);
    }

    private function mapItems(array $cart)
    {
        $ids = array_keys($cart);
        $instruments = Instrument::whereIn('id', $ids)->with('brand')->get()->keyBy('id');

        return collect($cart)->map(function ($row, $instrumentId) use ($instruments) {
            $instrument = $instruments[$instrumentId] ?? null;
            if (!$instrument) {
                return null;
            }

            return [
                'instrument' => $instrument,
                'quantity' => $row['quantity'],
                'price' => $instrument->effective_price,
            ];
        })->filter();
    }
}
