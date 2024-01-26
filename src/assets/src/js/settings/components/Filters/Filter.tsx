import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { PanelRow, ToggleControl, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import Filter from '../../types/Filter';
import useSettingsStore from '../../store/settingsStore';

type Props = {
    filter: Filter,
    id: string
}

export default function FilterComponent({ filter, id }: Props) {
    const updateFilter = useSettingsStore(state => state.updateFilter);

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
    } = useSortable({ id });
    
    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
    };

    return (
        <div ref={setNodeRef} style={style} {...attributes} {...listeners}>
            <PanelRow className='flex-col items-start'>
                <h3 className="text-underline">{ filter.label }</h3>
                <div className='filter-controls flex gap-3'>
                    <ToggleControl
                        className='self-center'
                        label={ __( 'Enabled', 'filtry' ) }
                        checked={ filter.enabled }
                        onChange={ (enabled) => updateFilter(filter.id, { enabled }) }
                    />
                    <TextControl
                        className='max-w-[180px]'
                        label={ __( 'Filter Name', 'filtry' ) }
                        onChange={ (value) => updateFilter(filter.id, { label: value }) }
                        value={ filter.label }
                        help={ __( 'How the filter name will be displayed on the website', 'filtry' )}
                    />
                    <SelectControl
                        label={ __( 'View Type', 'filtry' ) }
                        help={ __( 'How the filter will be displayed on the website', 'filtry' ) }
                        options={ [
                            { label: __( 'Radio', 'filtry' ), value: 'radio' },
                            { label: __( 'Checkbox', 'filtry' ), value: 'checkbox' }
                        ] }
                        value={ filter.view }
                        onChange={ (value) => updateFilter(filter.id, { view: value }) }
                    />
                    <ToggleControl
                        className='self-center'
                        label={ __('Collapsable', 'filtry') }
                        help={ __( 'Enable the category box to be collapsable', 'filtry' ) }
                        checked={ filter.collapsable }
                        onChange={ (value) => updateFilter(filter.id, { collapsable: value }) }
                    />

                    <SelectControl
                        label={ __( 'Logic', 'filtry' ) }
                        help={ <LogicHelpText /> }
                        options={ [
                            { label: __( 'AND', 'filtry' ), value: 'and' },
                            { label: __( 'OR', 'filtry' ), value: 'or' }
                        ] }
                        value={ filter.logic }
                        onChange={ (value) => updateFilter(filter.id, { logic: value }) }
                    />

                    <SelectControl
                        label={ __( 'Sort', 'filtry' ) }
                        help={ __( 'How to sort the terms inside of filter block', 'filtry' ) } 
                        options={ [
                            { label: __( 'Count', 'filtry' ), value: 'count' },
                            { label: __( 'Title', 'filtry' ), value: 'title' },
                            { label: __( 'ID', 'filtry' ), value: 'id' },
                        ] }
                        value={ filter.sort }
                        onChange={ (value) => updateFilter(filter.id, { sort: value }) }
                    />

                    <SelectControl
                        label={ __( 'Sort Order', 'filtry' ) }
                        help={ __( 'Order of sorting (ASC or DESC)', 'filtry' ) }
                        options={ [
                            { label: __( 'ASC', 'filtry' ), value: 'asc' },
                            { label: __( 'DESC', 'filtry' ), value: 'desc' },
                        ] }
                        value={ filter.sortOrder }
                        onChange={ (value) => updateFilter(filter.id, { sortOrder: value }) }
                    />
                </div>
            </PanelRow>
        </div>
    );
}

function LogicHelpText() {
    return (
        <div className='max-w-[300px]'>
            AND - If 2 or more terms are selected then only the products that the selected terms (all of them) will be displayed. OR - show the products that are in at least one of the selected terms.
        </div>
    );
}