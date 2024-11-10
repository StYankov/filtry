import Design from './Design';
import Filter from './Filter';
import Settings from './Settings';

type SettingsStore = {
    filters: { [key: string]: Filter },
    settings: Settings,
    designSettings: Design,
    updateFilter: (id: string, newValues: Partial<Filter>) => void,
    setFilters: (filters: { [key: string]: Filter }) => void,
    setState: (payload: Partial<SettingsStore>) => void,
    setSettings: (payload: Partial<Settings>) => void,
    setDesignSetting: (payload: Partial<Design>) => void,
    save: () => Promise<void>
}

export default SettingsStore;