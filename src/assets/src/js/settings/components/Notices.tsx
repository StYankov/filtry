import { useSelect } from '@wordpress/data';
import { store } from '@wordpress/notices';
import { Notice } from '@wordpress/components';

export default function() {
    const notices = useSelect( ( select ) => 
        select( store ).getNotices(),
        []
    );

    return (
        <div>
            { notices.map(notice => <Notice key={ notice.id } status={ notice.status as ('warning' | 'success' | 'error' | 'info' ) }>{ notice.content }</Notice>)}
        </div>
    );
}