<h1>{{ $product->name }}</h1>
<p>Deskripsi: {{ $product->description }}</p>
<p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
<p>Stok: {{ $product->stock }}</p>
<img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="100">
<a href="{{ route('products.index') }}">Kembali ke Daftar Produk</a>
