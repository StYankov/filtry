import { Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Header() {
    return (
        <div className="filtry-plugin__header">
            <div className="filtry-plugin__container">
                <div className="filtry-plugin__title">
                    <h1>{ __( 'Filtry Settings', 'filtry' ) } <Icon icon="admin-settings" /></h1>
                </div>
            </div>
        </div>
    );
}