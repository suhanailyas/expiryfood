@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">🛒 Shopping List</h2>
        <p class="text-muted">Track items you need to buy</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Add Item Form --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('shopping.store') }}" class="d-flex gap-3">
            @csrf
            <input type="text" name="item_name" class="form-control form-control-lg"
                placeholder="Enter item name..." required>
            <button type="submit" class="btn btn-success btn-lg px-4">➕ Add</button>
        </form>
    </div>
</div>

{{-- Shopping Items --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold">📋 Items to Buy ({{ $items->count() }})</h5>
    </div>
    <div class="card-body">
        @if($items->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr class="{{ $item->purchased ? 'table-success' : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold {{ $item->purchased ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $item->item_name }}
                        </td>
                        <td>
                            @if($item->purchased)
                                <span class="badge bg-success">✅ Purchased</span>
                            @else
                                <span class="badge bg-warning text-dark">⏳ Pending</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            @if(!$item->purchased)
                            <form method="POST" action="{{ route('shopping.purchased', $item->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">✅ Mark Purchased</button>
                            </form>
                            @else
                                <span class="text-muted">Done ✅</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-5">
                <div style="font-size:4rem;">🛒</div>
                <h5 class="mt-3">Shopping list is empty!</h5>
                <p class="text-muted">Add items you need to buy above.</p>
            </div>
        @endif
    </div>
</div>
@endsection