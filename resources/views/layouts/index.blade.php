<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CartON')</title>
    <link href="{{ asset('/') }}assets/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="{{ asset('/') }}assets/plugin/fontawasome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/sidebar.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/costumers/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/costumers/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/categories/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/categories/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/categories/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/show.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/edit.css">


</head>

<body>
    @include('layouts.navbar')
    {{-- content --}}
    <div class="mt-2">
        <div class="container">
            @yield('content')
        </div>
    </div>
    {{-- end content --}}
    <script src="{{ asset('/') }}assets/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    {{-- sidebar --}}
    <script src="{{ asset('pemai/js/sidebar.js') }}"></script>

    {{-- category --}}
    <script>
        document.getElementById('add-subcategory').addEventListener('click', function() {
            const subcategoryList = document.getElementById('subcategory-list');
            const newSubcategoryItem = document.createElement('li');
            newSubcategoryItem.className = 'd-flex justify-content-between align-items-center mb-2';
            newSubcategoryItem.innerHTML = `
            <input type="text" class="form-control mb-3" name="new_subcategories[]" required
                style="background-color: #dcdcdc; border-color: #c0c0c0;"> <!-- Gray input with mb-3 -->
            <button type="button" class="btn btn-link text-danger" onclick="removeSubcategory(this)">Delete</button>
        `;
            subcategoryList.appendChild(newSubcategoryItem);
        });

        function removeSubcategory(button, subCategoryId = null) {
            if (subCategoryId) {
                // Mark the subcategory for deletion
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_subcategories[]';
                hiddenInput.value = subCategoryId;
                button.closest('form').appendChild(hiddenInput);
            }
            button.parentElement.remove();
        }
    </script>

    {{-- sweet alert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @if (session('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('msg') }}",
                    icon: 'success',
                    confirmButtonText: 'Return'
                });
            });
        </script>
    @endif
</body>

</html>
