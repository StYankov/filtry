import clsx from 'clsx';
import { BaseControl, ColorPicker, Popover } from '@wordpress/components';
import { useRef, useState } from 'react';
import { isColorLight } from '../../utils/colors';

type Props = {
    label: string;
    value: string;
    onChange: ( value: string ) => void;
    help?: string;
}

export function ColorField( { label, value, onChange, help = null }: Props ): JSX.Element {
    const rootRef = useRef< HTMLDivElement >( null );
    const [ showPicker, setShowPicker ] = useState< boolean >( false );

    return (
        <div ref={ rootRef } className='color-field flex mb-2'>
            <BaseControl
                label={ label }
                help={ help }
            >
                <button
                    type='button'
                    className={ clsx(
                        'block w-28 h-10 cursor-pointer border-0 text-sm',
                        {
                            'text-black': isColorLight( value ),
                            'text-white': ! isColorLight( value )
                        }
                    ) }
                    style={ { backgroundColor: value } }
                    onClick={ () => setShowPicker( ! showPicker ) }
                >
                    { value }
                </button>
            </BaseControl>

            { showPicker && (
                <Popover
                    anchor={ rootRef.current }
                    onFocusOutside={ () => setShowPicker( false ) }
                >
                    <ColorPicker
                        color={ value }
                        onChange={ onChange }
                    />
                </Popover>
            ) }
        </div>  
    );
}
