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

    <style>
        .notif-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 10px;
            cursor: default;
            transition: background-color 0.3s ease;
        }

        .notif-read {
            color: #999;
            background-color: #e0e0e0;
            font-weight: normal;
        }

        .notif-unread {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .notif-icon {
            font-size: 1.5rem;
            color: #555;
        }

        .notif-text {
            margin: 0;
        }

        .th-filter-group-notif {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .th-filter-btn-notif {
            border: 2px solid #e9ecef;
            border-radius: 24px;
            padding: 8px 20px;
            color: #6c757d;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            background: #f8f9fa;
            cursor: pointer;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .th-filter-btn-notif::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(73, 80, 87, 0.1), rgba(73, 80, 87, 0.05));
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .th-filter-btn-notif:hover {
            border-color: #ced4da;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }

        .th-filter-btn-notif:hover::before {
            transform: translateX(0);
        }

        .th-filter-btn-notif.filter-active {
            background: #000000;
            color: white;
            border-color: #000000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .th-filter-btn-notif.filter-active:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.th-filter-btn-notif');
            const notifCards = document.querySelectorAll('.notif-card');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.dataset.status;
                    const url = new URL(window.location);
                    url.searchParams.set('status', status);
                    url.searchParams.delete('page');
                    window.location = url.toString();
                });
            });

            // MarkAsRead
            const markAllPageBtn = document.getElementById('markAllAsReadPageBtn');
            if (markAllPageBtn) {
                markAllPageBtn.addEventListener('click', async () => {
                    markAllPageBtn.disabled = true;
                    markAllPageBtn.textContent = 'Marking...';
                    try {
                        const res = await fetch("{{ route('markAsRead') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                notification_ids: 'all'
                            })
                        });
                        if (!res.ok) throw new Error('Failed to mark all as read');
                        window.location.reload();
                    } catch (error) {
                        console.error(error);
                        markAllPageBtn.disabled = false;
                        markAllPageBtn.textContent = 'Mark All as Read';
                        alert('Failed to mark all as read.');
                    }
                });
            }
        });
    </script>
@endsection
