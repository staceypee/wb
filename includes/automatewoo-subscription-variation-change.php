<?php
/**
 * This is an example trigger that is triggered via a WordPress action and includes a user data item.
 *
 * This example is triggered with the following code
 * do_action('woocommerce_subscription_status_updated', $user_id );
 *
 * @class AW_Trigger_Subscription_Status_Suspended
 */
class AW_Trigger_Subscription_Variation_Changed extends AW_Trigger
{
	/**
	 * A unique ID for the trigger
	 * @var string
	 */
	public $name = 'subscription_variation_changed';
	/**
	 * Define which data items this trigger will supply to the actions
	 * @var array
	 */
	public $supplied_data_items = array( 'user' );
	/**
	 * @var bool
	 */
	public $_doing_payment = false;

	/**
	 * Construct
	 */
	public function init()
	{
		$this->title = __('Subscription Variation Changed', 'automatewoo');

		// Registers the trigger
		parent::init();
	}

	/**
	 * Add options to the trigger
	 */
	public function load_fields()
	{
		$product = new AW_Field_Subscription_Products();
		$product->set_description( __( 'Select which subscription products to trigger for. Leave blank to apply for all subscription products.', 'automatewoo'  ) );

		$from = new AW_Field_Subscription_Variation_Status();
		$from->set_title( __( 'Status Changes From', 'automatewoo'  ) );
		$from->set_name('subscription_status_from');
		$from->set_placeholder(__( 'Leave blank for any status', 'automatewoo'  ));
		$from->multiple = true;

		$to = new AW_Field_Subscription_Variation_Status();
		$to->set_title( __( 'Status Changes To', 'automatewoo'  ) );
		$to->set_name('subscription_status_to');
		$to->set_placeholder(__( 'Leave blank for any status', 'automatewoo'  ));
		$to->multiple = true;

		$recheck_status_before_run = new AW_Field_Checkbox();
		$recheck_status_before_run->set_name('validate_order_status_before_queued_run');
		$recheck_status_before_run->set_title("Recheck Status Before Run");
		$recheck_status_before_run->default_to_checked = true;
		$recheck_status_before_run->set_description(
			__( "This is useful for Workflows that are not run immediately as it ensures the status of the subscription hasn't changed since initial trigger." ,
				'automatewoo'  ) );

		$this->add_field($product);
		$this->add_field($from);
		$this->add_field($to);
		$this->add_user_tags_field();
		$this->add_user_limit_field();
	}



	/**
	 * When might this trigger run?
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
	 * @param $workflow AW_Model_Workflow
	 *
	 * @return bool
	 */
	public function validate_workflow( $workflow )
	{
		$trigger = $workflow->get_trigger();
		$user = $workflow->get_data_item('user');

		/** @var $subscription WC_Subscription */
		$subscription = $workflow->get_data_item('subscription');

		if ( ! $user || ! $subscription )
			return false;

		// options
		$status_from = $workflow->get_trigger_option('subscription_status_from');
		$status_to = $workflow->get_trigger_option('subscription_status_to');

		if ( ! $this->validate_status_field( $status_from, $subscription->_aw_old_status ) )
			return false;

		if ( ! $this->validate_status_field( $status_to, $subscription->get_status() ) )
			return false;

		if ( ! $this->validate_subscription_products_field( $subscription, $trigger ) )
			return false;

		if ( ! $this->validate_user_tag_fields( $user, $trigger ) )
			return false;

		if ( ! $this->validate_limit_per_user( $workflow, $trigger, $user ) )
			return false;

		return true;
	}



	/**
	 * Ensures 'to' status has not changed while sitting in queue
	 *
	 * @param $workflow
	 *
	 * @return bool
	 */
	public function validate_before_queued_event( $workflow )
	{
		// check parent
		if ( ! parent::validate_before_queued_event( $workflow ) )
			return false;

		$trigger = $workflow->get_trigger();
		$user = $workflow->get_data_item('user');

		/** @var $subscription WC_Subscription */
		$subscription = $workflow->get_data_item('subscription');

		if ( ! $user || ! $subscription )
			return false;

		// Option to validate order status
		if ( $workflow->get_trigger_option('validate_order_status_before_queued_run') )
		{
			$status_to = $workflow->get_trigger_option('subscription_status_to');

			if ( ! $this->validate_status_field( $status_to, $subscription->get_status() ) )
				return false;
		}

		return true;
	}
}