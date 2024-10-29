<h1>{{ $brand->name }}</h1>
<p>Deskripsi: {{ $brand->description }}</p>
<img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="100">
<a href="{{ route('brands.index') }}">Kembali ke Daftar Brand</a>
