import './theme';
import { initializeProfileSlider } from './home/profile-slider';
import './public/landing.js';
import './public/login.js';
import './public/privacy-policies.js';
import './public/script.js';
import './public/toast-manager.js';
import './public/signup.js';
import './public/upload-photos.js';
import { initializeRevealAnimations } from './home/animations';
import { initializeLucideIcons } from './home/icons';
import { initializeNavigation } from './home/navigation';

document.addEventListener('DOMContentLoaded', () => {
    initializeRevealAnimations();
    initializeLucideIcons();
    initializeNavigation();
    initializeProfileSlider();
});