@extends('layouts.index')

@section('title', 'Product')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header d-flex justify-content-between" style="background-color: #d3d3d3;">
                <h2 class="mb-0" style="color: black;">Add New Product</h2> <!-- Title -->
                <a href="{{ route('products.index') }}" class="btn btn-danger"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return To Product List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" style="font-weight: bold; color: black; margin-bottom: 5px;">Product</label>
                        <input type="text" class="form-control" id="name" name="name" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mt-3">
                        <label for="description"
                            style="font-weight: bold; color: black; margin-bottom: 5px;">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                            style="background-color: #dcdcdc; border-color: #c0c0c0;"></textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="price" style="font-weight: bold; color: black; margin-bottom: 5px;">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mt-3">
                        <label for="stock" style="font-weight: bold; color: black; margin-bottom: 5px;">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mt-3">
                        <label for="image" style="font-weight: bold; color: black; margin-bottom: 5px;">Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>

                    <div class="form-group mt-3">
                        <label for="brand_id">Brand:</label>
                        <select name="brands_id" required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h4>Sub-Variants:</h4>
                    <div id="sub-variants">
                        <div class="sub-variant">
                            <input type="text" name="sub_variants[]" placeholder="Sub-Variant Name">
                            <button type="button" class="remove-sub-variant"
                                aria-label="Remove this sub-variant">Remove</button>
                        </div>
                    </div>
                    <button type="button" id="add-sub-variant" aria-label="Add another sub-variant">Add Another
                        Sub-Variant</button>

                    <!-- Optional: Include a script to handle adding and removing sub-variant fields -->
                    <script>
                        document.getElementById('add-sub-variant').addEventListener('click', function() {
                            const subVariantContainer = document.getElementById('sub-variants');
                            const newSubVariant = document.createElement('div');
                            newSubVariant.classList.add('sub-variant');
                            newSubVariant.innerHTML = `
            <input type="text" name="sub_variants[]" placeholder="Sub-Variant Name" required>
            <button type="button" class="remove-sub-variant" aria-label="Remove this sub-variant">Remove</button>
        `;
                            subVariantContainer.appendChild(newSubVariant);

                            // Add event listener for the remove button of the new sub-variant
                            newSubVariant.querySelector('.remove-sub-variant').addEventListener('click', function() {
                                subVariantContainer.removeChild(newSubVariant);
                            });
                        });

                        // Add event listener to existing remove buttons
                        document.querySelectorAll('.remove-sub-variant').forEach(button => {
                            button.addEventListener('click', function() {
                                const subVariant = this.parentElement;
                                subVariant.parentElement.removeChild(subVariant);
                            });
                        });
                    </script>


                    <div class="mt-4">
                        <button type="submit" class="btn confirm-btn" style="background-color: #00ff00; color: black;">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
