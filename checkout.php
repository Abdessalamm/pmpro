<?php
/**
 * Template: Checkout
 *
 * See documentation for how to override the PMPro templates.
 * @link https://www.paidmembershipspro.com/documentation/templates/
 *
 * @version 2.0
 *
 * @author Paid Memberships Pro and Abdessalamm
 
<
 */


global $gateway, $pmpro_review, $skip_account_fields, $pmpro_paypal_token, $wpdb, $current_user, $pmpro_msg, $pmpro_msgt, $pmpro_requirebilling, $pmpro_level, $pmpro_levels, $tospage, $pmpro_show_discount_code, $pmpro_error_fields;
global $discount_code, $username, $password, $password2, $bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $bconfirmemail, $CardType, $AccountNumber, $ExpirationMonth, $ExpirationYear;

/**
 * Filter to set if PMPro uses email or text as the type for email field inputs.
 *
 * @since 1.8.4.5
 *
 * @param bool $use_email_type, true to use email type, false to use text type
 */


$pmpro_email_field_type = apply_filters( 'pmpro_email_field_type', true );

// Set the wrapping class for the checkout div based on the default gateway;
$default_gateway = pmpro_getOption( 'gateway' );
if ( empty( $default_gateway ) ) {
  $pmpro_checkout_gateway_class = 'pmpro_checkout_gateway-none';
} else {
  $pmpro_checkout_gateway_class = 'pmpro_checkout_gateway-' . $default_gateway;
}
?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap"><link rel="stylesheet" href="/assets/pages/bootstrap.min.css"><link rel="stylesheet" href="/assets/pages/compiled-addons-4.20.0.min.css"><link rel="stylesheet" href="/assets/pages/mdb.min.css">
	
	
<style>@-webkit-keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s}span.pmpro_asterisk{display:none}</style>
<?php do_action('pmpro_checkout_before_form'); ?>
<!--Main layout-->

<main class="mt-5 pt-4">
  <div class="container wow fadeIn"> 
    
    <!-- Heading --> 
    
    <!--Grid row-->
    <div class="row"> 
      
      <!--Grid column-->
      <div class="col-md-8 mb-4"> 
        <!--Card-->
        
        <div id="pmpro_level-<?php echo $pmpro_level->id; ?>" class="<?php echo pmpro_get_element_class( $pmpro_checkout_gateway_class, 'pmpro_level-' . $pmpro_level->id ); ?>">
          <form id="pmpro_form" class="<?php echo pmpro_get_element_class( 'pmpro_form' ); ?>" action="<?php if(!empty($_REQUEST['review'])) echo pmpro_url("checkout", "?level=" . $pmpro_level->id); ?>" method="post">
            <input type="hidden" id="level" name="level" value="<?php echo esc_attr($pmpro_level->id) ?>" />
            <input type="hidden" id="checkjavascript" name="checkjavascript" value="1" />
            <?php if ($discount_code && $pmpro_review) { ?>
            <input class="form-control pmpro_required" id="discount_code" name="discount_code" type="hidden" size="20" value="<?php echo esc_attr($discount_code) ?>" />
            <?php } ?>
            <?php if($pmpro_msg) { ?>
            <div id="pmpro_message" class="<?php echo pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ); ?>"> <?php echo apply_filters( 'pmpro_checkout_message', $pmpro_msg, $pmpro_msgt ) ?> </div>
            <?php } else { ?>
            <div id="pmpro_message" class="<?php echo pmpro_get_element_class( 'pmpro_message' ); ?>" style="display: none;"></div>
            <?php } ?>
            <?php if($pmpro_review) { ?>
            <p>
              <?php _e('Almost done. Review the membership information and pricing below then <strong>click the "Complete Payment" button</strong> to finish your order.', 'paid-memberships-pro' );?>
            </p>
            <?php } ?>
            <?php
            $include_pricing_fields = apply_filters( 'pmpro_include_pricing_fields', true );
            if ( $include_pricing_fields ) {
              ?>
            <div id="pmpro_pricing_fields" class="<?php echo pmpro_get_element_class( 'pmpro_checkout', 'pmpro_pricing_fields' ); ?>" style="display: none;">
              <h3> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-name' ); ?>">
                <?php _e('Membership Level', 'paid-memberships-pro' );?>
                </span>
                <?php if(count($pmpro_levels) > 1) { ?>
                <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-msg' ); ?>"><a href="<?php echo pmpro_url("levels"); ?>">
                <?php _e('change', 'paid-memberships-pro' );?>
                </a></span>
                <?php } ?>
              </h3>
              <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields' ); ?>">
                <p> <?php printf(__('You have selected the <strong>%s</strong> membership level.', 'paid-memberships-pro' ), $pmpro_level->name);?> </p>
                <?php
                /**
                 * All devs to filter the level description at checkout.
                 * We also have a function in includes/filters.php that applies the the_content filters to this description.
                 * @param string $description The level description.
                 * @param object $pmpro_level The PMPro Level object.
                 */
                $level_description = apply_filters( 'pmpro_level_description', $pmpro_level->description, $pmpro_level );
                if ( !empty( $level_description ) )
                  echo $level_description;
                ?>
                <div id="pmpro_level_cost">
                  <?php if($discount_code && pmpro_checkDiscountCode($discount_code)) { ?>
                  <?php printf(__('<p class="' . pmpro_get_element_class( 'pmpro_level_discount_applied' ) . '">The <strong>%s</strong> code has been applied to your order.</p>', 'paid-memberships-pro' ), $discount_code);?>
                  <?php } ?>
                  <?php echo wpautop(pmpro_getLevelCost($pmpro_level)); ?> <?php echo wpautop(pmpro_getLevelExpiration($pmpro_level)); ?> </div>
                <?php do_action("pmpro_checkout_after_level_cost"); ?>
                <?php if($pmpro_show_discount_code) { ?>
                <?php if($discount_code && !$pmpro_review) { ?>
                <p id="other_discount_code_p" class="<?php echo pmpro_get_element_class( 'pmpro_small', 'other_discount_code_p' ); ?>"><a id="other_discount_code_a" href="#discount_code">
                  <?php _e('Click here to change your discount code.', 'paid-memberships-pro' );?>
                  </a></p>
                <?php } elseif(!$pmpro_review) { ?>
                <p id="other_discount_code_p" class="<?php echo pmpro_get_element_class( 'pmpro_small', 'other_discount_code_p' ); ?>">
                  <?php _e('Do you have a discount code?', 'paid-memberships-pro' );?>
                  <a id="other_discount_code_a" href="#discount_code">
                  <?php _e('Click here to enter your discount code', 'paid-memberships-pro' );?>
                  </a>.</p>
                <?php } elseif($pmpro_review && $discount_code) { ?>
                <p><strong>
                  <?php _e('Discount Code', 'paid-memberships-pro' );?>
                  :</strong> <?php echo $discount_code?></p>
                <?php } ?>
                <?php } ?>
                <?php if($pmpro_show_discount_code) { ?>
                <div id="other_discount_code_tr" class="md-form mb-5" style="display: none;">
                  <label for="other_discount_code">
                    <?php _e('Discount Code', 'paid-memberships-pro' );?>
                  </label>
                  <input id="other_discount_code" name="other_discount_code" type="text" class="form-control pmpro_required" size="20" value="<?php echo esc_attr($discount_code); ?>" />
                  <input type="button" name="other_discount_code_button" class="btn btn-primary btn-lg btn-block waves-effect waves-light" id="other_discount_code_button" value="<?php _e('Apply', 'paid-memberships-pro' );?>" />
                </div>
                <?php } ?>
              </div>
              <!-- end pmpro_checkout-fields --> 
            </div>
            <!-- end pmpro_pricing_fields -->
            <?php
            } // if ( $include_pricing_fields )
            ?>
            <?php
            do_action( 'pmpro_checkout_after_pricing_fields' );
            ?>
            <?php if(!$skip_account_fields && !$pmpro_review) { ?>
            <?php
            // Get discount code from URL parameter, so if the user logs in it will keep it applied.
            $discount_code_link = !empty( $discount_code ) ? '&discount_code=' . $discount_code : '';
            ?>
            <div id="pmpro_user_fields" class="<?php echo pmpro_get_element_class( 'pmpro_checkout', 'pmpro_user_fields' ); ?>">
              <hr />
              <h3> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-name' ); ?>" style="display: none;">
                <?php _e('Account Information', 'paid-memberships-pro' );?>
                </span> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-msg' ); ?>">
                <?php _e('Already have an account?', 'paid-memberships-pro' );?>
                <a href="<?php echo wp_login_url( apply_filters( 'pmpro_checkout_login_redirect', pmpro_url("checkout", "?level=" . $pmpro_level->id . $discount_code_link) ) ); ?>">
                <?php _e('Log in here', 'paid-memberships-pro' );?>
                </a></span> </h3>
              <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields' ); ?>">
                <div class="md-form mb-5 <?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-username', 'pmpro_checkout-field-username' ); ?>">
                  <label class="active" for="username">
                    <?php _e('Username', 'paid-memberships-pro' );?>
                  </label>
                  <input id="username" name="username" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($username); ?>" />
                </div>
                <!-- end pmpro_checkout-field-username -->
                
                <?php
                do_action( 'pmpro_checkout_after_username' );
                ?>
                <div class="row">
                  <div class="col-md-6 mb-2">
                    <div class="md-form <?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-password', 'pmpro_checkout-field-password' ); ?>">
                      <label for="password">
                        <?php _e('Password', 'paid-memberships-pro' );?>
                      </label>
                      <input id="password" name="password" type="password" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($password); ?>" />
                    </div>
                  </div>
                  <!-- end pmpro_checkout-field-password -->
                  
                  <?php
                  $pmpro_checkout_confirm_password = apply_filters( "pmpro_checkout_confirm_password", true );
                  if ( $pmpro_checkout_confirm_password ) {
                    ?>
                  <div class="col-md-6 mb-2">
                    <div class="md-form <?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-password2', 'pmpro_checkout-field-password2' ); ?>">
                      <label class="active" for="password2">
                        <?php _e('Confirm Password', 'paid-memberships-pro' );?>
                      </label>
                      <input id="password2" name="password2" type="password" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($password2); ?>" />
                    </div>
                    <!-- end pmpro_checkout-field-password2 -->
                    <?php } else { ?>
                    <input type="hidden" name="password2_copy" value="1" />
                    <?php
                    }
                    ?>
                  </div>
                </div>
                <?php
                do_action( 'pmpro_checkout_after_password' );
                ?>
                <div class="row">
                  <div class="col-md-6 mb-2">
                    <div class="md-form <?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-bemail', 'pmpro_checkout-field-bemail' ); ?>">
                      <label for="bemail">
                        <?php _e('Email Address', 'paid-memberships-pro' );?>
                      </label>
                      <input id="bemail" name="bemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bemail); ?>" />
                    </div>
                  </div>
                  <!-- end pmpro_checkout-field-bemail -->
                  
                  <?php
                  $pmpro_checkout_confirm_email = apply_filters( "pmpro_checkout_confirm_email", true );
                  if ( $pmpro_checkout_confirm_email ) {
                    ?>
                  <div class="col-md-6 mb-2">
                    <div class="md-form <?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-bconfirmemail', 'pmpro_checkout-field-bconfirmemail' ); ?>">
                      <label for="bconfirmemail">
                        <?php _e('Confirm Email Address', 'paid-memberships-pro' );?>
                      </label>
                      <input id="bconfirmemail" name="bconfirmemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bconfirmemail); ?>" />
                    </div>
                  </div>
                </div>
                <!-- end pmpro_checkout-field-bconfirmemail -->
                <?php } else { ?>
                <input type="hidden" name="bconfirmemail_copy" value="1" />
                <?php
                }
                ?>
                <?php
                do_action( 'pmpro_checkout_after_email' );
                ?>
                <div class="md-form mb-5 <?php echo pmpro_get_element_class( 'pmpro_hidden' ); ?>">
                  <label for="fullname">
                    <?php _e('Full Name', 'paid-memberships-pro' );?>
                  </label>
                  <input id="fullname" name="fullname" type="text" class="form-control pmpro_required" size="30" value="" autocomplete="off"/>
                  <strong>
                  <?php _e('LEAVE THIS BLANK', 'paid-memberships-pro' );?>
                  </strong> </div>
                <!-- end pmpro_hidden --> 
                
              </div>
              <!-- end pmpro_checkout-fields --> 
            </div>
            <!-- end pmpro_user_fields -->
            <?php } elseif($current_user->ID && !$pmpro_review) { ?>
            <div id="pmpro_account_loggedin" style="display: none;" class="<?php echo pmpro_get_element_class( 'pmpro_message pmpro_alert', 'pmpro_account_loggedin' ); ?>"> <?php printf(__('You are logged in as <strong>%s</strong>. If you would like to use a different account for this membership, <a href="%s">log out now</a>.', 'paid-memberships-pro' ), $current_user->user_login, wp_logout_url($_SERVER['REQUEST_URI'])); ?> </div>
            <!-- end pmpro_account_loggedin -->
            <?php } ?>
            <?php
            do_action( 'pmpro_checkout_after_user_fields' );
            ?>
            <?php
            do_action( 'pmpro_checkout_boxes' );
            ?>
            <?php if(pmpro_getGateway() == "paypal" && empty($pmpro_review) && true == apply_filters('pmpro_include_payment_option_for_paypal', true ) ) { ?>
            <div id="pmpro_payment_method" class="<?php echo pmpro_get_element_class( 'pmpro_checkout', 'pmpro_payment_method' ); ?>" <?php if(!$pmpro_requirebilling) { ?>style="display: none;"<?php } ?>>
              <hr />
              <h3> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-name' ); ?>">
                <?php _e('Choose your Payment Method', 'paid-memberships-pro' ); ?>
                </span> </h3>
              <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields' ); ?>"> <span class="<?php echo pmpro_get_element_class( 'gateway_paypal' ); ?>">
                <input type="radio" name="gateway" value="paypal" <?php if(!$gateway || $gateway == "paypal") { ?>checked="checked"<?php } ?> />
                <a href="javascript:void(0);" class="<?php echo pmpro_get_element_class( 'pmpro_radio' ); ?>">
                <?php _e('Check Out with a Credit Card Here', 'paid-memberships-pro' );?>
                </a> </span> <span class="<?php echo pmpro_get_element_class( 'gateway_paypalexpress' ); ?>">
                <input type="radio" name="gateway" value="paypalexpress" <?php if($gateway == "paypalexpress") { ?>checked="checked"<?php } ?> />
                <a href="javascript:void(0);" class="<?php echo pmpro_get_element_class( 'pmpro_radio' ); ?>">
                <?php _e('Check Out with PayPal', 'paid-memberships-pro' );?>
                </a> </span> </div>
              <!-- end pmpro_checkout-fields --> 
            </div>
            <!-- end pmpro_payment_method -->
            <?php } ?>
            <?php
            $pmpro_include_billing_address_fields = apply_filters( 'pmpro_include_billing_address_fields', true );
            if ( $pmpro_include_billing_address_fields ) {
              ?>
            <div id="pmpro_billing_address_fields" class="<?php echo pmpro_get_element_class( 'pmpro_checkout', 'pmpro_billing_address_fields' ); ?>" <?php if(!$pmpro_requirebilling || apply_filters("pmpro_hide_billing_address_fields", false) ){ ?>style="display: none;"<?php } ?>>
              <hr />
              <h3> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-name' ); ?>">
                <?php _e('Billing Address', 'paid-memberships-pro' );?>
                </span> </h3>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label class="active" for="bfirstname">
                      <?php _e('First Name', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bfirstname" name="bfirstname" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bfirstname); ?>" />
                  </div>
                </div>
                <!-- end pmpro_checkout-field-bfirstname -->
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label class="active" for="blastname">
                      <?php _e('Last Name', 'paid-memberships-pro' );?>
                    </label>
                    <input id="blastname" name="blastname" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($blastname); ?>" />
                  </div>
                </div>
              </div>
              <!-- end pmpro_checkout-field-blastname -->
              <div class="md-form mb-5">
                <label class="active" for="baddress1">
                  <?php _e('Address', 'paid-memberships-pro' );?>
                </label>
                <input id="baddress1" name="baddress1" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($baddress1); ?>" />
              </div>
              <!-- end pmpro_checkout-field-baddress1 -->
              <div class="md-form mb-5">
                <label  class="active" for="baddress2">
                  <?php _e('Address 2 (Optional)', 'paid-memberships-pro' );?>
                </label>
                <input id="baddress2" name="baddress2" type="text" class="form-control" size="30" value="<?php echo esc_attr($baddress2); ?>" />
              </div>
              <!-- end pmpro_checkout-field-baddress2 -->
              <?php
              $longform_address = apply_filters( "pmpro_longform_address", true );
              if ( $longform_address ) {
                ?>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label for="bcity">
                      <?php _e('City', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bcity" name="bcity" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bcity); ?>" />
                  </div>
                </div>
                <!-- end pmpro_checkout-field-bcity -->
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label for="bstate">
                      <?php _e('State', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bstate" name="bstate" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bstate); ?>" />
                  </div>
                </div>
              </div>
              <!-- end pmpro_checkout-field-bstate -->
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label for="bzipcode">
                      <?php _e('Postal Code', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bzipcode" name="bzipcode" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bzipcode); ?>" />
                  </div>
                </div>
                <!-- end pmpro_checkout-field-bzipcode -->
                <?php } else { ?>
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label for="bcity_state_zip' ); ?>">
                      <?php _e('City, State Zip', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bcity" name="bcity" type="text" class="form-control pmpro_required" size="14" value="<?php echo esc_attr($bcity); ?>" />
                  </div>
                </div>
                ,
                <?php
                $state_dropdowns = apply_filters( "pmpro_state_dropdowns", false );
                if ( $state_dropdowns === true || $state_dropdowns == "names" ) {
                  global $pmpro_states;
                  ?>
                <div class="col-lg-4 col-md-12 mb-4" style="color:gray; min-width: 50%;">
                <select name="bstate" class="ustom-select d-block w-100">
                  <option value="">--</option>
                  <?php
                  foreach ( $pmpro_states as $ab => $st ) {
                    ?>
                  <option value="<?php echo esc_attr($ab);?>" <?php if($ab == $bstate) { ?>selected="selected"<?php } ?>><?php echo $st;?></option>
                  <?php } ?>
                </select>
                <?php
                } elseif ( $state_dropdowns == "abbreviations" ) {
                    global $pmpro_states_abbreviations;
                    ?>
                <select name="bstate" class="ustom-select d-block w-100">
                  <option value="">--</option>
                  </div>
                  <?php
                  foreach ( $pmpro_states_abbreviations as $ab ) {
                    ?>
                  <option value="<?php echo esc_attr($ab);?>" <?php if($ab == $bstate) { ?>selected="selected"<?php } ?>><?php echo $ab;?></option>
                  <?php } ?>
                </select>
                <?php } else { ?>
                <input id="bstate" name="bstate" type="text" class="col-md-6 mb-2 md-form" size="2" value="<?php echo esc_attr($bstate); ?>" />
                <?php } ?>
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <input id="bzipcode" name="bzipcode" type="text" class="form-control pmpro_required" size="5" value="<?php echo esc_attr($bzipcode); ?>" />
                    <!-- end pmpro_checkout-field-bcity_state_zip --></div>
                </div>
                <?php } ?>
                <?php
                $show_country = apply_filters( "pmpro_international_addresses", true );
                if ( $show_country ) {
                  ?>
                <div class="col-lg-4 col-md-12 mb-4" style="color:gray; min-width: 50%;">
                  <label for="bcountry" >
                    <?php _e('Country', 'paid-memberships-pro' );?>
                  </label>
                  <select name="bcountry" id="bcountry" class="custom-select d-block w-100">
                    <?php
                    global $pmpro_countries, $pmpro_default_country;
                    if ( !$bcountry ) {
                      $bcountry = $pmpro_default_country;
                    }
                    foreach ( $pmpro_countries as $abbr => $country ) {
                      ?>
                    <option value="<?php echo $abbr?>" <?php if($abbr == $bcountry) { ?>selected="selected"<?php } ?>><?php echo $country?></option>
                    <?php } ?>
                  </select>
                </div>
                <!-- end pmpro_checkout-field-bcountry -->
                <?php } else { ?>
                <input type="hidden" name="bcountry" value="US" />
                <?php } ?>
              </div>
              <div class="md-form mb-5">
                <label class="active" for="bphone">
                  <?php _e('Phone', 'paid-memberships-pro' );?>
                </label>
                <input id="bphone" name="bphone" type="text" class="form-control pmpro_required" size="30" value="<?php echo esc_attr(formatPhone($bphone)); ?>" />
              </div>
              <!-- end pmpro_checkout-field-bphone -->
              <?php if($skip_account_fields) { ?>
              <?php
              if ( $current_user->ID ) {
                if ( !$bemail && $current_user->user_email ) {
                  $bemail = $current_user->user_email;
                }
                if ( !$bconfirmemail && $current_user->user_email ) {
                  $bconfirmemail = $current_user->user_email;
                }
              }
              ?>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label for="bemail">
                      <?php _e('Email Address', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bemail" name="bemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="form-control pmpro_required" />
                  </div>
                </div>
                <!-- end pmpro_checkout-field-bemail -->
                <?php
                $pmpro_checkout_confirm_email = apply_filters( "pmpro_checkout_confirm_email", true );
                if ( $pmpro_checkout_confirm_email ) {
                  ?>
                <div class="col-md-6 mb-2">
                  <div class="md-form">
                    <label for="bconfirmemail">
                      <?php _e('Confirm Email', 'paid-memberships-pro' );?>
                    </label>
                    <input id="bconfirmemail" name="bconfirmemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="form-control pmpro_required" size="30" value="<?php echo esc_attr($bconfirmemail); ?>" />
                  </div>
                </div>
              </div>
              <!-- end pmpro_checkout-field-bconfirmemail -->
              <?php } else { ?>
              <input type="hidden" name="bconfirmemail_copy" value="1" />
              <?php } ?>
              <?php } ?>
            </div>
            <!-- end pmpro_checkout-fields --> 
            
            <!--end pmpro_billing_address_fields -->
            <?php } ?>
            <?php do_action("pmpro_checkout_after_billing_fields"); ?>
            <?php
            $pmpro_accepted_credit_cards = pmpro_getOption( "accepted_credit_cards" );
            $pmpro_accepted_credit_cards = explode( ",", $pmpro_accepted_credit_cards );
            $pmpro_accepted_credit_cards_string = pmpro_implodeToEnglish( $pmpro_accepted_credit_cards );
            ?>
            <?php
            $pmpro_include_payment_information_fields = apply_filters( "pmpro_include_payment_information_fields", true );
            if ( $pmpro_include_payment_information_fields ) {
              ?>
            <div id="pmpro_payment_information_fields" class="<?php echo pmpro_get_element_class( 'pmpro_checkout', 'pmpro_payment_information_fields' ); ?>" <?php if(!$pmpro_requirebilling || apply_filters("pmpro_hide_payment_information_fields", false) ) { ?>style="display: none;"<?php } ?>>
              <hr />
              <h3> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-name' ); ?>">
                <?php _e('Payment Information', 'paid-memberships-pro' );?>
                </span> <span class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-msg' ); ?>"><?php printf(__('We Accept %s', 'paid-memberships-pro' ), $pmpro_accepted_credit_cards_string);?></span> </h3>
              <?php $sslseal = pmpro_getOption("sslseal"); ?>
              <?php if(!empty($sslseal)) { ?>
              <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields-display-seal' ); ?>">
                <?php } ?>
                <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields' ); ?>">
                  <?php
                  $pmpro_include_cardtype_field = apply_filters( 'pmpro_include_cardtype_field', false );
                  if ( $pmpro_include_cardtype_field ) {
                    ?>
                  <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_payment-card-type', 'pmpro_payment-card-type' ); ?>">
                    <label for="CardType">
                      <?php _e('Card Type', 'paid-memberships-pro' );?>
                    </label>
                    <select id="CardType" name="CardType" class="<?php echo pmpro_get_element_class( '', 'CardType' ); ?>">
                      <?php foreach($pmpro_accepted_credit_cards as $cc) { ?>
                      <option value="<?php echo $cc; ?>" <?php if($CardType == $cc) { ?>selected="selected"<?php } ?>><?php echo $cc; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php } else { ?>
                  <input type="hidden" id="CardType" name="CardType" value="<?php echo esc_attr($CardType);?>" />
                  <?php } ?>
                  <div class="row">
                    <div class="col-md-6 mb-2" >
                      <div class="md-form">
                        <input id="AccountNumber" placeholder="<?php _e('Card Number', 'paid-memberships-pro' );?>" name="AccountNumber" class="form-control pmpro_required" type="text" size="30" value="<?php echo esc_attr($AccountNumber); ?>" data-encrypted-name="number" autocomplete="off" />
                      </div>
                    </div>
                    <div class="col-md-6 mb-2">
                      <div class="md-form">
                        <?php
                        $pmpro_show_cvv = apply_filters( "pmpro_show_cvv", true );
                        if ( $pmpro_show_cvv ) {
                          ?>
                        <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_payment-cvv', 'pmpro_payment-cvv' ); ?>">
                          <input id="CVV" name="CVV" placeholder="<?php _e('Security Code (CVC)', 'paid-memberships-pro' );?>" type="text" size="4" value="<?php if(!empty($_REQUEST['CVV'])) { echo esc_attr($_REQUEST['CVV']); }?>" class="form-control pmpro_required" />
                          <small>(<a href="javascript:void(0);" onclick="javascript:window.open('<?php echo pmpro_https_filter(PMPRO_URL); ?>/pages/popup-cvv.html','cvv','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=600, height=475');">
                          <?php _e("what's this?", 'paid-memberships-pro' );?>
                          </a>)</small> </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-4 col-md-12 mb-4" style="min-width: 50%;">
                      <label for="ExpirationMonth">
                        <?php _e('Expiration Date: Month', 'paid-memberships-pro' );?>
                      </label>
                      <select id="ExpirationMonth" name="ExpirationMonth" class="custom-select d-block w-100">
                        <option value="01" <?php if($ExpirationMonth == "01") { ?>selected="selected"<?php } ?>>01</option>
                        <option value="02" <?php if($ExpirationMonth == "02") { ?>selected="selected"<?php } ?>>02</option>
                        <option value="03" <?php if($ExpirationMonth == "03") { ?>selected="selected"<?php } ?>>03</option>
                        <option value="04" <?php if($ExpirationMonth == "04") { ?>selected="selected"<?php } ?>>04</option>
                        <option value="05" <?php if($ExpirationMonth == "05") { ?>selected="selected"<?php } ?>>05</option>
                        <option value="06" <?php if($ExpirationMonth == "06") { ?>selected="selected"<?php } ?>>06</option>
                        <option value="07" <?php if($ExpirationMonth == "07") { ?>selected="selected"<?php } ?>>07</option>
                        <option value="08" <?php if($ExpirationMonth == "08") { ?>selected="selected"<?php } ?>>08</option>
                        <option value="09" <?php if($ExpirationMonth == "09") { ?>selected="selected"<?php } ?>>09</option>
                        <option value="10" <?php if($ExpirationMonth == "10") { ?>selected="selected"<?php } ?>>10</option>
                        <option value="11" <?php if($ExpirationMonth == "11") { ?>selected="selected"<?php } ?>>11</option>
                        <option value="12" <?php if($ExpirationMonth == "12") { ?>selected="selected"<?php } ?>>12</option>
                      </select>
                    </div>
                    <div class="col-lg-4 col-md-12 mb-4" style="min-width: 50%;">
                      <label for="ExpirationMonth">
                        <?php _e('Year', 'paid-memberships-pro' );?>
                      </label>
                      <select id="ExpirationYear" name="ExpirationYear" class="custom-select d-block w-100">
                        <?php
                        $num_years = apply_filters( 'pmpro_num_expiration_years', 10 );

                        for ( $i = date_i18n( "Y" ); $i < intval( date_i18n( "Y" ) ) + intval( $num_years ); $i++ ) {
                          ?>
                        <option value="<?php echo $i?>" <?php if($ExpirationYear == $i) { ?>selected="selected"<?php } ?>><?php echo $i?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <?php if($pmpro_show_discount_code) { ?>
                  <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_payment-discount-code', 'pmpro_payment-discount-code' ); ?>">
                    <label for="discount_code">
                      <?php _e('Discount Code', 'paid-memberships-pro' );?>
                    </label>
                    <input class="<?php echo pmpro_get_element_class( 'input pmpro_alter_price', 'discount_code' ); ?>" id="discount_code" name="discount_code" type="text" size="10" value="<?php echo esc_attr($discount_code); ?>" />
                    <input type="button" id="discount_code_button" name="discount_code_button" value="<?php _e('Apply', 'paid-memberships-pro' );?>" />
                    <p id="discount_code_message" class="<?php echo pmpro_get_element_class( 'pmpro_message', 'discount_code_message' ); ?>" style="display: none;"></p>
                  </div>
                  <?php } ?>
                </div>
                <!-- end pmpro_checkout-fields -->
                <?php if(!empty($sslseal)) { ?>
                <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields-rightcol pmpro_sslseal', 'pmpro_sslseal' ); ?>"><?php echo stripslashes($sslseal); ?></div>
              </div>
              <!-- end pmpro_checkout-fields-display-seal -->
              <?php } ?>
            </div>
            <!-- end pmpro_payment_information_fields -->
            <?php } ?>
            <?php do_action('pmpro_checkout_after_payment_information_fields'); ?>
            <?php if($tospage && !$pmpro_review) { ?>
            <div id="pmpro_tos_fields" class="<?php echo pmpro_get_element_class( 'pmpro_checkout', 'pmpro_tos_fields' ); ?>">
              <hr />
              <h3> <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-h3-name' ); ?>" style="display: none;"><?php echo esc_html( $tospage->post_title );?></div> </h3>
              <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-fields' ); ?>">
                <div id="pmpro_license" class="<?php echo pmpro_get_element_class( 'pmpro_checkout-field', 'pmpro_license' ); ?>" style="display: none;">
                  <?php
                  /**
                   * Hook to run formatting filters before displaying the content of your "Terms of Service" page at checkout.
                   *
                   * @since 2.4.1
                   *
                   * @param string $pmpro_tos_content The content of the post assigned as the Terms of Service page.
                   * @param string $tospage The post assigned as the Terms of Service page.
                   *
                   * @return string $pmpro_tos_content
                   */
                  $pmpro_tos_content = apply_filters( 'pmpro_tos_content', do_shortcode( $tospage->post_content ), $tospage );
                  echo $pmpro_tos_content;
                  ?>
                </div>
                <!-- end pmpro_license -->
                <?php
                if ( isset( $_REQUEST[ 'tos' ] ) ) {
                  $tos = intval( $_REQUEST[ 'tos' ] );
                } else {
                  $tos = "";
                }
                ?>
				<div class="custom-control custom-checkbox">
				<input type="checkbox" name="tos" value="1" class="custom-control-input" id="tos" <?php checked( 1, $tos ); ?> /> <label class="custom-control-label <?php echo pmpro_get_element_class( 'pmpro_label-inline pmpro_clickable', 'tos' ); ?>" for="tos">I agree to the <a href="/terms"><?php printf(__('%s', 'paid-memberships-pro' ), $tospage->post_title);?></a> and <a href="/privacy">Privacy Policy</a></label>

				  </div>
				  </div>
              <!-- end pmpro_checkout-fields --> 
            </div>
            <!-- end pmpro_tos_fields -->
            <?php
            }
            ?>
            <?php do_action("pmpro_checkout_after_tos_fields"); ?>
            <div class="<?php echo pmpro_get_element_class( 'pmpro_checkout-field pmpro_captcha', 'pmpro_captcha' ); ?>">
              <?php
              global $recaptcha, $recaptcha_publickey;
              if ( $recaptcha == 2 || $recaptcha == 1 ) {
                echo pmpro_recaptcha_get_html( $recaptcha_publickey, NULL, true );
              }
              ?>
            </div>
            <!-- end pmpro_captcha -->
            
            <?php
            do_action( 'pmpro_checkout_after_captcha' );
            ?>
            <?php do_action("pmpro_checkout_before_submit_button"); ?>
            <div class="<?php echo pmpro_get_element_class( 'pmpro_submit' ); ?>">
              <hr />
              <?php if ( $pmpro_msg ) { ?>
              <div id="pmpro_message_bottom" class="<?php echo pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ); ?>"><?php echo $pmpro_msg; ?></div>
              <?php } else { ?>
              <div id="pmpro_message_bottom" class="<?php echo pmpro_get_element_class( 'pmpro_message' ); ?>" style="display: none;"></div>
              <?php } ?>
              <?php if($pmpro_review) { ?>
              <span id="pmpro_submit_span">
              <input type="hidden" name="confirm" value="1" />
              <input type="hidden" name="token" value="<?php echo esc_attr($pmpro_paypal_token); ?>" />
              <input type="hidden" name="gateway" value="<?php echo esc_attr($gateway); ?>" />
              <button type="submit" id="pmpro_btn-submit" class="btn btn-primary btn-lg btn-block waves-effect waves-light" value="<?php _e('Complete Payment', 'paid-memberships-pro' );?> &raquo;" >
              <?php _e('Complete Payment', 'paid-memberships-pro' );?>
              &raquo;</button>
              </span>
              <?php } else { ?>
              <?php
              $pmpro_checkout_default_submit_button = apply_filters( 'pmpro_checkout_default_submit_button', true );
              if ( $pmpro_checkout_default_submit_button ) {
                ?>
              <span id="pmpro_submit_span">
              <input type="hidden" name="submit-checkout" value="1" />
              <button type="submit"  id="pmpro_btn-submit" class="btn btn-primary btn-lg btn-block waves-effect waves-light" value="<?php if($pmpro_requirebilling) { _e('Submit and Check Out', 'paid-memberships-pro' ); } else { _e('Submit and Confirm', 'paid-memberships-pro' );}?> &raquo;">
              <?php if($pmpro_requirebilling) { _e('Submit and Check Out', 'paid-memberships-pro' ); } else { _e('Submit and Confirm', 'paid-memberships-pro' );}?>
              &raquo;</button>
              </span>
              <?php
              }
              ?>
              <?php } ?>
              <span id="pmpro_processing_message" style="visibility: hidden;">
              <?php
              $processing_message = apply_filters( "pmpro_processing_message", __( "Processing...", 'paid-memberships-pro' ) );
              echo $processing_message;
              ?>
              </span> </div>
          </form>
          <?php do_action('pmpro_checkout_after_form'); ?>
        </div>
        <!-- end pmpro_level-ID --> 
        
        <!--/.Card--> 
        
      </div>
      <!--Grid column--> 
      
      <!--Grid column-->
      
      <div class="col-md-4 mb-4"> 
        
        <!-- Heading -->
        <h4 class="d-flex justify-content-between align-items-center mb-3"> <span class="text-muted">Your cart</span> <span class="badge badge-secondary badge-pill">1</span> </h4>
        
        <!-- Cart -->
        <ul class="list-group mb-3 z-depth-0">
          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0"><?php printf(__('Selected Plan: <strong>%s</strong>', 'paid-memberships-pro' ), $pmpro_level->name);?></h6>
              <small class="text-muted"></small> </div>
          </li>
          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0"><?php printf(__('Billed Per: <strong>%s</strong>', 'paid-memberships-pro' ), $pmpro_level->cycle_period);?></h6>
              <small class="text-muted"></small> </div>
          </li>
          <li class="list-group-item d-flex justify-content-between"> <span>Total (USD)</span> <strong>$<?php echo number_format((float)$pmpro_level->billing_amount, 2, '.', '');?></strong> </li>
        </ul>
        <!-- Cart --> 
        
        <!-- Promo code -->
        <div class="card p-2" style="display: none;">
          <div class="input-group">
            <input type="text" class="form-control pmpro_required" placeholder="Promo code" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-secondary btn-md waves-effect m-0" type="button">Redeem</button>
            </div>
          </div>
        </div>
        <!-- Promo code --> 
        
      </div>
      <!--Grid column--> 
    </div>
    <!--Grid row--> 
    
  </div>
</main>

 <script type="text/javascript" src="/assets/pages/jquery.min.js"></script><script type="text/javascript" src="/assets/pages/popper.min.js"></script><script type="text/javascript" src="/assets/pages/bootstrap.min.js"></script><script type="text/javascript" src="/assets/pages/mdb.min.js"></script><script type="text/javascript">{    // Animations initialization
    new WOW().init();}</script><div class="hiddendiv common"></div>
