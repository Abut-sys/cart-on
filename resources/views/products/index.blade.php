<h1>Daftar Produk</h1>
<a href="{{ route('products.create') }}">Tambah Produk Baru</a>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<ul>
    @foreach ($products as $product)
        <li>
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="50">
            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
            <a href="{{ route('products.show', $product->id) }}">Lihat</a>
            <a href="{{ route('products.edit', $product->id) }}">Edit</a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </li>
    @endforeach
</ul>
