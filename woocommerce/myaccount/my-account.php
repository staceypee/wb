<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * My Account navigation.
 * @since 2.6.0
 */

wc_print_notices();


?>
<style>

	.woocommerce-account table {
			font-family: Roboto;
	}
	.woocommerce-account table tbody tr:nth-child(even) {
			background-color: #ececec;
	}
	.woocommerce-account table tbody tr td i.connected-on {
			color: #f8d12c;
	}
	.woocommerce-account table tbody tr td i.connected-off {
			color: #dadada;
	}
 .woocommerce-account  table tbody tr td {
			vertical-align: middle;
			height: 50px;
	}
	.woocommerce-account table tbody tr td i {
			font-size: 36px !important;
	}

	.woocommerce-account table.shop_table thead tr th,
	.woocommerce-account table thead tr th {
			text-align: left;
			font-size: 12px;
			text-transform: uppercase;
	}
	.woocommerce-account table tbody {
			font-size: 16px;
	}
	.woocommerce-account  a.btn,
	.woocommerce-account  a.button {
			font-family: Roboto;
			font-size: 16px;
			margin-top: 0px;
			text-transform: uppercase;
			text-decoration: none;
	}
	.woocommerce-account a.btn:hover {
			text-decoration: none;
	}

	.woocommerce-account .isloading-wrapper {
		/*background: rgba(255,255,255,0);*/
		text-align:center;
		width:100% !important;
	}
	.woocommerce-account .isloading-overlay {
			width: 74%;
			font-size:52px;
			font-family:Roboto;
			/*background: rgba(255,255,255,0.8); */
			position: absolute;
	    z-index: 10000;
	}

	.woocommerce-account .article-head {
		margin-top: 20px;
		text-transform: uppercase;
	}
	.woocommerce-account .article {
		padding-bottom: 10px;
	}
	.woocommerce-account a.button {
		margin-bottom: 0px;
	}
	.woocommerce table.shop_table,
	.woocommerce table.shop_table thead,
	.woocommerce table.shop_table tbody {
		border:0px;
		border-image-width:0px;
	}
	.woocommerce table.shop_table tr {
		border:0px;
		border-bottom: 0px;
		border-image-width:0px;
	}
	.woobotjqueryui .ui-widget {
		font-family: Roboto;
		font-size: 16px;
	}

	.woocommerce-account address {
		font-size: 16px;
	}
</style>


<div class="intro" style="border: 0px;">
    <div class="intro-content">
		<h1>My Account</h1>
		<p>
			<?php
			   echo sprintf( esc_attr__( 'Hello %s%s%s (not %2$s? %sSign out%s)', 'woocommerce' ), '<strong>', esc_html( $current_user->display_name ), '</strong>', '<a href="' . esc_url( wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) ) ) . '">', '</a>' );
			?>
	  </p>
		<p>
			<a href="/myaccount/edit-account">Change Password</a>
	  </p>
	</div><!-- /.intro-content -->
</div><!-- /.intro -->

<div class="content content-fullwidth" >
  <div class="article article-head">
    <h1 class="pageTitle">MY CONNECTIONS</h1>
    <p>Create up to 5 connections with your subscription</p>
  </div>
  <div class="section-body" >
    <table class="shop_table_responsive" id="myconnections_top">
        <thead>
            <tr>
                <th colspan="1">STORE NAME</th>
                <th colspan="2">STATUS</th>
                <th colspan="1">SLACK TEAM</th>
								<th>Slack Channel</th>
            </tr>
        </thead>
        <tbody id="myconnections_tbody">
<?php
	 echo get_my_connections_html();
?>
        </tbody>
    </table>
  </div>  <!-- section-body -->

	<div class="article article-head">
    <h1 class="pageTitle">My Cards</h1>
  </div>
  <div class="section-body">
<?php
  /*  payment methods */
  include 'payment-methods.php';
?>
    </div> <!-- section-body -->

  <div class="article article-head">
    <h1 class="pageTitle">My Address</h1>
  </div>
  <div class="section-body">
<?php
  /*  payment methods */
  include 'my-address.php';
?>
	</div> <!-- section-body -->


  <div class="article article-head" >
    <h1 class="pageTitle">My Payments</h1>
  </div>
  <div class="section-body">
    <?php
			/* $order_count = 5; */
			include 'my-orders.php';
		?>
  </div> <!-- section-body -->
</div>




<?php  /* end of page */ ?>
<script type="text/javascript">

    jQuery(document).on({
        ajaxStart: function() { pleaseWait(true); },
        ajaxStop: function() { pleaseWait(false); }
    });

    function pleaseWait(showit) {
       if ( showit )  {
            jQuery("#myconnections_top").isLoading({
                text: "<i class='fa fa-circle-o-notch fa-spin'/>",
                position: "overlay",
                'transparency': 0.8,

            });
        }
        else {
            jQuery("#myconnections_top").isLoading("hide");
					  jQuery(".channel_selector").selectmenu();
        }
    }

    function addConnection(subsid) {

        var data = {
            'action': 'woobot_myconnections_action',
            'dothis': 'new',
            'subscription_id' : subsid
        };
        jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
            document.getElementById('myconnections_tbody').innerHTML = response;
        });
    }
    function deleteConnection(connid) {
        var data = {
            'action': 'woobot_myconnections_action',
            'dothis': 'delete',
            'connection_id' : connid
        };

				if (confirm("Are you sure you want to delete this connection?") ) {

	        jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
	            document.getElementById('myconnections_tbody').innerHTML = response;
	        });
				}
    }

    function repaintConnections() {
        var data = {
            'action': 'woobot_myconnections_action',
            'dothis': 'show'
        };
        jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
            document.getElementById('myconnections_tbody').innerHTML = response;
        });
    }
    jQuery(document).ready(function() {
        jQuery("body").addClass("woobotjqueryui");
				repaintConnections();
    });
</script>

<?php
?>
