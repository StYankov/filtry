<?php

namespace Filtry\Core;

class Bootstrap {
    public function __construct() {
        $this->init();

        add_action( 'widgets_init', [ $this, 'register_widget' ] );
    }

    public function init() {
        new Query();
        new RestAPI();
    }

    public function register_widget() {
        register_widget( Widget::class );
    }
}