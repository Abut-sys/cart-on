<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google Logged In</title>
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/spinner.css">
</head>

<body>
    @if ($user === null)
        <h1>User tidak ditemukan</h1>
    @else
        <h1>Nama: {{ $user->name }}</h1>
        <h2>Email: {{ $user->email }}</h2>
        <img src="{{ $avatar }}" alt="{{ $user->name }}">
    @endif


</body>

</html>
