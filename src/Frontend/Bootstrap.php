<?php

namespace Filtry\Frontend;

class Bootstrap {
    public function __construct() {
        new Enqueue();
        new WooCommerce();
        new TemplateHooks();
    }
}