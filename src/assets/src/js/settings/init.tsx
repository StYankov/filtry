import App from './App';
import { createRoot } from 'react-dom/client';
import PassedState from './types/PassedState';

declare global {
    interface Window {
        filtersSettings: PassedState
    }
}

export function init() {
    const container = document.getElementById('filtry-settings');

    if(!container) {
        return;
    }

    const root = createRoot(container);

    root.render(( <App /> ));
}