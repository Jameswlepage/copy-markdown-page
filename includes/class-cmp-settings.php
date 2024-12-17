<?php
if (! defined('ABSPATH')) {
    exit;
}

class CMP_Settings
{

    private static $option_name = '';

    public static function init($option_name)
    {
        self::$option_name = $option_name;
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
    }

    public static function register_settings()
    {
        register_setting('copy_markdown_page_group', self::$option_name, array(
            'sanitize_callback' => array(__CLASS__, 'sanitize_settings'),
        ));

        add_settings_section(
            'copy_markdown_page_section',
            'Copy Markdown Page Settings',
            array(__CLASS__, 'settings_section_callback'),
            'copy_markdown_page'
        );

        add_settings_field(
            'post_types',
            'Post Types',
            array(__CLASS__, 'field_post_types_callback'),
            'copy_markdown_page',
            'copy_markdown_page_section'
        );

        add_settings_field(
            'auto_insert',
            'Auto Insert Button',
            array(__CLASS__, 'field_auto_insert_callback'),
            'copy_markdown_page',
            'copy_markdown_page_section'
        );

        add_settings_field(
            'button_text',
            'Button Text',
            array(__CLASS__, 'field_button_text_callback'),
            'copy_markdown_page',
            'copy_markdown_page_section'
        );
    }

    public static function sanitize_settings($input)
    {
        $output = array();
        // Post types
        $output['post_types'] = array();
        if (isset($input['post_types']) && is_array($input['post_types'])) {
            foreach ($input['post_types'] as $pt) {
                $pt_obj = get_post_type_object($pt);
                if ($pt_obj) {
                    $output['post_types'][] = $pt;
                }
            }
        }

        // Auto insert
        $allowed_positions = array('none', 'before', 'after');
        $output['auto_insert'] = in_array($input['auto_insert'], $allowed_positions, true) ? $input['auto_insert'] : 'none';

        // Button text
        $output['button_text'] = isset($input['button_text']) ? sanitize_text_field($input['button_text']) : 'Copy Page';

        return $output;
    }

    public static function settings_section_callback()
    {
        echo '<p>Configure how the “Copy Markdown” button behaves.</p>';
    }

    public static function field_post_types_callback()
    {
        $options = get_option(self::$option_name);
        $post_types = get_post_types(array('public' => true), 'objects');
        echo '<ul>';
        foreach ($post_types as $type => $obj) {
            $checked = (in_array($type, $options['post_types'], true)) ? 'checked="checked"' : '';
            echo '<li><label><input type="checkbox" name="' . esc_attr(self::$option_name) . '[post_types][]" value="' . esc_attr($type) . '" ' . $checked . '> ' . esc_html($obj->labels->singular_name) . '</label></li>';
        }
        echo '</ul>';
    }

    public static function field_auto_insert_callback()
    {
        $options = get_option(self::$option_name);
?>
        <select name="<?php echo esc_attr(self::$option_name); ?>[auto_insert]">
            <option value="none" <?php selected($options['auto_insert'], 'none'); ?>>Do not auto insert</option>
            <option value="before" <?php selected($options['auto_insert'], 'before'); ?>>Before Content</option>
            <option value="after" <?php selected($options['auto_insert'], 'after'); ?>>After Content</option>
        </select>
    <?php
    }

    public static function field_button_text_callback()
    {
        $options = get_option(self::$option_name);
    ?>
        <input type="text" name="<?php echo esc_attr(self::$option_name); ?>[button_text]" value="<?php echo esc_attr($options['button_text']); ?>">
<?php
    }

    public static function add_settings_page()
    {
        add_options_page(
            'Copy Markdown Page',
            'Copy Markdown Page',
            'manage_options',
            'copy_markdown_page',
            array(__CLASS__, 'render_settings_page')
        );
    }

    public static function render_settings_page()
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        echo '<div class="wrap">';
        echo '<h1>Copy Markdown Page Settings</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('copy_markdown_page_group');
        do_settings_sections('copy_markdown_page');
        submit_button();
        echo '</form>';
        echo '</div>';
    }
}
