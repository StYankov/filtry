import { Panel, PanelBody, Button, TabPanel } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import Filters from './Tabs/Filters';
import Settings from './Tabs/Settings';
import Sortable from './Tabs/Sortable';
import useSettingsStore from '../store/settingsStore';

export default function () {
    const save = useSettingsStore(state => state.save);

    return (
        <Panel>
            <TabPanel
                tabs={ [
                    {
                        name: 'filters',
                        title: __( 'Filters', 'filtry' )
                    },
                    {
                        name: 'settings',
                        title: __( 'Settings', 'filtry' )
                    },
                    {
                        name: 'sortable',
                        title: __( 'Sortable', 'filtry' )
                    },
                ] }
            >
                { ( tab ) => (
                    <PanelBody>
                        { TabSwitcher(tab.name) }

                        <Button 
                            variant='primary'
                            size='compact'
                            onClick={ save }
                        >
                            Save
                        </Button>
                    </PanelBody>
                ) }
            </TabPanel>
        </Panel>
    );
}

function TabSwitcher(tabId: string) {
    switch(tabId) {
        case 'filters':
            return <Filters />
        case 'settings':
            return <Settings />
        case 'sortable':
            return <Sortable />
        default:
            return null;
    }
}