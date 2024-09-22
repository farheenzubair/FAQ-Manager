<?php

if ( ! defined( 'ABSPATH' ) ) 
{
    exit; 
}

class FAQMaster 
{
    public function init() 
    {
        add_action( 'init', array( $this, 'register_faq_post_type' ) );
        add_action( 'init', array( $this, 'register_faq_taxonomies' ) );
        add_action( 'init', array( $this, 'register_shortcodes' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'handle_faq_submission' ) );
        add_action( 'widgets_init', array( $this, 'register_faq_widgets' ) ); 
    }

    // Register custom post type for FAQs
    public function register_faq_post_type() 
    {
        $labels = array(
            'name'               => __( 'FAQs', 'faqmaster' ),
            'singular_name'      => __( 'FAQ', 'faqmaster' ),
            'add_new'            => __( 'Add New FAQ', 'faqmaster' ),
            'edit_item'          => __( 'Edit FAQ', 'faqmaster' ),
            'all_items'          => __( 'All FAQs', 'faqmaster' ),
            'menu_name'          => __( 'FAQs', 'faqmaster' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'has_archive'        => true,
            'supports'           => array( 'title', 'editor' ),
        );

        register_post_type( 'faq', $args );
    }

    // Register taxonomies for FAQ categories and tags
    public function register_faq_taxonomies() 
    {
        // Category taxonomy
        $category_labels = array(
            'name'              => __( 'FAQ Categories', 'faqmaster' ),
            'singular_name'     => __( 'FAQ Category', 'faqmaster' ),
        );

        register_taxonomy( 'faq_category', 'faq', array(
            'labels' => $category_labels,
            'hierarchical' => true,
        ));

        // Tag taxonomy
        $tag_labels = array(
            'name'              => __( 'FAQ Tags', 'faqmaster' ),
            'singular_name'     => __( 'FAQ Tag', 'faqmaster' ),
        );

        register_taxonomy( 'faq_tag', 'faq', array(
            'labels' => $tag_labels,
            'hierarchical' => false,
        ));
    }

    // Register shortcodes
    public function register_shortcodes() 
    {
        add_shortcode( 'faq_accordion', array( $this, 'render_faq_accordion' ) );
    }

    // Render the FAQ accordion
    public function render_faq_accordion() 
    {
        ob_start();
        include FAQMASTER_PATH . 'templates/faq-accordion.php';
        return ob_get_clean();
    }

    // Enqueue styles and scripts
    public function enqueue_scripts() 
    {
        wp_enqueue_style( 'faqmaster-style', FAQMASTER_URL . 'assets/css/faqmaster.css' );
        wp_enqueue_script( 'faqmaster-script', FAQMASTER_URL . 'assets/js/faqmaster.js', array('jquery'), null, true );
    }

    // Add admin menu for FAQs
    public function add_admin_menu() 
    {
        add_menu_page(
            __( 'FAQs', 'faqmaster' ),
            __( 'FAQs', 'faqmaster' ),
            'manage_options',
            'faqmaster',
            array( $this, 'render_faqs_page' ),
            'dashicons-format-aside'
        );
    }

    // Render the FAQs admin page
    public function render_faqs_page() 
    {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Manage FAQs', 'faqmaster' ); ?></h1>
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="faq_title"><?php _e( 'FAQ Title', 'faqmaster' ); ?></label></th>
                        <td><input name="faq_title" type="text" id="faq_title" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="faq_content"><?php _e( 'FAQ Content', 'faqmaster' ); ?></label></th>
                        <td><textarea name="faq_content" id="faq_content" rows="5" class="large-text" required></textarea></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Add FAQ', 'faqmaster' ) ); ?>
            </form>
            
            <h2><?php _e( 'Existing FAQs', 'faqmaster' ); ?></h2>
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th><?php _e( 'Title', 'faqmaster' ); ?></th>
                        <th><?php _e( 'Content', 'faqmaster' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display existing FAQs
                    $faqs = new WP_Query( array( 'post_type' => 'faq' ) );
                    if ( $faqs->have_posts() ) {
                        while ( $faqs->have_posts() ) {
                            $faqs->the_post();
                            ?>
                            <tr>
                                <td><?php the_title(); ?></td>
                                <td><?php the_content(); ?></td>
                            </tr>
                            <?php
                        }
                    } 
                    else 
                    {
                        ?>
                        <tr>
                            <td colspan="2"><?php _e( 'No FAQs found.', 'faqmaster' ); ?></td>
                        </tr>
                        <?php
                    }
                    wp_reset_postdata();
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // Handle form submission to add a new FAQ
    public function handle_faq_submission() {
        if ( isset( $_POST['faq_title'] ) && isset( $_POST['faq_content'] ) ) {
            $faq_title   = sanitize_text_field( $_POST['faq_title'] );
            $faq_content = wp_kses_post( $_POST['faq_content'] );

            $new_faq = array(
                'post_title'   => $faq_title,
                'post_content' => $faq_content,
                'post_status'  => 'publish',
                'post_type'    => 'faq',
            );

            wp_insert_post( $new_faq );
            wp_redirect( admin_url( 'admin.php?page=faqmaster' ) );
            exit;
        }
    }

    public function register_faq_widgets() {
        include_once 'class-faqmaster-widget-top.php'; 
        register_widget('FAQMaster_Widget_Top');
    }
}
