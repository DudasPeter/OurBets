import { ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Always force light mode
    document.documentElement.classList.remove('dark');
}

export function useAppearance() {
    const appearance = ref<Appearance>('light');

    function updateAppearance(value: Appearance) {
        // No-op: always keep light mode
    }

    return {
        appearance,
        updateAppearance,
    };
}
