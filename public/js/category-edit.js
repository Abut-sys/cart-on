document.addEventListener('DOMContentLoaded', function() {
    const subcategoryList = document.getElementById('subcategory-list');
    const addSubcategoryBtn = document.getElementById('add-subcategory');

    // Add new subcategory with fadeSlideDown animation
    addSubcategoryBtn.addEventListener('click', function() {
        const newSubcategoryItem = document.createElement('li');
        newSubcategoryItem.className = 'category-edit-subcategory-item mb-2 fadeSlideDown';
        newSubcategoryItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <input type="text" class="form-control category-edit-subcategory-input"
                    name="new_subcategories[]" placeholder="Sub Category" required>
                <button type="button" class="btn btn-link text-danger remove-subcategory">Delete</button>
            </div>
        `;
        subcategoryList.appendChild(newSubcategoryItem);

        // Add event listener for the remove button
        newSubcategoryItem.querySelector('.remove-subcategory').addEventListener('click', function() {
            removeSubcategory(newSubcategoryItem);
        });
    });

    // Remove existing subcategory with fadeSlideUp animation
    subcategoryList.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove-subcategory')) {
            const subcategoryItem = event.target.closest('li');
            removeSubcategory(subcategoryItem);
        }
    });

    function removeSubcategory(subcategoryItem) {
        // Apply fadeSlideUp animation before removing
        subcategoryItem.classList.add('fadeSlideUp');

        // Mark the subcategory for deletion if it exists in the database
        const subCategoryId = subcategoryItem.dataset.id;
        if (subCategoryId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_subcategories[]';
            hiddenInput.value = subCategoryId;
            document.getElementById('categoryForm').appendChild(hiddenInput);
        }

        // Wait for the animation before removing the element
        setTimeout(() => {
            subcategoryItem.remove();
        }, 400);
    }
});
