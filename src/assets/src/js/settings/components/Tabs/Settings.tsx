import { ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import useSettingsStore from '../../store/settingsStore';

export default function Settings() {
    const [settings, setSettings] = useSettingsStore(state => [state.settings, state.setSettings]);

    return (
        <>
            <ToggleControl
                label={ __('Show terms count', 'filtry') }
                onChange={ value => setSettings({ showCount: value }) }
                help={ __('Show the count of products in a category', 'filtry') }
                checked={ settings.showCount }
            />

            { settings.showCount && (
                <ToggleControl
                    label={ __('Dynamic recount', 'filtry') }
                    onChange={ value => setSettings({ dynamicRecount: value }) }
                    help={ __('Count the numbers of products in a category depending on the currently selected filters', 'filtry') }
                    checked={ settings.dynamicRecount }
                />
            ) }

            <ToggleControl
                label={ __('Hide Empty', 'filtry') }
                onChange={ value => setSettings({ hideEmpty: value }) }
                help={ __('Hide categories with 0 products', 'filtry') }
                checked={ settings.hideEmpty }
            />

            <ToggleControl
                label={ __('Selected First', 'filtry') }
                onChange={ value => setSettings({ selectedFirst: value }) }
                help={ __('Show selected filters on top', 'filtry') }
                checked={ settings.selectedFirst }
            />

            <ToggleControl
                label={ __('Autosubmit', 'filtry') }
                onChange={ value => setSettings({ autosubmit: value }) }
                help={ __('Apply a filter immediatelly after it\'s toggled on or off', 'filtry') }
                checked={ settings.autosubmit }
            />

            <ToggleControl
                label={ __('AJAX Reload', 'filtry') }
                onChange={ value => setSettings({ ajax_reload: value }) }
                help={ __('Apply filters without refreshing the page', 'filtry') }
                checked={ settings.ajax_reload }
            />

            { settings.ajax_reload && (
                <>
                    <ToggleControl
                        label={ __('Infinity loading', 'filtry') }
                        onChange={ value => setSettings({ infinity_load: value }) }
                        help={ __('Load products at the bottom of the list instead of going to a next page. Removes the pagination and adds a button to load more products at the end of the list.', 'filtry') }
                        checked={ settings.infinity_load }
                    />

                    <ToggleControl
                        label={ __('Loader animation', 'filtry') }
                        onChange={ value => setSettings({ enable_loader: value }) }
                        help={ __('Add loader animation while the next page of products is loading in AJAX mode', 'filtry') }
                        checked={ settings.enable_loader }
                    />
                </>
            ) }
        </>
    );
}