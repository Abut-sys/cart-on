@extends('layouts.index')

@section('title', 'Product')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
                <h2 class="mb-0" style="color: black;">Edit Product</h2> <!-- Title -->
                <a href="{{ route('products.index') }}" class="btn btn-danger me-2"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return To Product List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="name" style="font-weight: bold; color: black; margin-bottom: 5px;">Product
                            Name:</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $product->name }}" required style="background-color: #dcdcdc; border-color: #c0c0c0;">
                        <!-- Gray input -->
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="description"
                            style="font-weight: bold; color: black; margin-bottom: 5px;">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">{{ $product->description }}</textarea>
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="price" style="font-weight: bold; color: black; margin-bottom: 5px;">Price:</label>
                        <input type="number" class="form-control" id="price" name="price"
                            value="{{ $product->price }}" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="stock" style="font-weight: bold; color: black; margin-bottom: 5px;">Stock:</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ $product->stock }}" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="image" style="font-weight: bold; color: black; margin-bottom: 5px;">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        @if ($product->image_path)
                            <small class="form-text text-muted">Gambar saat ini:</small>
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                width="100" class="img-thumbnail">
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="brand_id">Brand:</label>
                        <select name="brands_id" id="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ (old('brands_id') == $brand->id || (isset($product) && $product->brands_id == $brand->id)) ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h4>Sub-Variants:</h4>
                    <div id="sub-variants">
                        @foreach ($product->subVariants as $subVariant)
                            <div class="sub-variant">
                                <input type="text" name="sub_variants[]" value="{{ $subVariant->name }}"
                                    placeholder="Sub-Variant Name" required>
                                <button type="button" class="remove-sub-variant">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-sub-variant">Add Another Sub-Variant</button>


                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn" style="background-color: #00ff00; color: black;">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>

                <script>
                    document.getElementById('add-sub-variant').addEventListener('click', function() {
                        const subVariantDiv = document.createElement('div');
                        subVariantDiv.className = 'sub-variant';
                        subVariantDiv.innerHTML = `
                            <input type="text" name="sub_variants[]" placeholder="Sub-Variant Name" required>
                            <button type="button" class="remove-sub-variant">Remove</button>
                        `;
                        document.getElementById('sub-variants').appendChild(subVariantDiv);

                        subVariantDiv.querySelector('.remove-sub-variant').addEventListener('click', function() {
                            subVariantDiv.remove();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
