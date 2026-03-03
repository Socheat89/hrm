import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Validasi delete confirmation
document.addEventListener('submit', function (e) {
    if (e.target && e.target.matches('form[data-confirm="true"]')) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    }
});
