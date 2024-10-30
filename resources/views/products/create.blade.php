<h1>Tambah Produk Baru</h1>
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nama Produk:</label>
    <input type="text" name="name" required>

    <label>Deskripsi:</label>
    <textarea name="description"></textarea>

    <label>Harga:</label>
    <input type="number" name="price" required>

    <label>Stok:</label>
    <input type="number" name="stock" required>

    <label>Gambar:</label>
    <input type="file" name="image">
    
    <button type="submit">Simpan</button>
</form>
