<?php
/*
Plugin Name: MTP Our Users
Plugin URI: http://mt-performance.net
Description: Create or remove authors but hide them from the users section.
Version: 1.0
Author: Toni Medina
Author URI: http://tonimedina.tumblr.com
License: GPL2
*/
class OurUsers {
	public function __construct() {
		$this->OurUsers_install();
	}
	// DataBase
	public function OurUsers_install() {
		global $wpdb;
		$table = $wpdb->prefix.'our_users';
		
		$sql = "CREATE TABLE IF NOT EXISTS $table(
			ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL DEFAULT '0',
			user_name varchar(60) NOT NULL DEFAULT '',
			time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (id)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		// User Capabilities
		$role = get_role( 'administrator' );
		$role->add_cap('OurUsers_show_plugin');
	}
	// Uninstall
	public function OurUsers_uninstall($drop) {
		global $wpdb;
		if (!defined('WP_UNINSTALL_PLUGIN')) {
			exit();
		}
		$table = $wpdb->prefix.'our_users';
		$drop = $wpdb->query("DROP $table IF EXISTS");
		return $drop;
	}
	
	// Settings
	public function OurUsers_add_options_page() {
		if (is_admin() || current_user_can('OurUsers_show_plugin')) {
			add_management_page('Our Users','Our Users','OurUsers_show_plugin',__FILE__,array('OurUsers','OurUsers_add_options_page_cb'));
		}
	}

	public function OurUsers_add_options_page_cb() {
		global $wpdb, $blog_id;
		// Singlesite Variables
		$prefix         = $wpdb->prefix;
		$table          = $wpdb->prefix.'our_users';
		$users_table    = $wpdb->prefix.'users';
		$usermeta_table = $wpdb->prefix.'usermeta';
		$posts_table    = $wpdb->prefix.'posts';
		// Multisite Variables
		$base_prefix       = $wpdb->base_prefix;
		$ms_table          = $wpdb->prefix.'our_users';
		$ms_users_table    = $wpdb->base_prefix.'users';
		$ms_usermeta_table = $wpdb->base_prefix.'usermeta';
		$ms_posts_table    = $wpdb->prefix.'posts';
		$dbhost            = $wpdb->dbhost;	
		// Form Fields Variables
		$username      = trim($_POST['username']); //login name for the new user
		$first_name    = trim($_POST['first_name']);
		$last_name     = trim($_POST['last_name']);
		$display_name  = trim($_POST['display_name']);
		$rm_user       = trim($_POST['rm_user']);
		$old_user      = trim($_POST['old_user']);
		$new_user      = trim($_POST['new_user']); //Variable used to change the owner of the post
		$existing_user = trim($_POST['existing_user']);
		// Error messages
		$error_msg    = __('All fields are required.','OurUsers');
		$error_msg_rm = __('You have no permission to delete this user.','OurUsers');
		$error_msg_ch = __('One of the users you are trying to change does not exists.','OurUsers');
		$error_msg_su = __('The users you are trying to change are the same.','OurUsers');
		$error_msg_ex = __('The user exist as real user.','OurUsers');
		
		// Other Variables
		$current_time = current_time('mysql');
		
		// Creating The User
		if (isset($_POST['save_user'])) {
			if ($username === '' || $first_name === '' || $last_name === '' || $display_name === '') {
				echo '<div class="error"><p>'.$error_msg.'</p></div>';
			} else {
				$ms_existing_user = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM $ms_users_table WHERE user_login = '$username'"));
				switch ($ms_existing_user) {
					case 0:
					if (is_multisite()) {
						// Queries
						$wpdb->insert($ms_table,array('user_name'=>$username,'time'=>$current_time));
						$wpdb->insert($ms_users_table,array('user_login'=>$username,'user_pass'=>'$P$B8SJkLUmFCgHEug7WwjKfUtmlpfBYN0','user_nicename'=>$username,'user_email'=>'our@users.com','user_registered'=>$current_time,'display_name'=>$display_name));
						$ms_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $ms_users_table WHERE user_login = '$username'"));
						$wpdb->query("UPDATE $ms_table SET user_id = '$ms_uid' WHERE user_name = '$username'");
						// Inserting The Data
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'first_name','meta_value'=>$first_name));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'last_name','meta_value'=>$last_name));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'nickname','meta_value'=>$username));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'description','meta_value'=>''));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'rich_editing','meta_value'=>'true'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'comment_shortcuts','meta_value'=>'false'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'admin_color','meta_value'=>'fresh'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'use_ssl','meta_value'=>'0'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'show_admin_bar_front','meta_value'=>'true'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'dismissed_wp_pointers','meta_value'=>'wp330_toolbar,wp330_media_uploader,wp330_saving_widgets'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'primary_blog','meta_value'=>$blog_id));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>'source_domain','meta_value'=>$dbhost));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>''.$base_prefix.''.$blog_id.'_capabilities','meta_value'=>'a:1:{s:6:"author";s:1:"1";}'));
						$wpdb->insert($ms_usermeta_table,array('user_id'=>$ms_uid,'meta_key'=>''.$base_prefix.''.$blog_id.'_user_level','meta_value'=>'2')); ?>
					
						<div class="updated">
							<p>User has been successfully created.</p>
						</div>
					
					<?php } else {
						// Queries
						$wpdb->insert($table,array('user_name'=>$username,'time'=>$current_time));
						$wpdb->insert($users_table,array('user_login'=>$username,'user_pass'=>'$P$B8SJkLUmFCgHEug7WwjKfUtmlpfBYN0','user_nicename'=>$username,'user_email'=>'our@users.com','user_registered'=>$current_time,'display_name'=>$display_name));
						$uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $users_table WHERE user_login = '$username'"));
						$wpdb->query("UPDATE $table SET user_id = '$uid' WHERE user_name = '$username'");
						// Inserting The Data
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'first_name','meta_value'=>$first_name));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'last_name','meta_value'=>$last_name));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'nickname','meta_value'=>$username));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'description','meta_value'=>''));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'rich_editing','meta_value'=>'true'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'comment_shortcuts','meta_value'=>'false'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'admin_color','meta_value'=>'fresh'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'use_ssl','meta_value'=>'0'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'show_admin_bar_front','meta_value'=>'true'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>''.$prefix.'capabilities','meta_value'=>'a:1:{s:6:"author";s:1:"1";}'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>''.$prefix.'user_level','meta_value'=>'2'));
						$wpdb->insert($usermeta_table,array('user_id'=>$uid,'meta_key'=>'dismissed_wp_pointers','meta_value'=>'wp330_toolbar,wp330_media_uploader,wp330_saving_widgets')); ?>
					
						<div class="updated">
							<p>User has been successfully created.</p>
						</div>
					
					<?php }
					break;
					default:
						echo '<div class="error"><p>'.$error_msg_ex.'</p></div>';	
						break;
				}
			}
		}
		// Deleting The User
		if (isset($_POST['delete_user'])) {
			$admins = $wpdb->get_var($wpdb->prepare("SELECT user_email FROM $users_table WHERE user_login = '$rm_user'"));
			$ms_admins = $wpdb->get_var($wpdb->prepare("SELECT user_email FROM $ms_users_table WHERE user_login = '$rm_user'"));
			if ($rm_user === '') {
				echo '<div class="error"><p>'.$error_msg.'</p></div>';
			} else if (is_multisite() && $ms_admins != 'our@users.com') {
				echo '<div class="error"><p>'.$error_msg_rm.'</p></div>';
			} else if (!is_multisite() && $admins != 'our@users.com') {
				echo '<div class="error"><p>'.$error_msg_rm.'</p></div>';
			} else {
				// Deleting The Data
				if (is_multisite()) {
					$ms_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $ms_users_table WHERE user_login = '$rm_user'"));
					$wpdb->query($wpdb->prepare("DELETE FROM $ms_table WHERE user_name = '$rm_user'"));
					$wpdb->query($wpdb->prepare("DELETE FROM $ms_users_table WHERE user_login = '$rm_user'"));
					$wpdb->query($wpdb->prepare("DELETE FROM $ms_usermeta_table WHERE user_id = '$ms_uid'")); ?>
					
					<div class="updated">
						<p>User has been successfully deleted.</p>
					</div>
					
				<?php } else {
					$uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $users_table WHERE user_login = '$rm_user'"));
					$wpdb->query($wpdb->prepare("DELETE FROM $table WHERE user_name = '$rm_user'"));
					$wpdb->query($wpdb->prepare("DELETE FROM $users_table WHERE user_login = '$rm_user'"));
					$wpdb->query($wpdb->prepare("DELETE FROM $usermeta_table WHERE user_id = '$uid'")); ?>
					
					<div class="updated">
						<p>User has been successfully deleted.</p>
					</div>
					
				<?php }
			}
		}
		
		// Changing The User
		if (isset($_POST['change_user'])) {
			$old_user_star = $wpdb->get_var($wpdb->prepare("SELECT * FROM $users_table WHERE user_login = '$old_user'"));
			$ms_old_user_star = $wpdb->get_var($wpdb->prepare("SELECT * FROM $ms_users_table WHERE user_login = '$old_user'"));
			$new_user_star = $wpdb->get_var($wpdb->prepare("SELECT * FROM $users_table WHERE user_login = '$new_user'"));
			$ms_new_user_star = $wpdb->get_var($wpdb->prepare("SELECT * FROM $ms_users_table WHERE user_login = '$new_user'"));
			var_dump($ms_old_user_star);
			var_dump($ms_new_user_star);
			if ($old_user === '' || $new_user === '') {
				echo '<div class="error"><p>'.$error_msg.'</p></div>';
			} else if (is_multisite() && empty($ms_old_user_star) || is_multisite() && empty($ms_new_user_star)) {
				echo '<div class="error"><p>'.$error_msg_ch.'</p></div>';
			} else if (!is_multisite() && empty($old_user_star) || !is_multisite() && empty($new_user_star)) {
				echo '<div class="error"><p>'.$error_msg_ch.'</p></div>';
			} else {
				// Changing The Data
				if (is_multisite()) {
					$old_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $ms_users_table WHERE user_login = '$old_user'"));
					$new_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $ms_users_table WHERE user_login = '$new_user'"));
					$wpdb->query("UPDATE $ms_posts_table SET post_author = '$new_uid' WHERE post_author = '$old_uid'"); ?>
					
					<div class="updated">
						<p>User has been successfully changed.</p>
					</div>
					
				<?php } else {
					$old_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $users_table WHERE user_login = '$old_user'"));
					$new_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $users_table WHERE user_login = '$new_user'"));
					$wpdb->query("UPDATE $posts_table SET post_author = '$new_uid' WHERE post_author = '$old_uid'"); ?>
					
					<div class="updated">
						<p>User has been successfully changed.</p>
					</div>
					
				<?php }
			}
		}
		if (isset($_POST['convert_user'])) {
			if ($existing_user === '') {
				echo '<div class="error"><p>'.$error_msg.'</p></div>';
			} else {
				if (is_multisite()) {
					$ms_existing_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $ms_users_table WHERE user_login = '$existing_user'"));
					$wpdb->insert($ms_table,array('user_id'=>$ms_existing_uid,'user_name'=>$existing_user,'time'=>$current_time));
					$wpdb->update($ms_users_table,array('user_email'=>'our@users.com',array('user_login'=>$existing_user))); ?>
					<div class="updated">
						<p>User has been successfully converted.</p>
					</div>
				<?php } else {
					$existing_uid = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $users_table WHERE user_login = '$existing_user'"));
					$wpdb->insert($table,array('user_id'=>$existing_uid,'user_name'=>$existing_user,'time'=>$current_time));
					$wpdb->update($users_table,array('user_email'=>'our@users.com',array('user_login'=>$existing_user))); ?>
					<div class="updated">
						<p>User has been successfully converted.</p>
					</div>
				<?php }
			}
		} ?>
		
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Our Users Options Page</h2>
			<form action="" method="post">
				<input name="option_page" type="hidden" value="general" />
				<input name="action" type="hidden" value="update" />
				<input id="_wpnonce" name="_wpnonce" type="hidden" value="f574e0338f" />
				<input name="_wp_http_referer" type="hidden" value="/wp-admin/options-general.php" />
				<h3>Add new User</h3>
				<p>Creates a new Fake User. <i>Please fill out all fields</i>.</p>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="username">Username</label></th>
							<td><input class="regular-text" id="username" placeholder="johndoe" name="username" type="text" value="" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="first_name">First Name</label></th>
							<td><input class="regular-text" id="first_name" placeholder="John" name="first_name" type="text" value="" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="last_name">Last Name</label></th>
							<td><input class="regular-text" id="last_name" placeholder="Doe" name="last_name" type="text" value="" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="display_name">Display Name</label></th>
							<td><input class="regular-text" id="display_name" placeholder="John Doe" name="display_name" type="text" value="" /></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input class="button-primary" id="save_user" name="save_user" type="submit" value="Save User"></p>
			</form>
			<form action="" method="post">
				<input name="option_page" type="hidden" value="general" />
				<input name="action" type="hidden" value="update" />
				<input id="_wpnonce" name="_wpnonce" type="hidden" value="f574e0338f" />
				<input name="_wp_http_referer" type="hidden" value="/wp-admin/options-general.php" />
				<h3>Delete User</h3>
				<p>Deletes a Fake User. <i>Please fill out all fields</i>.</p>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="rm_user">Username</label></th>
							<td><input class="regular-text" id="rm_user" placeholder="johndoe" name="rm_user" type="text" value="" /></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input class="button-primary" id="delete_user" name="delete_user" type="submit" value="Delete User"></p>
			</form>
			<form action="" method="post">
				<input name="option_page" type="hidden" value="general" />
				<input name="action" type="hidden" value="update" />
				<input id="_wpnonce" name="_wpnonce" type="hidden" value="f574e0338f" />
				<input name="_wp_http_referer" type="hidden" value="/wp-admin/options-general.php" />
				<h3>Change User</h3>
				<p>Assigns all post from an user to another. <i>Please fill out all fields</i>.</p>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="old_user">Username</label></th>
							<td><input class="regular-text" id="old_user" placeholder="admin" name="old_user" type="text" value="" /> <span class="description">Enter the username of the actual post author.</span></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="new_user">Username</label></th>
							<td><input class="regular-text" id="new_user" placeholder="johndoe" name="new_user" type="text" value="" /> <span class="description">Enter the username of the new post author.</span></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input class="button-primary" id="change_user" name="change_user" type="submit" value="Change User"></p>
			</form>
			<form action="" method="post">
				<input name="option_page" type="hidden" value="general" />
				<input name="action" type="hidden" value="update" />
				<input id="_wpnonce" name="_wpnonce" type="hidden" value="f574e0338f" />
				<input name="_wp_http_referer" type="hidden" value="/wp-admin/options-general.php" />
				<h3>Convert User</h3>
				<p>Converts a Real User into a Fake User. <i>Please fill out all fields</i>.</p>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="existing_user">Username</label></th>
							<td><input class="regular-text" id="existing_user" placeholder="johnsmith" name="existing_user" type="text" value="" /> <span class="description">Enter the username of an existing user.</span></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input class="button-primary" id="convert_user" name="convert_user" type="submit" value="Convert User"></p>
			</form>
			<h3>Users List</h3>
			<table id="users-list">
				<thead>
					<tr valign="top">
						<td>Username</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if (is_multisite()) {
						$ms_our_users = $wpdb->get_col("SELECT user_name FROM $ms_table");
						foreach ($ms_our_users as $ms_our_user) {
							echo '<tr valign="top"><td>'.$ms_our_user.'</td></tr>';
						}
					} else {
						$our_users = $wpdb->get_col("SELECT user_name FROM $table");
						foreach ($our_users as $our_user) {
							echo '<tr valign="top"><td>'.$our_user.'</td></tr>';
						}
					}
					?>
				</tbody>
			</table>
		</div>
		
		<style type="text/css">
		#users-list { width: 200px; }
		#users-list thead { color: #21759b; font-family: Georgia, 'Times New Roman', 'Bitstream Charter', Times, serif; font-size: 14px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); }
		#users-list thead tr { background: rgb(236,236,236); }
		#users-list tbody tr { background: rgb(252,252,252); }
		#users-list tbody tr:nth-child(2n) { background: rgb(249,249,249); }
		#users-list td { padding: 10px; }
		</style>
	<?php }
}
function OurUsers_admin_menu() {
	OurUsers::OurUsers_add_options_page();
}
add_action('admin_menu','OurUsers_admin_menu');
register_activation_hook(__FILE__,array('OurUsers','OurUsers_install'));
register_uninstall_hook(__FILE__,array('OurUsers','OurUsers_uninstall'));
function OurUsers_admin_init() {
	new OurUsers();
}
add_action('admin_init','OurUsers_admin_init');
function OurUsers_pre_user_query($userSearch) {
	global $wpdb, $post;
	// Singlesite Variables
	$table          = $wpdb->prefix.'our_users';
	$users_table    = $wpdb->prefix.'users';
		
	// Multisite Variables
	$ms_table          = $wpdb->prefix.'our_users';
	$ms_users_table    = $wpdb->base_prefix.'users';
	if ($post->post_type == 'post' || $post->post_type == 'page' || $post->post_type == 'attachment' || $post->post_type == 'revision') {
		return $userSearch;
	} else {
		if (is_multisite()) {
			$userSearch->query_where .= " AND $ms_users_table.ID NOT IN (SELECT user_id FROM $ms_table)";
		} else {
			$userSearch->query_where .= " AND $users_table.ID NOT IN (SELECT user_id FROM $table)";
		}
	}
}
add_action('pre_user_query','OurUsers_pre_user_query');
?>