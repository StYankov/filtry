import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { useState } from '@wordpress/element';
import { PanelRow, ToggleControl, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import FilterDetails from './FilterDetails';
import Filter from '../../types/Filter';
import useSettingsStore from '../../store/settingsStore';

type Props = {
    filter: Filter,
    id: string
}

export default function FilterComponent({ filter, id }: Props) {
    const [showDetails, setShowDetails] = useState(false);
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
        <div ref={setNodeRef} style={style} {...attributes}>
            <PanelRow className='flex-col items-start border-0 border-solid border-b border-b-slate-200 mb-3'>
                <h3 className="text-underline">{ filter.label }</h3>
                <div className='filter-controls flex gap-3'>
                    <button 
                        className='
                            bg-transparent 
                            border-0 
                            cursor-pointer 
                            h-10 
                            min-w-[40px] 
                            p-0
                            mt-4
                        '
                        type='button' 
                        {...listeners}
                    >
                        <span className="dashicons dashicons-move"></span>
                    </button>

                    <ToggleControl
                        className='mt-7'
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
                    <div className='max-w-[180px]'>
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
                    </div>

                    <button 
                        className='bg-transparent border-0 h-10 cursor-pointer mt-5 transition'
                        style={{ transform: showDetails ? 'rotate(180deg)' : 'rotate(0deg)' }}
                        type='button' 
                        onClick={ () => setShowDetails(state => ! state) }
                    >
                        <span className="dashicons dashicons-arrow-down-alt2"></span>
                    </button>
                </div>
                <div 
                    className='overflow-hidden'
                    style={{ transition: '0.3s max-height ease-out', maxHeight: showDetails ? 400 : 0 }}
                >
                    <FilterDetails filter={ filter } />
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