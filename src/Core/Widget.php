<?php

namespace Filtry\Core;

use Filtry\Filtry;
use Filtry\Utils\View;

class Widget extends \WP_Widget {
    public function __construct() {
        parent::__construct(
            'filtry_widget',
            __( 'Filtry WooCommerce Filters', Filtry::get_text_domain() ),
            [
                'description' => __( 'Products filters', Filtry::get_text_domain() )
            ]
        );
    }

    /**
     * Widget front end
     */
    public function widget( $args, $instance ) {
        $args['title'] = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];

        // Render frontend
        View::render_widget( $args );

        echo $args['after_widget'];
    }

    /**
     * Widget backend
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
           $title = $instance[ 'title' ];
        } else {
            $title = __( 'Filters', Filtry::get_text_domain() );
        }
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                <input 
                    class="widefat" 
                    id="<?php echo $this->get_field_id( 'title' ); ?>" 
                    name="<?php echo $this->get_field_name( 'title' ); ?>" 
                    type="text" 
                    value="<?php echo esc_attr( $title ); ?>"
                />
            </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance          = [];
        $instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;   
    }
}