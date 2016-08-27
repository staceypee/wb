<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;


Container::make( 'post_meta', __( 'Sections', 'crb' ) )
	->show_on_post_type( 'page' )
	->show_on_template( "templates/home.php" )
	->add_fields( array(
		Field::make( 'complex', 'crb_sections' )
			->setup_labels( array(
				'plural_name'   =>  __( 'Sections', 'crb' ),
				'singular_name' =>  __( 'Section', 'crb' ),
			) )
			->add_fields( __( 'Intro', 'crb' ), array(
				Field::make( 'text', 'crb_intro_title', __( 'Title', 'crb' ) )->set_required( true ),
				Field::make( 'textarea', 'crb_intro_content', __( 'Content', 'crb' ) ),
				Field::make( 'text', 'crb_intro_slogan', __( 'Slogan', 'crb' ) ),
				Field::make( 'image', 'crb_intro_image', __( 'Image', 'crb' ) ),
				Field::make( 'complex', 'crb_intro_buttons' )
					->setup_labels( array(
						'plural_name'   =>  __( 'Buttons', 'crb' ),
						'singular_name' =>  __( 'Button', 'crb' ),
					) )
					->add_fields( array(
						Field::make( 'text', 'btn_link', __( 'Link', 'crb' ) )->set_required( true ),
						Field::make( 'text', 'btn_label', __( 'Label', 'crb' ) ),
					) )
			))
			->add_fields(  __( 'Features', 'crb' ), array(
				Field::make( 'text', 'crb_ft_title', __( 'Title', 'crb' ) ),
				Field::make( 'complex', 'crb_features' )
					->setup_labels( array(
						'plural_name'   =>  __( 'Features', 'crb' ),
						'singular_name' =>  __( 'Feature', 'crb' ),
					) )
					->add_fields( array(
						Field::make( 'image', 'icon', __( 'Icon', 'crb' ) )->set_required( true ),
						Field::make( 'text', 'content', __( 'Content', 'crb' ) ),
					) ),
			))
			->add_fields(  __( 'Primary', 'crb' ), array(
				Field::make( 'text', 'crb_pr_title', __( 'Title', 'crb' ) ),
				Field::make( 'text', 'crb_pr_sub_title', __( 'Sub Title', 'crb' ) ),
				Field::make( 'image', 'crb_pr_image', __( 'Image', 'crb' ) )->set_required( true ),
				Field::make( 'complex', 'crb_options' )
					->setup_labels( array(
						'plural_name'   =>  __( 'Options', 'crb' ),
						'singular_name' =>  __( 'Option', 'crb' ),
					) )
					->add_fields( array(
						Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) )->set_required( true ),
					) ),
				Field::make( 'text', 'crb_pr_link', __( 'Button Link', 'crb' ) ),
				Field::make( 'text', 'crb_pr_label', __( 'Button Label', 'crb' ) ),
			))
			->add_fields(  __( 'Media', 'crb' ), array(
				Field::make( 'rich_text', 'crb_md_content', __( 'Content', 'crb' ) )->set_required( true ),
				Field::make( 'image', 'crb_md_image', __( 'Image', 'crb' ) ),
				Field::make( 'text', 'crb_md_link', __( 'Button Link', 'crb' ) ),
				Field::make( 'text', 'crb_md_label', __( 'Button Label', 'crb' ) )
			))
			->add_fields(  __( 'Team', 'crb' ), array(
				Field::make( 'text', 'crb_team_title', __( 'Title', 'crb' ) ),
				Field::make( 'complex', 'crb_employees' )
					->setup_labels( array(
						'plural_name'   =>  __( 'Employees', 'crb' ),
						'singular_name' =>  __( 'Employee', 'crb' ),
					) )
					->add_fields( array(
						Field::make( 'image', 'image', __( 'Image', 'crb' ) )->set_required( true ),
						Field::make( 'text', 'name', __( 'Name', 'crb' ) )->set_required( true ),
						Field::make( 'text', 'position', __( 'Position', 'crb' ) ),
						Field::make( 'text', 'information', __( 'Information', 'crb' ) ),
					) ),
			))
	));

Container::make( 'post_meta', __( 'Plans', 'crb' ) )
    ->show_on_post_type( 'page' )
    ->show_on_template( "templates/pricing.php" )
    ->add_fields( array(
        Field::make( 'complex', 'crb_pr_plans', '' )
            ->setup_labels( array(
                'plural_name'   =>  __( 'Pricing Plans', 'crb' ),
                'singular_name' =>  __( 'Pricing Plan', 'crb' ),
            ) )
            ->add_fields( array(
                Field::make( 'text', 'option', __( 'Option', 'crb' ) ),
                Field::make( 'text', 'type', __( 'Type', 'crb' ) )->set_required( true ),
                Field::make( 'rich_text', 'crb_benefits' )->help_text( __( 'Use an unordered list to add the benefits. Bold lines will appear with a checkmark in front.', 'crb' ) ),
                Field::make( 'text', 'btn_text', __( 'Button Text', 'crb' ) ),
                Field::make( 'text', 'btn_link', __( 'Button Link', 'crb' ) ),
            ) )
    ) );
