import { create } from 'zustand';
import { devtools } from 'zustand/middleware';
import { dispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import SettingsStore from '../types/SettingsStore';
import Filter from '../types/Filter';

export const useSettingsStore = create<SettingsStore>()(devtools(
    (set, get) => ({
        filters: window.filtersSettings.filters,
        settings: {
            showCount: window.filtersSettings.showCount,
            hideEmpty: window.filtersSettings.hideEmpty,
            dynamicRecount: window.filtersSettings.dynamicRecount,
            selectedFirst: window.filtersSettings.selectedFirst,
            autosubmit: window.filtersSettings.autosubmit,
            ajax_reload: window.filtersSettings.ajax_reload,
            infinity_load: window.filtersSettings.infinity_load,
            enable_loader: window.filtersSettings.enable_loader,
            mobile_filters: window.filtersSettings.mobile_filters
        },
        designSettings: window.filtersSettings.design,
        updateFilter: (id, newValues) => {
            if(!get().filters[id]) {
                return;
            }

            const filters: { [key: string]: Filter } = JSON.parse(JSON.stringify(get().filters));

            filters[id] = { ...filters[id], ...newValues };
            
            set({ filters });
        },
        setFilters: filters => set({ filters }),
        setState: (payload) => set(state => ({ ...state, ...payload })),
        setSettings: (payload) => set(state => ({ ...state, settings: { ...state.settings, ...payload } })),
        setDesignSetting: (payload) => set(state => ({ ...state, designSettings: { ...state.designSettings, ...payload } })),
        save: async () => {
            const state = get();

            // @ts-ignore 
            const settings = new window.wp.api.models.Settings({
                'filtry_filters': state.filters,
                'filtry_hide_empty': state.settings.hideEmpty,
                'filtry_dynamic_recount': state.settings.dynamicRecount,
                'filtry_show_count': state.settings.showCount,
                'filtry_selected_first': state.settings.selectedFirst,
                'filtry_autosubmit': state.settings.autosubmit,
                'filtry_ajax_reload': state.settings.ajax_reload,
                'filtry_infinity_load': state.settings.infinity_load,
                'filtry_enable_laoder': state.settings.enable_loader,
                'filtry_mobile_filters': state.settings.mobile_filters,
                'filtry_disable_styles': state.designSettings.disable,
                'filtry_floating_button_position': state.designSettings.floating_button_position,
            });

            await settings.save();

            await dispatch( noticesStore ).createSuccessNotice( 'Success!', {
                type: 'snackbar'
            } );

            document.body.scrollIntoView({ behavior: 'smooth' });
        }
    }),
    { trace: true }
));

export default useSettingsStore;