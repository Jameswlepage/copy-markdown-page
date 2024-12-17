<?php
if (! defined('ABSPATH')) {
    exit;
}

class CMP_Shortcode
{

    private static $option_name = '';

    public static function init($option_name)
    {
        self::$option_name = $option_name;
        add_shortcode('copy_markdown', array(__CLASS__, 'render_shortcode'));
    }

    public static function render_shortcode()
    {
        return self::get_button_html();
    }

    public static function get_button_html()
    {
        $options = get_option(self::$option_name);
        $button_text = isset($options['button_text']) ? $options['button_text'] : 'Copy Page';

        // Using Dashicons: dashicons-clipboard for copy, dashicons-yes for check
        $html  = '<div class="cmp-copy-container">';
        $html .= '<button class="cmp-copy-btn" type="button">';
        $html .= '<span class="icon icon-copy dashicons dashicons-clipboard" aria-hidden="true"></span>';
        $html .= '<span class="icon icon-check dashicons dashicons-yes" aria-hidden="true"></span>';
        $html .= '<span class="copy-text">' . esc_html($button_text) . '</span>';
        $html .= '</button>';
        $html .= '</div>';
        return $html;
    }
}
