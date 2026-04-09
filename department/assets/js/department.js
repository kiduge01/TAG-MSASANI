/**
 * Department Subsystem JavaScript
 * Form validation, modals, and UI interactions
 */

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initializeUI();
    attachEventListeners();
});

/**
 * Initialize UI Components
 */
function initializeUI() {
    // Hide success messages after 5 seconds
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Initialize all form fields
    initializeFormFields();
}

/**
 * Attach Event Listeners
 */
function attachEventListeners() {
    // Form submission validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });

    // Search input - clear button
    const searchInputs = document.querySelectorAll('input[type="text"]');
    searchInputs.forEach(input => {
        if (input.placeholder.includes('Search')) {
            input.addEventListener('keyup', function() {
                this.style.borderColor = this.value ? '#d4af37' : '#dee2e6';
            });
        }
    });

    // Delete confirmation
    const deleteLinks = document.querySelectorAll('a[onclick*="confirm"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = 'Are you sure? This action cannot be undone.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Mobile sidebar toggle (if implemented)
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
}

/**
 * Initialize Form Fields
 */
function initializeFormFields() {
    // Set today's date as default for date fields
    document.querySelectorAll('input[type="date"]').forEach(input => {
        if (!input.value) {
            input.value = getCurrentDateISO();
        }
    });

    // Format currency inputs
    document.querySelectorAll('input[name="amount"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                const num = parseFloat(this.value);
                if (!isNaN(num)) {
                    this.value = num.toFixed(2);
                }
            }
        });
    });
}

/**
 * Validate Form
 * @param {HTMLFormElement} form
 * @returns {boolean}
 */
function validateForm(form) {
    let isValid = true;
    const fields = form.querySelectorAll('[required]');

    fields.forEach(field => {
        const value = field.value.trim();
        
        // Check if required field is empty
        if (!value) {
            highlightField(field, true);
            isValid = false;
        } else {
            highlightField(field, false);

            // Specific validations
            if (field.type === 'email' && !isValidEmail(value)) {
                highlightField(field, true);
                isValid = false;
            }

            if (field.type === 'tel' && !isValidPhone(value)) {
                // Phone is not required to be valid, just a warning
                console.warn('Invalid phone format: ' + value);
            }

            if (field.name === 'amount' && !isValidAmount(value)) {
                highlightField(field, true);
                isValid = false;
            }
        }
    });

    if (!isValid) {
        showNotification('Please fill in all required fields correctly', 'danger');
    }

    return isValid;
}

/**
 * Highlight Invalid Field
 * @param {HTMLElement} field
 * @param {boolean} isInvalid
 */
function highlightField(field, isInvalid) {
    if (isInvalid) {
        field.style.borderColor = '#dc3545';
        field.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
    } else {
        field.style.borderColor = '#dee2e6';
        field.style.boxShadow = 'none';
    }
}

/**
 * Validate Email
 * @param {string} email
 * @returns {boolean}
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validate Phone
 * @param {string} phone
 * @returns {boolean}
 */
function isValidPhone(phone) {
    const re = /^[\d\s\-\+\(\)]+$/;
    return re.test(phone) && phone.replace(/\D/g, '').length >= 10;
}

/**
 * Validate Amount
 * @param {string} amount
 * @returns {boolean}
 */
function isValidAmount(amount) {
    const num = parseFloat(amount);
    return !isNaN(num) && num > 0;
}

/**
 * Get Current Date in ISO Format
 * @returns {string}
 */
function getCurrentDateISO() {
    const today = new Date();
    return today.toISOString().split('T')[0];
}

/**
 * Show Notification
 * @param {string} message
 * @param {string} type - 'success', 'danger', 'info', 'warning'
 */
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${getIconForType(type)}"></i>
        <span>${message}</span>
    `;
    
    const container = document.querySelector('.page-container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.style.transition = 'opacity 0.3s ease';
        alertDiv.style.opacity = '0';
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

/**
 * Get Icon for Notification Type
 * @param {string} type
 * @returns {string}
 */
function getIconForType(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-circle',
        'info': 'info-circle',
        'warning': 'warning'
    };
    return icons[type] || 'info-circle';
}

/**
 * Toggle Sidebar (Mobile)
 */
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

/**
 * Format Currency Display
 * @param {number} amount
 * @returns {string}
 */
function formatCurrency(amount) {
    return 'Tsh ' + parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/**
 * Setup Textarea Auto-Resize
 */
function setupAutoResizeTextarea() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        function autoResize() {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 500) + 'px';
        }
        
        textarea.addEventListener('input', autoResize);
        autoResize();
    });
}

/**
 * Setup Table Row Selection (if needed for bulk actions)
 */
function setupTableRowSelection() {
    const tables = document.querySelectorAll('.table');
    tables.forEach(table => {
        const checkboxes = table.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length === 0) return;

        // Header checkbox for select all
        const headerCheckbox = table.querySelector('thead input[type="checkbox"]');
        if (headerCheckbox) {
            headerCheckbox.addEventListener('change', function() {
                const bodyCheckboxes = table.querySelectorAll('tbody input[type="checkbox"]');
                bodyCheckboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        // Update header checkbox state
        const bodyCheckboxes = table.querySelectorAll('tbody input[type="checkbox"]');
        bodyCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(bodyCheckboxes).every(c => c.checked);
                const noneChecked = Array.from(bodyCheckboxes).every(c => !c.checked);
                if (headerCheckbox) {
                    headerCheckbox.checked = allChecked;
                    headerCheckbox.indeterminate = !allChecked && !noneChecked;
                }
            });
        });
    });
}

/**
 * Export Table to CSV (if needed)
 * @param {string} tableSelector
 * @param {string} filename
 */
function exportTableToCSV(tableSelector, filename = 'export.csv') {
    const table = document.querySelector(tableSelector);
    if (!table) return;

    let csv = [];
    
    // Get headers
    const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent.trim());
    csv.push(headers.join(','));

    // Get rows
    table.querySelectorAll('tbody tr').forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).map(td => {
            let text = td.textContent.trim();
            // Escape quotes and wrap in quotes if contains comma
            text = text.replace(/"/g, '""');
            return text.includes(',') ? `"${text}"` : text;
        });
        csv.push(cells.join(','));
    });

    // Create and download file
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}

/**
 * Print Page
 * @param {string} sectionSelector - Optional: print specific section only
 */
function printPage(sectionSelector = null) {
    if (sectionSelector) {
        const section = document.querySelector(sectionSelector);
        if (!section) return;
        
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write('<pre>' + section.innerHTML + '</pre>');
        printWindow.document.close();
        printWindow.print();
    } else {
        window.print();
    }
}

/**
 * Debounce Function for Search/Filter
 * @param {Function} func
 * @param {number} wait
 * @returns {Function}
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Live Search in Table
 * @param {string} inputSelector
 * @param {string} tableSelector
 */
function setupLiveTableSearch(inputSelector, tableSelector) {
    const input = document.querySelector(inputSelector);
    const table = document.querySelector(tableSelector);
    
    if (!input || !table) return;

    input.addEventListener('keyup', debounce(function() {
        const searchTerm = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }));
}

// Initialize textarea auto-resize on page load
setupAutoResizeTextarea();
setupTableRowSelection();
