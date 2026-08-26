@extends('layouts.admin')

@section('title', 'Customers')

@section('content')

    <h1 class="font-display text-2xl font-semibold mb-6">Customers</h1>

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..."
               class="w-full max-w-sm border-hairline text-sm focus:border-accent focus:ring-accent">
    </form>

    <div class="border border-hairline">
        <table class="w-full text-sm">
            <thead class="bg-ink/5 text-left font-mono text-xs uppercase text-ink/50">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Orders</th>
                    <th class="px-4 py-3">Total spent</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hairline">
                @foreach ($customers as $customer)
                    <tr>
                        <td class="px-4 py-3">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-ink/70">{{ $customer->email }}</td>
                        <td class="px-4 py-3">{{ $customer->orders_count }}</td>
                        <td class="px-4 py-3 font-mono">₱{{ number_format($customer->orders_sum_total ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-accent hover:underline text-xs">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $customers->links() }}</div>

@endsection