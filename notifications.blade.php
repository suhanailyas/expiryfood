<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
</div>
@extends('layouts.app')
@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-0">🔔 Notifications</h2>
    <p class="text-muted">Your expiry alerts and messages</p>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded
                {{ $notification->is_read ? 'bg-light' : 'bg-warning bg-opacity-10 border border-warning' }}">
                <div class="d-flex align-items-center gap-3">
                    <div style="font-size:2rem;">{{ $notification->is_read ? '🔕' : '🔔' }}</div>
                    <div>
                        <p class="mb-0 fw-semibold">{{ $notification->message }}</p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @if(!$notification->is_read)
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">✅ Mark Read</button>
                </form>
                @else
                    <span class="badge bg-secondary">Read</span>
                @endif
            </div>
            @endforeach
        @else
        <div class="text-center py-5">
            <div style="font-size:4rem;">🔕</div>
            <h5 class="mt-3">No notifications yet!</h5>
            <p class="text-muted">You will be notified when items are about to expire.</p>
        </div>
        @endif
    </div>
</div>
@endsection