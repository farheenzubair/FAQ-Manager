<?php
/**
 * Plugin Name: FAQMaster
 * Plugin URI: https://example.com/faqmaster
 * Description: A plugin to manage FAQs with categories, tags, likes/dislikes, widgets, and search functionality.
 * Version: 1.1
 * Author: Farheen
 * Author URI: https://example.com
 * Text Domain: faqmaster
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) 
{
    exit; 
}

define( 'FAQMASTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'FAQMASTER_URL', plugin_dir_url( __FILE__ ) );

// Include the main class file
require_once FAQMASTER_PATH . 'includes/class-faqmaster.php';

// Initialize the plugin
function faqmaster_init() 
{
    $faqmaster = new FAQMaster();
    $faqmaster->init();
}
add_action( 'plugins_loaded', 'faqmaster_init' );

// Enqueue scripts
function faq_enqueue_scripts() 
{
    wp_enqueue_script('faq-like-dislike', plugin_dir_url(__FILE__) . 'js/faq-like-dislike.js', array('jquery'), null, true);
    wp_localize_script('faq-like-dislike', 'faq_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'faq_enqueue_scripts');

// AJAX handler for like/dislike
function update_faq_reaction() 
{
    if (isset($_POST['faq_id']) && isset($_POST['reaction'])) 
    {
        $faq_id = intval($_POST['faq_id']);
        $reaction = sanitize_text_field($_POST['reaction']);

        $newCount = 10; 

        wp_send_json_success(array('newCount' => $newCount));
    }

    wp_send_json_error();
}
add_action('wp_ajax_update_faq_reaction', 'update_faq_reaction');
add_action('wp_ajax_nopriv_update_faq_reaction', 'update_faq_reaction');

