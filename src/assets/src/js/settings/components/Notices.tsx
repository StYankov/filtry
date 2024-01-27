import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { store } from '@wordpress/notices';
import { Notice } from '@wordpress/components';

export default function() {
    const notices = useSelect( ( select ) => select( store ).getNotices(), [] );
    const { removeNotice } = useDispatch( store );

    useEffect(() => {
        for(const notice of notices) {
            if(notice.status === 'success') {
                setTimeout(() => {
                    removeNotice(notice.id);
                }, 7500);
            }
        }
    }, [notices]);

    return (
        <div>
            { 
                notices.map(notice => (
                        <Notice 
                            className='mx-0 mb-4'
                            key={ notice.id } 
                            status={ notice.status as ('warning' | 'success' | 'error' | 'info' ) }
                            onRemove={ () => removeNotice(notice.id) }
                        >
                            { notice.content }
                        </Notice>
                    )
                )
            }
        </div>
    );
}