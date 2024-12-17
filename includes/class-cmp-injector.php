<?php
if (! defined('ABSPATH')) {
    exit;
}

class CMP_Injector
{

    private static $option_name = '';

    public static function init($option_name)
    {
        self::$option_name = $option_name;
        add_filter('the_content', array(__CLASS__, 'maybe_inject_button'));
    }

    public static function maybe_inject_button($content)
    {
        if (! is_singular()) {
            return $content;
        }

        $options = get_option(self::$option_name);
        $post_type = get_post_type();

        if (empty($options['post_types']) || ! in_array($post_type, $options['post_types'], true)) {
            return $content;
        }

        $button_html = CMP_Shortcode::get_button_html();

        switch ($options['auto_insert']) {
            case 'before':
                return $button_html . $content;
            case 'after':
                return $content . $button_html;
            default:
                return $content;
        }
    }
}
