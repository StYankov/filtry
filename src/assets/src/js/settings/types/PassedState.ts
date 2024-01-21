import Filter from './Filter';

type PassedState = {
    filters: { [key: string]: Filter },
    hideEmpty: boolean,
    dynamicRecount: boolean,
    showCount: boolean,
    selectedFirst: boolean,
    autosubmit: boolean
    ajax_reload: boolean,
    infinity_load: boolean,
    enable_loader: boolean
}

export default PassedState;