<h1>Edit Produk</h1>
<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Nama Produk:</label>
    <input type="text" name="name" value="{{ $product->name }}" required>

    <label>Deskripsi:</label>
    <textarea name="description">{{ $product->description }}</textarea>

    <label>Harga:</label>
    <input type="number" name="price" value="{{ $product->price }}" required>

    <label>Stok:</label>
    <input type="number" name="stock" value="{{ $product->stock }}" required>

    <label>Gambar:</label>
    <input type="file" name="image">
    
    <button type="submit">Perbarui</button>
</form>
