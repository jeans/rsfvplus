<?php
class RSFV_Frontend {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            'rsfv-extended',
            plugins_url('../assets/js/rsfv-extended.js', __FILE__),
            array('jquery'),
            '1.0.0',
            true
        );
        wp_enqueue_style(
            'rsfv-play-icon',
            plugins_url('../assets/css/play-icon.svg', __FILE__)
        );
    }
}