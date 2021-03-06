<?php
/**
 * @class       AW_Field_Subscription_Status
 * @package     AutomateWoo/Fields
 * @since       2.1.0
 */

class AW_Field_Subscription_Variation_Status extends AW_Field_Select
{
	protected $default_title = 'Subscription Status';

	protected $default_name = 'subscription_status';

	protected $type = 'select';


	/**
	 * @param bool $allow_all
	 */
	public function __construct( $allow_all = true )
	{
		if ( $allow_all )
			$this->set_placeholder('- Any -');
		else
			$this->set_placeholder('- Select -');

		$this->set_description( __( 'Select which subscription status to preform trigger.', 'automatewoo'  ) );

		$statuses = custom_wcs_get_subscription_variation_status();

		$this->set_options( $statuses );
	}


}