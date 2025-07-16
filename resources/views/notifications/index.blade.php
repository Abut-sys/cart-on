@extends('layouts.index')

@section('title', 'Notifications')

@section('dongol')
    <div class="th-bg-container">
        <div class="payment-sidebar">
            @include('components.profile-sidebar')
        </div>

        <div class="th-bg-card">
            <div class="th-bg-body">
                <div class="th-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h2 class="th-title">Your Notifications</h2>
                    <div class="th-filter-group-notif">
                        <button type="button" class="th-filter-btn-notif {{ $status === 'all' ? 'filter-active' : '' }}"
                            data-status="all">All</button>
                        <button type="button" class="th-filter-btn-notif {{ $status === 'unread' ? 'filter-active' : '' }}"
                            data-status="unread">Unread</button>
                        <button type="button" class="th-filter-btn-notif {{ $status === 'read' ? 'filter-active' : '' }}"
                            data-status="read">Read</button>
                    </div>

                </div>

                @if ($notifications->whereNull('read_at')->count() > 0)
                    <button id="markAllAsReadPageBtn" class="mark-all-text">
                        Mark All as Read
                    </button>
                @endif

                <div id="notificationList">
                    @forelse ($notifications as $notification)
                        <div class="notif-card {{ $notification->read_at ? 'notif-read' : 'notif-unread' }}"
                            data-read-status="{{ $notification->read_at ? 'read' : 'unread' }}">
                            <div class="notif-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="notif-content">
                                <p class="notif-text">{{ $notification->data['message'] ?? 'No message' }}</p>
                                <small
                                    class="notif-time text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="th-empty-state text-center mt-5">
                            <h5 class="text-muted">No notifications yet.</h5>
                        </div>
                    @endforelse
                </div>

                @if ($notifications->hasPages())
                    <div class="th-pagination-wrapper mt-4" id="paginationWrapper">
                        {{ $notifications->links('components.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('pemai/css/notification.css') }}">
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new NotificationHandler();
        });
    </script>
@endsection
