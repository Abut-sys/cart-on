<h1>Edit Brand</h1>
<form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label>Nama:</label>
    <input type="text" name="name" value="{{ $brand->name }}" required>

    <label>Deskripsi:</label>
    <textarea name="description">{{ $brand->description }}</textarea>
    
    <label>Logo:</label>
    <input type="file" name="logo">
    <button type="submit">Perbarui</button>
</form>
