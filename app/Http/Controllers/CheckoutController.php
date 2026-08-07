<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('shop.checkout');
    }

    public function process(Request $request)
    {
        // TODO: Fase 3 — implementar flujo Stripe/Redsys
        return redirect()->route('home')->with('info', 'Pasarela de pago — próximamente.');
    }

    public function confirmation(Order $order)
    {
        return view('shop.confirmation', compact('order'));
    }

    public function stripeWebhook(Request $request)
    {
        // TODO: Fase 3
        return response()->json(['received' => true]);
    }

    public function redsysWebhook(Request $request)
    {
        // TODO: Fase 3
        return response()->json(['received' => true]);
    }
}
