import './bootstrap';

// Initialize Alpine.js
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Global helper for opening modals
window.openModal = (modalId) => {
    window.dispatchEvent(new CustomEvent(`open-modal-${modalId}`));
};
