<?php


/*  Define Features post type */
function connection_register() {
   register_post_type('connection',
     array(
         'labels'                => array(
            'name'                  => 'Connections',
            'singular_name'         => 'Connection',
            'add_new'               => __('Add Connection'),
            'add_new_item'          => __('Add New Connection'),
            'edit'                  => __('Edit'),
            'edit_item'             => __('Edit'),
            'new_item'              => __('New Connection'),
            'view'                  => __('View Connection'),
            'view_item'             => __('View Connection'),
            'search_items'          => __('Search Connection'),
            'not_found'             => __('Nothing found'),
            'not_found_in_trash'    => __('Nothing found in Trash'),
            'menu_name'             => __('Connections'),
         ),
         'public'                => false,
         'show_ui'               => true,
         'capability_type'       => 'post',
         'map_meta_cap'          => true,
         'publicly_queryable'    => false,
         'exclude_from_search'   => true,
         'hierarchical'          => false,
         'rewrite'               => false,
         'query_var'             => true,
         'supports'              => array('title', 'comments', 'custom-fields'),
         'show_in_nav_menus'     => true,
         'show_in_menu'          => 'woocommerce',
         'menu_position'         => 5

   ));

}
add_action('init', 'connection_register', 99);

/*

  My Account AJAX functions

*/


// Add Shortcode
function shortcode_woobot_customer_id( ) {

   $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
   ) ) );

  $order_id = '';
  foreach ( $customer_orders as $customer_order ) {
    $order_id = $customer_order->{'ID'};
  }

  $woobotcustomerid = get_post_meta($order_id, 'woobotcustomerid', true);
  if ( $woobotcustomerid == '' ) {
    return "None";
  }
  return $woobotcustomerid ;

}

function shortcode_woobot_provision_status() {
   $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => wc_get_order_types( 'view-orders' ),
        'post_status' => array_keys( wc_get_order_statuses() )
   ) ) );

  $order_id = '';
  foreach ( $customer_orders as $customer_order ) {
    $order_id = $customer_order->{'ID'};
  }
  $woobotcustomerid = get_post_meta($order_id, 'woobotcustomerid', true);

  if ( $woobotcustomerid == '' ) {
    $ret = "<td colspan=\"2\">No customer record found in Woobot Platform</td>";
    return $ret;
  }

  /*  Callout */
  $ch = curl_init('https://api.woobot.io/v1/provision/' . $woobotcustomerid);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key: 0EoukQI39K9mJk59MSE7X9XjksnzxJhx2kzlMErg'));
  $resultString = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  $ret = "";

  if ( $http_code == 200 ) {
    $result = json_decode($resultString);

    $customer = $result->{'customer'};

    if ( $customer->{'slack'} == True ) {
	$ret .=  "<td>Complete</td>";
    }
    else {
	$ret .= "<td>Not complete. Click <a href=\"https://slack.com/oauth/authorize?scope=incoming-webhook,commands,bot&client_id=3500888778.44776561651&state=order-" . $order_id . "\">here</a> to add to slack</td>";
    }

    if ( $customer->{'woo'} == True ) {
	$ret .=  "<td>Complete</td>";
    }
    else {
	$ret .= "<td>Not complete. Click <a href=\"" . $storeurli . "#&user_id=order-" . $order_id . "\">here</a> to add to woo</td>";
    }
  }
  else {
    $ret = "<td colspan=\"2\">No customer record found in Woobot Platform</td>";
  }
  return $ret;
}
add_shortcode( 'woobot_provision_status', 'shortcode_woobot_provision_status' );
add_shortcode( 'woobot_customer_id', 'shortcode_woobot_customer_id' );
function shortcode_woobot_provision_slack_callback() {

  $connection_id = $_GET["state"];
  $url = 'https://api.woobot.io/v1/provision/slack';

  $fields = array(
    'code' => urlencode($_GET["code"]),
    'state' => urlencode($_GET["state"])
  );
  // url-ify
  $fields_string = "";
  foreach( $fields as $key=>$value) {
    $fields_string .= $key . '=' . $value . '&';
  }
  rtrim($fields_string, '&');

  /*  Callout */
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key: 0EoukQI39K9mJk59MSE7X9XjksnzxJhx2kzlMErg'));
  curl_setopt($ch, CURLOPT_POST, count($fields));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

  // excute callout pot
  $resultString = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  update_post_meta( (int) $connection_id, 'slack_connected', 'true' );
  update_post_meta( (int) $connection_id, 'slack_team', 'xxx' );

  $store_connected = get_post_meta((int) $connection_id, 'store_connected', true);
  if ( $store_connected == 'true' ) {
    update_post_meta( (int) $connection_id, 'connection_status', 'true');
  }

  return "Callback result: " . $http_code . " : " . $resultString;
}
add_shortcode( 'woobot_provision_slack_callback', 'shortcode_woobot_provision_slack_callback' );

function shortcode_woobot_provision_woo_callback() {
  $code = $_GET["user_id"];

  list($connection_id, $woo_store_url) = explode(":", $code);
  update_post_meta( (int) $connection_id, 'store_connected', 'true');
  update_post_meta( (int) $connection_id, 'store_name', $woo_store_url);

  $slack_connected = get_post_meta((int) $connection_id, 'slack_connected', true);
  if ( $slack_connected == 'true' ) {
    update_post_meta( (int) $connection_id, 'connection_status', 'true');
  }

  return "Updated connection";
}
add_shortcode( 'woobot_provision_woo_callback', 'shortcode_woobot_provision_woo_callback' );


function shortcode_woobot_provision_woostore_script() {
$returnstring =  <<<EOFRETURNSTRING
<script>
  var theUrl = '';
  function updateDraftUrl(node) {
    var urlSuffix = "/wc-auth/v1/authorize?app_name=woobot.io&scope=read_write&user_id=" +
      QueryString.connection_id + ":" + encodeURI(node.value) +
      "&return_url=https%3A%2F%2Fwoobot.io%2Fmyaccount%2Fcallbackwoo%2F&callback_url=https%3A%2F%2Fapi.woobot.io%2Fv1%2Fprovision%2Fwoostore";
    document.getElementById("draft-url").innerHTML = "https://" + node.value + urlSuffix;
    theUrl = "https://" + node.value + urlSuffix;
    // document.getElementById("authorizeButton").href = "https://" + node.value + urlSuffix;
  }
  function validateUrl(node) {
    var data = {
       'action': 'woobot_check_url',
       'url': theUrl
    };
    jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
      console.log(response);
      resp = JSON.parse(response);
      if ( resp.result == "302") {
        window.location.href = theUrl;
      }
      else {
        document.getElementById('errorMessage').style.display = 'block';
      }
      return false;
    });
  }
var QueryString = function () {
  // This function is anonymous, is executed immediately and
  // the return value is assigned to QueryString!
  var query_string = {};
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
        // If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = decodeURIComponent(pair[1]);
        // If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
      query_string[pair[0]] = arr;
        // If third or later entry with this name
    } else {
      query_string[pair[0]].push(decodeURIComponent(pair[1]));
    }
  }
  return query_string;
}();

</script>
EOFRETURNSTRING;
return $returnstring;
}
add_shortcode( 'woobot_provision_woostore_script', 'shortcode_woobot_provision_woostore_script' );


add_action('wp_ajax_woobot_check_url', 'woobot_check_url');
function woobot_check_url() {
  global $wpdb;
  $url = $_POST['url'];

  error_log($url);
  /*  Callout */
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPGET, 1);
  $result = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  /*  Ajax, echo the result */
  print('{"result" : "' . $http_code . '"}');

  wp_die();
}

add_action('wp_ajax_woobot_ajax_action', 'woobot_action_callback');
function woobot_action_callback() {
  global $wpdb;
  $woobotcustomerid = $_POST['woobotcustomerid'];


  /*  Callout */
  $ch = curl_init('https://api.woobot.io/v1/provision/' . $woobotcustomerid);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);

  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  curl_close($ch);

  /*  end callout */

  /*  Ajax, echo the result */
  print($http_code . ' ' . $result);

  wp_die();
}

function get_connections_array($subscription_id) {
  global $post;

  $connections = array();

  $query = new WP_Query(array(
      'post_type' => 'connection',
      'meta_key' => 'subscription_id',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      'meta_query' => array(
          'key' => 'subscription_id',
          'value' => $subscription_id,
          'compare' => '='
      )
  ));
  while ($query->have_posts() ) {
    $query->the_post();
    array_push($connections, $post);
  }
  wp_reset_postdata();
  return $connections;
}
/* Draw MY CONNECTIONS area */
/* should replace everything in the tbody */

function get_my_connections_html() {

    $html = '';
    $subscriptions = wcs_get_users_subscriptions();
    foreach ( $subscriptions as $subscription_id => $subscription ) :
        $html .= '<!-- subscription status ' . $subscription->status . '  -->';

        if ( strcmp($subscription->status, 'cancelled') == 0 ) {
            continue;
        }

        if ( strcmp($subscription->status, 'on hold') == 0 ) {
        }
        /*  Grab the Connections for this subscription */
        $connections = get_connections_array($subscription_id);
        $total_connections = count($connections);

        foreach ( $connections as  $connection ) {
            $connection_id = $connection->ID;
            $store_name = get_post_meta($connection_id, 'store_name', true);
            $slack_team = get_post_meta($connection_id, 'slack_team', true);
            $store_connected = get_post_meta($connection_id, 'store_connected', true) == 'true' ? true : false;
            $slack_connected =  get_post_meta($connection_id, 'slack_connected', true) == 'true' ? true : false;
            $connection_status = get_post_meta($connection_id, 'connection_status', true) == 'true' ? true : false;
            $slack_authorize_url = "https://slack.com/oauth/authorize?scope=incoming-webhook,commands,bot&client_id=3500888778.44776561651&state=" . $connection_id ;
            $store_authorize_url = "/myaccount/ask-store-url/?connection_id=" . $connection_id;

            $html .= '<tr>';
            if ( $store_connected ) {
                $html .= '<td data-title="Store Name">' . $store_name . '</td>';
            } else {
                $html .= '<td data-title="Store Name">';
                    $html .= ('<a target="wooPopup" class="btn" ');
                    $html .= ('href="javascript:window.open(\'' . $store_authorize_url . '\', \'Woo Authorize\', \'scrollbars=1,resizable=0,width=640,height=800\')" ');
                    $html .= ('onclick="this.innerHTML=\'Authorizing...\'; return true"');
                    $html .= ('>Authorize</a></td>');
            }
            if ( $connection_status  )  {
                $html .= ('<td data-title="Connection Status">CONNECTED</td>');
                $html .= ('<td data-title="Status"><i class="fa fa-fw connected-on">&#xf205;</i></td>');
            } else {
                $html .= ('<td data-title="Connection">NOT CONNECTED</td>');
                $html .= ('<td data-title="Status"><i class="fa fa-fw connected-off">&#xf204;</i></td>');
            }

            if ( $slack_connected )  {
                $html .= ('<td data-title="Slack Team">' . $slack_team . '</td>');
                $html .= ('<td data-title="Channel" class="woobotjqueryui">');
                $html .= channel_selector($connection_id);
                $html .= '</td>';
            } else {
                $html .= ('<td data-title="Slack Team"><a target="slackPopup" class="btn"');
                $html .= ('href="javascript:window.open(\'' . $slack_authorize_url . '\', \'Slack Authorize\', \'scrollbars=1,resizable=0,width=640,height=800\')"');
                $html .= ('onclick="this.innerHTML=\'Authorizing...\'; return true"');
                $html .= ('>Authorize</a></td>');
                $html .= ('<td data-title="Channel" class="woobotjqueryui">');
                $html .= channel_selector(false);
                $html .= '</td>';
            }


            $html .= ('<td class="order-actions" data-title="&nbsp;"><a href="#myconnections" onclick="deleteConnection(' . $connection_id . ')"><i class="fa fa-fw">&#xf05c;</i></a></td>');
            $html .= ('</tr>');
    } //endwhile
  $subscription_type = get_subscription_type($subscription_id);
  $possible_connections = 1;
  if ( $subscription_type == 'WOOBOT-annual' ) {
    $possible_connections = 5;
  }
  if ( $total_connections < $possible_connections ) {
    $html .= ('<tr> <td colspan="5">&nbsp;</td>');
    $html .= ('<td><a href="#myconnections" onclick="addConnection(' . $subscription_id . ')"><i class="fa fa-fw">&#xf055;</i></a></td>');
    $html .= ('</tr>');
  }
endforeach;

  return $html;
}

function channel_selector($connection_id) {
  if ( $connection_id == false ) {

    return <<<ENDOFCHANNELSELECTORFALSE
    <select disabled="true" name="channel" id="channel" class="channel_selector">
      <option disabled selected>Channel</option>
    </select>
ENDOFCHANNELSELECTORFALSE;
  }
  else {
  $html = <<<ENDOFCHANNELSELECTOR
  <select name="channel" id="channel" class="channel_selector">
    <option disabled selected>Channel</option>
    <option>general</option>
    <option>random</option>
    <option>slackers</option>
    <option>admins</option>
    <option>backoffice</option>
  </select>
ENDOFCHANNELSELECTOR;
  return $html;
  }
}



add_action('wp_ajax_woobot_myconnections_action', 'woobot_action_myconnections_callback');
function woobot_action_myconnections_callback() {
    global $wpdb;


    $action = $_POST['dothis'];
    $subscription_id = null;
    $connection_id = null;

    if ( isset($_POST['subscription_id'])) {
      $subscription_id = intval($_POST['subscription_id']);
    }
    if ( isset($_POST['connection_id'])) {
      $connection_id = intval($_POST['connection_id']);
    }

    if ( $action == 'new' && isset($_POST['subscription_id']) ) {
        $current_connections = get_connections_array($subscription_id);
        error_log(json_encode($current_connections));
        error_log("Count");
        error_log(count($current_connections));
        if ( count($current_connections) < 5 ) {
          create_connection($subscription_id);
        }
    }
    if ( $action == 'delete' ) {
        wp_delete_post( $connection_id );
    }
    $html =  get_my_connections_html();
    echo $html;

    wp_die();
}

function get_subscription_type($subscription_id) {
  $subscription_type = get_post_meta((int) $subscription_id, '_sku', true);
  return $subscription_type;
}
function get_connection($connection_id) {
  return get_post($connection_id);
}
function create_connection($subscription_id) {
  $post_id = wp_insert_post( array(
      'post_type' => 'connection',
      'post_status' => 'publish'
  ));
  add_post_meta($post_id, 'subscription_id', $subscription_id);
  add_post_meta($post_id, 'store_name', '');
  add_post_meta($post_id, 'slack_team', '');
  add_post_meta($post_id, 'store_connected', 'false');
  add_post_meta($post_id, 'slack_connected', 'false');
  add_post_meta($post_id, 'connection_status', 'false');
  return $post_id;
}

function isloading_enqueue_script() {
    wp_enqueue_script( 'isloading-js', get_template_directory_uri() . '/js/jquery.isloading.js', false);
    wp_enqueue_style('jqueryui-structure', get_template_directory_uri() . '/assets/jquery-ui.structure.min.css', false);
    wp_enqueue_style('jqueryui-theme', get_template_directory_uri() . '/assets/jquery-ui.theme.min.css', false);
    wp_enqueue_script( 'jqueryui', get_template_directory_uri() . '/js/jquery-ui.min.js', false);
}
add_action('wp_enqueue_scripts', 'isloading_enqueue_script');



/*  Subscription Hooks */
add_action('woocommerce_subscription_status_active', 'woobot_sub_active_action');
function woobot_sub_active_action($subscription) {
  /*  Determine how many connections exist */
  $subscription_id = $subscription->id;
  error_log(json_encode($subscription));
  error_log('woo_sub_active_action on ' . $subscription_id);

  $connections_to_create = 1;

  if ( get_subscription_type($subscription_id) == 'WOOBOT-annual') {
    $connections_to_create = 5;
  }

  $existing_connections = get_connections_array($subscription_id);
  if ( count($existing_connections) < $connections_to_create ) {
    /*  Make up to 5 connections */
    $connections_to_create = $connections_to_create - count($existing_connections);
    while ( $connections_to_create > 0 ) {
      create_connection($subscription_id);
      $connections_to_create = $connections_to_create - 1;
    }
  }

}
?>
