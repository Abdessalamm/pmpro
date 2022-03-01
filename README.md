Hi There,

This is a better and more customized Paid Memberships Pro Checkout Page. I only had less than an hour. So. Its best I could do.

Use it if you need it. I left more hidden features in there like coupon area. you can unhide it and add it to form in order for it to work. and CSS and JS you can use for the whole site not just this page.


If querry giving you trouble like There are JavaScript errors on the page. Please contact the webmaster. Just remove it.

Most of modern themes come with JS and CSS of this page. So just remove css rel only and JS and see. if you dont like it add it back.

There is also an option to add the form into a card so it will be me visible. Just change 
``` <div id="pmpro_level-<?php echo $pmpro_level->id; ?>" class="<?php echo pmpro_get_element_class( $pmpro_checkout_gateway_class, 'pmpro_level-' . $pmpro_level->id ); ?>"> ``` to ``` <div id="pmpro_level-<?php echo $pmpro_level->id; ?>" class="card <?php echo pmpro_get_element_class( $pmpro_checkout_gateway_class, 'pmpro_level-' . $pmpro_level->id ); ?>"> ```
  
  And 
``` <form id="pmpro_form" class="<?php echo pmpro_get_element_class( 'pmpro_form' ); ?>" action="<?php if(!empty($_REQUEST['review'])) echo pmpro_url("checkout", "?level=" . $pmpro_level->id); ?>" method="post"> ```
to ``` <form id="pmpro_form" class="card-body <?php echo pmpro_get_element_class( 'pmpro_form' ); ?>" action="<?php if(!empty($_REQUEST['review'])) echo pmpro_url("checkout", "?level=" . $pmpro_level->id); ?>" method="post">" ```
  
  
  I might add a better style soon when i get sometime.
