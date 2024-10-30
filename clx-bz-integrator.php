<?php
/**
 * Plugin Name: CLX.bz Integrator
 * Plugin URI: clx.bz
 * Description: CLX.bz Link Shortner And Interstitial Adserver Integration Plugin
 * Version: 1.0
 * Author: CLX.bz
 * Author URI: CLX.bz
 * License: GPL2
 */
 
add_action('admin_menu', 'clx_create_menu');


function clx_activate() {
	update_option( 'selector', 'a' );
	$options = get_option( 'type' );
	$options['site'] = 1;
	update_option('type', $options);
}
register_activation_hook( __FILE__, 'clx_activate' );

function clx_create_menu() {


	add_menu_page('CLX.bz Integration Plugin Settings', 'CLX.bz Settings', 'administrator', __FILE__, 'clx_settings_page',plugins_url('link.png', __FILE__));


	add_action( 'admin_init', 'register_clxsettings' );
}


function register_clxsettings() {

	register_setting( 'clx-settings-group', 'api_key' );
	register_setting( 'clx-settings-group', 'selector' );
	register_setting( 'clx-settings-group', 'type' );
	
}

function clx_settings_page() {
?>
<div class="wrap">
<h2>CLX.bz Integration Plugin Settings</h2>

<form method="post" action="options.php">
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByTagName("input");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
    <?php settings_fields( 'clx-settings-group' ); ?>
    <?php do_settings_sections( 'clx-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API Key</th>
        <td><input type="text" name="api_key" value="<?php echo esc_attr( get_option('api_key') ); ?>" /> Get this from <a href="http://clx.bz/user">http://clx.bz/user</a></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Selector</th>
        <td><input type="text" name="selector" value="<?php echo esc_attr( get_option('selector') ); ?>" /> JQuery Selector to use. Default is 'a' which is all links. Leave as defualt to shorten and track all links.</td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Types of page to use plugin on:</th>
        <td><?php $options = get_option( 'type' ); ?>
				<table>
				<tr>
				<td>Entire Site:</td> <td><input type="checkbox" onClick="toggle(this)" name="type[site]" value="1"<?php checked( isset( $options['site'] ) ); ?> />  </td></tr>
				<td>Home Page: </td> <td><input type="checkbox" name="type[home]" value="1"<?php checked( isset( $options['home'] ) ); ?> /> </td></tr>
				<td>Pages:</td> <td> <input type="checkbox" name="type[page]" value="1"<?php checked( isset( $options['page'] ) ); ?> /> </td></tr>
				<td>Posts: </td> <td><input type="checkbox" name="type[posts]" value="1"<?php checked( isset( $options['posts'] ) ); ?> /> </td></tr>
				<td>Category Pages: </td> <td><input type="checkbox" name="type[category]" value="1"<?php checked( isset( $options['category'] ) ); ?> /></td></tr>
				<td>Blog Page:</td> <td> <input type="checkbox" name="type[blog]" value="1"<?php checked( isset( $options['blog'] ) ); ?> /></td></tr>
				<td>Tag Page:</td> <td> <input type="checkbox" name="type[tag]" value="1"<?php checked( isset( $options['tag'] ) ); ?> /></td></tr>
				</table>
				</td>

        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>

</div>
<?php }

add_action('wp_head', clx_header);

function clx_header() {

	echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
<script src='http://clx.bz/urlshortener.js'></script>
";

}

add_action('wp_footer', clx_footer);

function clx_footer() {
$options = get_option('type');
if(isset($options['site'])) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['home']) && is_home() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['page']) && is_page() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['posts']) && is_single() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['category']) && is_category() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['blog']) && is_front_page() && is_home() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
}

if(isset($options['tag']) && is_tag() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clx.bz',
		key:'".get_option('api_key')."'
	});
</script>";
}

}

?>