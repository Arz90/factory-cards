<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('user_id', auth()->id())->orderByDesc('created_at')->limit(5)->get();
        return view('user.dashboard', compact('orders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->orderByDesc('created_at')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.product');
        return view('user.order-detail', compact('order'));
    }

    public function profile()
    {
        return view('user.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);
        auth()->user()->update($data);
        return back()->with('success', 'Perfil actualizado.');
    }
}
