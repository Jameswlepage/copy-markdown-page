<?php
if (! defined('ABSPATH')) {
    exit;
}

class CMP_Plugin
{

    private static $instance = null;
    private $option_name = 'copy_markdown_page_settings';

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Initialize settings
        CMP_Settings::init($this->option_name);

        // Initialize shortcode
        CMP_Shortcode::init($this->option_name);

        // Initialize injector
        CMP_Injector::init($this->option_name);

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function activate()
    {
        $default = array(
            'post_types'   => array('post'),
            'auto_insert'  => 'none',
            'button_text'  => 'Copy Page',
        );
        if (! get_option($this->option_name)) {
            add_option($this->option_name, $default);
        }
    }

    public function enqueue_assets()
    {
        if (is_singular()) {
            $options = get_option($this->option_name);
            $post_type = get_post_type();
            if (! empty($options['post_types']) && in_array($post_type, $options['post_types'], true)) {
                // Enqueue Turndown from CDN
                wp_enqueue_script('turndown-js', 'https://unpkg.com/turndown/dist/turndown.js', array(), null, true);

                // Enqueue our JS
                wp_enqueue_script('cmp-copy-js', CMP_PLUGIN_URL . 'assets/js/copy.js', array('turndown-js'), '1.0.0', true);

                // Localize script to supply button text (if needed)
                wp_localize_script('cmp-copy-js', 'cmpCopySettings', array(
                    'buttonText' => isset($options['button_text']) ? $options['button_text'] : 'Copy Page',
                ));

                // Enqueue CSS
                wp_enqueue_style('cmp-style', CMP_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.0');
            }
        }
    }
}
