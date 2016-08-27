<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

Container::make( 'theme_options', __( 'Theme Options', 'crb' ) )
	->add_tab( __( 'Footer Options', 'crb' ), array(
		Field::make( 'text', 'crb_copyright', __( 'Copyright', 'crb' ) )->help_text( __( 'To set the current year you can use the <code>[year]</code> shortcode.<code>© [year] BOTWOO LLC</code>', 'crb' ) ),
	) )
	->add_tab( __( 'Misc', 'crb' ), array(
		Field::make( 'header_scripts', 'crb_header_script', __( 'Header Script', 'crb' ) ),
		Field::make( 'footer_scripts', 'crb_footer_script', __( 'Footer Script', 'crb' ) ),
	) );
