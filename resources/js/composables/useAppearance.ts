export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Always force light mode
    document.documentElement.classList.remove('dark');
}
