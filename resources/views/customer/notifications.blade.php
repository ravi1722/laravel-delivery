@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'My Notifications')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0">
                        All Notifications
                    </h6>
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-check-all me-1"></i>Mark All Read
                        </button>
                    </form>
                </div>

                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isRead = $notification->read_at !== null;
                    @endphp
                    <div class="p-4 border-bottom {{ !$isRead ? 'bg-light' : '' }}">
                        <div class="d-flex gap-3">
                            {{-- Icon --}}
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:44px;height:44px;
                                background:rgba(255,107,53,.1);
                                color:#FF6B35">
                                <i class="bi {{ $data['icon'] ?? 'bi-bell' }} fs-5"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold small">
                                            {{ $data['title'] ?? 'Notification' }}
                                            @if (!$isRead)
                                                <span class="badge ms-1" style="background:#FF6B35;font-size:9px">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-muted small mt-1">
                                            {{ $data['message'] ?? '' }}
                                        </div>
                                        <div class="text-muted mt-1" style="font-size:11px">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        @if (isset($data['url']) && $data['url'] !== '#')
                                            <a href="{{ $data['url'] }}" class="btn btn-xs btn-outline-primary"
                                                style="font-size:11px;padding:3px 8px">
                                                View
                                            </a>
                                        @endif

                                        <form method="POST"
                                            action="{{ route('notifications.destroy', $notification->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-outline-danger"
                                                style="font-size:11px;padding:3px 8px">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-25"></i>
                        No notifications yet
                    </div>
                @endforelse
                {{-- Pagination --}}
                @if ($notifications->hasPages())
                    <div class="p-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
