@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">👋 Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Here's your food expiry overview</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-success btn-lg shadow">
        ➕ Add New Item
    </a>
</div>

{{-- STATS CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-semibold">Fresh Items</p>
                        <h1 class="fw-bold mb-0">{{ $fresh }}</h1>
                    </div>
                    <div style="font-size:3rem;">🟢</div>
                </div>
                <p class="mt-2 mb-0 opacity-75 small">More than 7 days left</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-semibold">Expiring Soon</p>
                        <h1 class="fw-bold mb-0">{{ $expiring }}</h1>
                    </div>
                    <div style="font-size:3rem;">🟡</div>
                </div>
                <p class="mt-2 mb-0 opacity-75 small">Within 3-7 days</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #cb2d3e, #ef473a);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-semibold">Expired</p>
                        <h1 class="fw-bold mb-0">{{ $expired }}</h1>
                    </div>
                    <div style="font-size:3rem;">🔴</div>
                </div>
                <p class="mt-2 mb-0 opacity-75 small">Please discard these</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-semibold">Total Items</p>
                        <h1 class="fw-bold mb-0">{{ $total }}</h1>
                    </div>
                    <div style="font-size:3rem;">📦</div>
                </div>
                <p class="mt-2 mb-0 opacity-75 small">All tracked items</p>
            </div>
        </div>
    </div>
</div>

{{-- CHART + QUICK ACTIONS --}}
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold">📊 Items Status Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold">⚡ Quick Actions</h5>
            </div>
            <div class="card-body d-flex flex-column gap-3">
                <a href="{{ route('items.create') }}" class="btn btn-success btn-lg w-100">
                    ➕ Add Item
                </a>
                <a href="{{ route('items.index') }}" class="btn btn-primary btn-lg w-100">
                    📦 View All Items
                </a>
                <a href="{{ route('shopping') }}" class="btn btn-warning btn-lg w-100">
                    🛒 Shopping List
                </a>
                <a href="{{ route('reports') }}" class="btn btn-danger btn-lg w-100">
                    📊 View Reports
                </a>
                <a href="{{ route('family') }}" class="btn btn-info btn-lg w-100 text-white">
                    👨‍👩‍👧 Family Sharing
                </a>
            </div>
        </div>
    </div>
</div>

{{-- RECENT EXPIRING ITEMS --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between">
        <h5 class="fw-bold">⚠️ Items Expiring Within 7 Days</h5>
        <a href="{{ route('items.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        @php
            $expiringItems = \App\Models\Item::where('user_id', auth()->id())
                ->whereBetween('expiry_date', [now(), now()->addDays(7)])
                ->with('category')
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @if($expiringItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Expiry Date</th>
                        <th>Days Left</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiringItems as $item)
                    @php
                        $daysLeft = now()->diffInDays($item->expiry_date, false);
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td><span class="badge bg-secondary">{{ $item->category->name }}</span></td>
                        <td>{{ $item->expiry_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $daysLeft <= 3 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ $daysLeft }} days
                            </span>
                        </td>
                        <td>PKR {{ number_format($item->price, 2) }}</td>
                        <td>
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-5">
                <div style="font-size:4rem;">🎉</div>
                <h5 class="text-success mt-2">All items are fresh!</h5>
                <p class="text-muted">No items expiring within 7 days.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['🟢 Fresh', '🟡 Expiring Soon', '🔴 Expired'],
            datasets: [{
                label: 'Number of Items',
                data: [{{ $fresh }}, {{ $expiring }}, {{ $expired }}],
                backgroundColor: [
                    'rgba(56, 239, 125, 0.8)',
                    'rgba(255, 210, 0, 0.8)',
                    'rgba(239, 71, 58, 0.8)',
                ],
                borderColor: [
                    '#38ef7d',
                    '#ffd200',
                    '#ef473a',
                ],
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endpush

@endsection