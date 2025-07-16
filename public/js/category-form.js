function handleSubcategoryForm() {
    const subcategoryList = document.getElementById('subcategory-list');

    document.getElementById('add-subcategory').addEventListener('click', function () {
        const newSubcategoryItem = document.createElement('li');
        newSubcategoryItem.className = 'category-create-subcategory-item mb-2 subcategory-animated';  // Add animation class
        newSubcategoryItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <input type="text" class="form-control category-create-form-control sub_category_name"
                    name="sub_category_name[]" placeholder="Sub Category">
                <button type="button" class="btn btn-link text-danger remove-subcategory">Delete</button>
            </div>
        `;
        subcategoryList.appendChild(newSubcategoryItem);

        setTimeout(() => {
            newSubcategoryItem.classList.remove('subcategory-animated');
        }, 400);
    });

    subcategoryList.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-subcategory')) {
            const subcategoryItem = e.target.closest('li');
            subcategoryItem.classList.add('subcategory-deleting');

            setTimeout(() => {
                subcategoryItem.remove();
            }, 400);
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    handleSubcategoryForm();
});
