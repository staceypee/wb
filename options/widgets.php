<?php

use Carbon_Fields\Widget\Widget;
use Carbon_Fields\Field\Field;

/**
 * Register the new widget classes here so that they show up in the widget list. 
 */
function crb_register_widgets() {
	register_widget( 'CrbThemeWidgetRichText' );
}
add_action( 'widgets_init', 'crb_register_widgets' );

/**
 * Displays a block with a title and WYSIWYG rich text content
 */
class CrbThemeWidgetRichText extends Widget {
	function __construct() {
		$this->setup(
			__( 'Rich Text', 'crb' ),
			__( 'Displays a block with title and WYSIWYG content.', 'crb' ),
			array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) ),
			)
		);
	}
	
	function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		echo apply_filters( 'the_content', $instance['content'] );
	}
}
