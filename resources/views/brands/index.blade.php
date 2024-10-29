<h1>Daftar Brand</h1>
<a href="{{ route('brands.create') }}">Buat Brand Baru</a>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<ul>
    @foreach ($brands as $brand)
        <li>
            <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="50">
            {{ $brand->name }}
            <a href="{{ route('brands.show', $brand->id) }}">Lihat</a>
            <a href="{{ route('brands.edit', $brand->id) }}">Edit</a>
            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </li>
    @endforeach
</ul>
