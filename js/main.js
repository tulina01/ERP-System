// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Phone number validation
function validatePhone(phone) {
    const phoneRegex = /^\d{10}$/;
    return phoneRegex.test(phone);
}

// Date range validation for reports
function validateDateRange(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return start <= end;
}

// Dynamic subcategory loading
function loadSubcategories(categoryId, subcategorySelectId) {
    const subcategorySelect = document.getElementById(subcategorySelectId);
    if (!subcategorySelect) return;

    fetch(`/api/subcategories.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
            data.forEach(subcategory => {
                const option = document.createElement('option');
                option.value = subcategory.id;
                option.textContent = subcategory.sub_category;
                subcategorySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading subcategories:', error));
}

// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Delete confirmation
function confirmDelete(id, type) {
    if (confirm(`Are you sure you want to delete this ${type}?`)) {
        window.location.href = `/pages/${type}/delete.php?id=${id}`;
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'LKR'
    }).format(amount);
}

// Print report
function printReport() {
    window.print();
}