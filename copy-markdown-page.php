<?php

/**
 * Plugin Name: Copy Markdown Page
 * Description: Provides a shortcode and automatic insertion of a button that converts a page's HTML to Markdown and copies it to the clipboard.
 * Version: 1.0.0
 * Author: James LePage
 * License: GPLv2 or later
 */

if (! defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CMP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CMP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include class files
require_once CMP_PLUGIN_DIR . 'includes/class-cmp-plugin.php';
require_once CMP_PLUGIN_DIR . 'includes/class-cmp-settings.php';
require_once CMP_PLUGIN_DIR . 'includes/class-cmp-shortcode.php';
require_once CMP_PLUGIN_DIR . 'includes/class-cmp-injector.php';

// Initialize the main plugin class
add_action('plugins_loaded', array('CMP_Plugin', 'get_instance'));
