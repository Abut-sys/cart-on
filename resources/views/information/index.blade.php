@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <form action="{{ route('informations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="image">Gambar:</label>
            <input type="file" name="image" accept="image/*">

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="website_name">Nama Website:</label>
            <input type="text" name="website_name" required>

            <label for="phone_number">Nomor Telepon:</label>
            <input type="text" name="phone_number" required>

            <label for="company_address">Alamat Perusahaan:</label>
            <input type="text" name="company_address" required>

            <label for="about_us">Tentang Kami:</label>
            <textarea name="about_us" required></textarea>

            <button type="submit">Simpan</button>
        </form>
    </div>
@endsection
