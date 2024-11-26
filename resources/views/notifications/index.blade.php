@extends('layouts.index')

@section('content')
    <h2>Notifikasi</h2>

    @foreach ($notifications as $notification)
        <div class="notification-item">
            <p>{{ $notification->message }}</p>
            <small>{{ $notification->created_at }}</small>
        </div>
    @endforeach
@endsection
