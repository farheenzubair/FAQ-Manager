<?php

if ( ! defined( 'ABSPATH' ) ) 
{
    exit; 
}

class FAQMaster_Widget_Top extends WP_Widget 
{

    public function __construct() {
        parent::__construct(
            'faqmaster_widget_top',
            __( 'FAQ Master Top Widget', 'faqmaster' ),
            array( 'description' => __( 'A widget to display FAQs.', 'faqmaster' ) )
        );
    }

    public function widget( $args, $instance ) 
    {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) 
        {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo '<p>This is a test output from the FAQ widget!</p>'; 
        echo $args['after_widget'];
    }

    public function form( $instance ) 
    {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'faqmaster' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'faqmaster' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) 
    {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }
}
