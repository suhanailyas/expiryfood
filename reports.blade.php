@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">📊 Waste Reports</h2>
        <p class="text-muted">Track your food waste and money loss</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100"
            style="background: linear-gradient(135deg, #cb2d3e, #ef473a);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-semibold">Total Expired Items</p>
                        <h1 class="fw-bold mb-0">{{ $totalExpired }}</h1>
                    </div>
                    <div style="font-size:3rem;">🗑️</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100"
            style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-semibold">Total Money Lost</p>
                        <h1 class="fw-bold mb-0">PKR {{ number_format($totalLoss, 2) }}</h1>
                    </div>
                    <div style="font-size:3rem;">💸</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Expired Items Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold">🔴 Expired Items List</h5>
    </div>
    <div class="card-body">
        @if($expiredItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Expired On</th>
                        <th>Price Lost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiredItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $item->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            <span class="badge bg-danger">
                                {{ \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="text-danger fw-bold">
                            PKR {{ number_format($item->price, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-danger">
                    <tr>
                        <td colspan="5" class="fw-bold text-end">Total Loss:</td>
                        <td class="fw-bold">PKR {{ number_format($totalLoss, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
            <div class="text-center py-5">
                <div style="font-size:4rem;">🎉</div>
                <h5 class="text-success mt-3">No expired items!</h5>
                <p class="text-muted">Great job managing your food!</p>
            </div>
        @endif
    </div>
</div>
@endsection