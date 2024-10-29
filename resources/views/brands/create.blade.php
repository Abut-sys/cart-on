<h1>Buat Brand Baru</h1>
<form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nama:</label>
    <input type="text" name="name" required>

    <label>Deskripsi:</label>
    <textarea name="description"></textarea>

    <label>Logo:</label>
    <input type="file" name="logo">
    
    <button type="submit">Buat</button>
</form>
