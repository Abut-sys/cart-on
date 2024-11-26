@extends('layouts.index')

@section('content')
    <h3>Unread Notifications</h3>
    @foreach ($unreadNotifications as $notification)
        <div>
            {{ $notification->data['message'] }}
            <form action="{{ route('markAsRead') }}" method="POST">
                @csrf
                <button type="submit" name="notification_ids[]" value="{{ $notification->id }}">Mark as Read</button>
            </form>
        </div>
    @endforeach

    <h3>Read Notifications</h3>
    @foreach ($readNotifications as $notification)
        <div>
            {{ $notification->data['message'] }}
        </div>
    @endforeach
@endsection
