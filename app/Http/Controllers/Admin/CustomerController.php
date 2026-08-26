<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders')->withSum('orders', 'total');

        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', '%' . $request->search . '%')
                                        ->orWhere('email', 'like', '%' . $request->search . '%'));
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_unless($user->role === 'customer', 404);

        $orders = $user->orders()->latest()->get();

        return view('admin.customers.show', compact('user', 'orders'));
    }
}