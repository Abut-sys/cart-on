@extends('layouts.index')

@section('content')
    <div class="container mt-4">

        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header" style="background-color: #d3d3d3;"> <!-- Light gray header -->
                <h2 class="mb-0" style="color: black;">Add Product Category</h2> <!-- Black header text -->
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="category_name" style="font-weight: bold; color: black; margin-bottom: 5px;">Category
                            Name</label> <!-- Bold label with bottom margin -->
                        <input type="text" class="form-control" id="category_name" name="name" required
                            placeholder="Category Name" style="background-color: #dcdcdc; border-color: #c0c0c0;">
                        <!-- Gray input -->
                    </div>

                    <div class="form-group mt-3"> <!-- Top margin added to space this group from the previous one -->
                        <label for="sub_category_name"
                            style="font-weight: bold; color: black; margin-bottom: 5px;">Sub-Category</label>
                        <!-- Bold label with bottom margin -->
                        <input type="text" class="form-control" id="sub_category_name" name="sub_category_name"
                            placeholder="Sub Category" style="background-color: #dcdcdc; border-color: #c0c0c0;">
                        <!-- Gray input -->
                    </div>

                    <!-- Button container with added spacing -->
                    <div class="mt-4">
                        <button type="submit" class="btn confirm-btn" style="background-color: #00ff00; color: black;">
                            <!-- Bright green button -->
                            Confirm
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn back-btn"
                            style="background-color: #ff0000; color: black;"> <!-- Red Back button -->
                            Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
