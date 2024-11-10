import { ToggleControl, SelectControl } from '@wordpress/components';
import { ColorField } from '../Fields/ColorField';
import { __ } from '@wordpress/i18n';
import { useSettingsStore } from '../../store/settingsStore';

export default function Settings() {
    const [settings, setSettings] = useSettingsStore(state => [state.designSettings, state.setDesignSetting]);
    
    return (
        <>
            <ToggleControl
                label={ __('Disable styles', 'filtry') }
                onChange={ value => setSettings({ disable: value }) }
                help={ __('Disable styles that come with the plugin. Usefull if you want to style the filters yourself', 'filtry') }
                checked={ settings.disable }
            />

            <ColorField
                label={ __('Primary color', 'filtry') }
                value={ settings.primary_color }
                onChange={ value => setSettings({ primary_color: value }) }
            />

            <ColorField
                label={ __('Secondary color', 'filtry') }
                value={ settings.secondary_color }
                onChange={ value => setSettings({ secondary_color: value }) }
            />

            <ColorField
                label={ __('Accent color', 'filtry') }
                value={ settings.accent_color }
                onChange={ value => setSettings({ accent_color: value }) }
            />

            <SelectControl
                className='max-w-80'
                label={ __('Mobiel Floatin Button Position', 'filtry') }
                value={ settings.floating_button_position }
                options={ [
                    { label: __( 'Bottom Right', 'filtry' ), value: 'bottom-right' },
                    { label: __( 'Bottom Left', 'filtry' ), value: 'bottom-left' },
                    { label: __( 'None', 'filtry' ), value: 'none' },
                ] }
                help={ __("Position of the floating button on mobile devices. Mobile filters must be enabled first", 'filtry') }
                onChange={ ( value: string ) => setSettings({ floating_button_position: value }) }
            />
        </>
    );
}