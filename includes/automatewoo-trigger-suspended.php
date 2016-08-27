<?php
/**
 * This is an example trigger that is triggered via a WordPress action and includes a user data item.
 *
 * This example is triggered with the following code
 * do_action('woocommerce_subscription_status_updated', $user_id );
 *
 * @class AW_Trigger_Subscription_Status_Suspended
 */
class AW_Trigger_Subscription_Status_Suspended extends AW_Trigger
{
	/**
	 * A unique ID for the trigger
	 * @var string
	 */
	public $name = 'subscription_status_suspended';

	/**
	 * @var bool
	 */
	public $_doing_payment = false;

	/**
	 * Define which data items this trigger will supply to the actions
	 * @var array
	 */
	public $supplied_data_items = array( 'user' );
	/**
	 * Construct
	 */
	public function init()
	{
		$this->title = __('Subscription Status Suspended', 'automatewoo-custom');
		// Registers the trigger
		parent::init();
	}
	/**
	 * Add any fields to the trigger. Can be left blank.
	 */
	public function load_fields()
	{
		$product = new AW_Field_Subscription_Products();
		$product->set_description( __( 'Select which subscription products to trigger for. Leave blank to apply for all subscription products.', 'automatewoo'  ) );

		$this->add_field($product);
		$this->add_user_tags_field();
		// Allows a limit per user to be added when this trigger is used on a workflow
		$this->add_user_limit_field();
	}
	/**
	 * Defines when the trigger is run
	 */
	public function register_hooks()
	{
		// Whenever a renewal payment is due subscription is placed on hold and then back to active if successful
		// Block this trigger while this happens
		add_action( 'woocommerce_custom_scheduled_subscription_payment', array( $this, 'before_payment' ), 0, 1 );
		add_action( 'woocommerce_custom_scheduled_subscription_payment', array( $this, 'after_payment' ), 1000, 1 );

		add_action( 'woocommerce_custom_subscription_status_updated', array( $this, 'catch_hooks' ), 10, 3 );
	}

	/**
	 * @param $subscription_id
	 */
	function before_payment( $subscription_id )
	{
		$this->_doing_payment = true;
	}


	/**
	 * @param $subscription_id
	 */
	function after_payment( $subscription_id )
	{
		$this->_doing_payment = false;

		$subscription = wcs_get_subscription( $subscription_id );

		if ( ! $subscription->has_status( 'active' ) )
		{
			// if status was changed (no longer active) during payment trigger now
			$this->catch_hooks( $subscription, $subscription->get_status(), 'active' );
		}
	}


	/**
	 * Route hooks through here
	 *
	 * @param $subscription WC_Subscription
	 * @param string $old_status
	 */
	public function catch_hooks( $subscription, $new_status, $old_status )
	{
		if ( $this->_doing_payment ) return;

		// bit of hack to pass in old status
		$subscription->_aw_old_status = $old_status;

		$this->maybe_run(array(
			'subscription' => $subscription,
			'user' => $subscription->get_user()
		));
	}

	/**
	 * Performs any validation if required. If this method returns true the trigger will fire.
	 *
	 * @param $workflow AW_Model_Workflow
	 *
	 * @return bool
	 */
	public function validate_workflow( $workflow )
	{
		// Get the trigger object with options loaded
		$trigger = $workflow->get_trigger();
		// Get any data items
		$user = $workflow->get_data_item('user');
		// don't trigger if there is no user
		if ( ! $user )
			return false;
		// validate user limit
		if ( ! $this->validate_limit_per_user( $workflow, $trigger, $user ) )
			return false;
		return true;
	}
}