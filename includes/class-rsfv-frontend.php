<?php

class RSFV_Frontend {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('rsfv_video_html', array($this, 'modify_video_html'), 10, 3);
    }

    public function enqueue_scripts() {
        wp_enqueue_script('rsfv-extended', plugins_url('/assets/js/rsfv-extended.js', __FILE__), array('jquery'), '1.1.0', true);
        wp_enqueue_style('rsfv-admin-settings', plugins_url('/assets/css/admin-settings.css', __FILE__), false, '1.1.0', 'all');
        wp_localize_script('rsfv-extended', 'rsfvSettings', [
            'scrollPlaySingleDesktop' => get_option('rsfv_scroll_play_single_desktop', 'off'),
            'scrollPlaySingleMEW' => get_option('rsfv_scroll_play_single_mew', 'off'),
            'mouseoverPlayListsDesktop' => get_option('rsfv_mouseover_play_lists_desktop', 'off'),
            'scrollPlayListsMEW' => get_option('rsfv_scroll_play_lists_mew', 'off'),
            'autoplaySingleDesktop' => get_option('rsfv_autoplay_single_desktop', 'off'),
            'autoplaySingleMEW' => get_option('rsfv_autoplay_single_mew', 'off'),
            'showPlayButtonLists' => get_option('rsfv_show_play_button_lists', 'off'),
            'aspectRatio' => get_option('rsfv_video_aspect_ratio', '16:9'),
            'lazyLoading' => get_option('rsfv_lazy_loading', 'off'),
        ]);
    }

    public function modify_video_html($html, $post_id, $context) {
        $aspect_ratio = get_option('rsfv_video_aspect_ratio', '16:9');
        $lazy_loading = get_option('rsfv_lazy_loading', 'off');

        $ratio_map = [
            '16:9' => 'padding-top:56.25%',
            '1:1' => 'padding-top:100%',
            '3:2' => 'padding-top:66.66%',
            '4:3' => 'padding-top:75%',
        ];
        $aspect_style = isset($ratio_map[$aspect_ratio]) ? $ratio_map[$aspect_ratio] : $ratio_map['16:9'];

        if ($lazy_loading === 'on') {
            $html = preg_replace('/<video([^>]*)>/', '<video$1 loading="lazy">', $html);
        }

        if ($context === 'list' && get_option('rsfv_show_play_button_lists', 'off') === 'on') {
            $html = '<div class="rsfv-poster-wrap">' . $html . '<span class="rsfv-play-btn"></span></div>';
        }

        $html = '<div class="rsfv-video-container" style="'.$aspect_style.'">' . $html . '</div>';

        return $html;
    }
}
