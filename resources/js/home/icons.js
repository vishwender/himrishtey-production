import { createIcons } from 'lucide';
import * as LucideIcons from 'lucide';

const lucideIcons = Object.fromEntries(
    Object.entries(LucideIcons)
        .filter(([name, value]) => typeof value === 'object' && name !== 'createIcons' && name !== 'default')
        .map(([name, icon]) => [name, icon])
);

export function initializeLucideIcons() {
    if (typeof window !== 'undefined') {
        window.lucide = window.lucide || {};
        window.lucide.createIcons = () => createIcons({ icons: lucideIcons });
    }

    createIcons({ icons: lucideIcons });
}
