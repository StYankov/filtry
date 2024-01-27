import { SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import Filter from '../../types/Filter';
import useSettingsStore from '../../store/settingsStore';

type Props = {
    filter: Filter
}

export default function FilterDetails({ filter }: Props) {
    const updateFilter = useSettingsStore(state => state.updateFilter);

    return (
        <div className="filter-details flex gap-3">
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
                onChange={ value => updateFilter(filter.id, { sortOrder: value }) }
            />
            <ToggleControl
                className='mt-6'
                label={ __('Collapsable', 'filtry') }
                help={ __( 'Enable the category box to be collapsable', 'filtry' ) }
                checked={ filter.collapsable }
                onChange={ (value) => updateFilter(filter.id, { collapsable: value }) }
            />
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