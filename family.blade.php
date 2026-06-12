<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>
@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">👨‍👩‍👧 Family Sharing</h2>
        <p class="text-muted">Share your inventory with family members</p>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold">📧 Invite Family Member</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('family.invite') }}" class="d-flex gap-3">
            @csrf
            <input type="email" name="email" class="form-control form-control-lg"
                placeholder="Enter family member's email..." required>
            <button type="submit" class="btn btn-primary btn-lg px-4">📨 Invite</button>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold">👥 Family Members</h5>
    </div>
    <div class="card-body">
        @if($family && $family->members->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Email</th><th>Joined</th></tr>
                </thead>
                <tbody>
                    @foreach($family->members as $index => $member)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $member->user->name ?? 'N/A' }}</td>
                        <td>{{ $member->user->email ?? 'N/A' }}</td>
                        <td>{{ $member->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <div style="font-size:4rem;">👨‍👩‍👧</div>
            <h5 class="mt-3">No family members yet!</h5>
            <p class="text-muted">Invite family members using their email above.</p>
        </div>
        @endif
    </div>
</div>
@endsection