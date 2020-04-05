<?php
/**
 * Display Name Username
 *
 * @package DisplayNameUsername
 * @author Alexander O'Mara
 * @copyright 2020 Alexander O'Mara
 * @license GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Display Name Username
 * Plugin URI: https://github.com/AlexanderOMara/wordpress-display-name-username
 * Version: 0.0.0
 * Requires at least: 2.7.0
 * Requires PHP: 5.3.0
 * Author: Alexander O'Mara
 * Author URI: https://alexomara.com
 * Text Domain: display-name-username
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * Description: Force display name to be username.
 */

defined('ABSPATH') or exit;

/**
 * Main class.
 */
class DISPLAY_NAME_USERNAME {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '0.0.0';

	/**
	 * Plugin entry.
	 *
	 * @var string
	 */
	const ENTRY = __FILE__;

	/**
	 * Init plugin.
	 */
	public static function init() {
		add_action('personal_options_update', [static::class, 'fix_post']);
		add_action('edit_user_profile_update', [static::class, 'fix_post']);

		add_action('profile_update', [static::class, 'fix_user']);
		add_action('user_register', [static::class, 'fix_user']);

		add_action('admin_head', [static::class, 'hide_input']);
	}

	/**
	 * Fix POST data before edit_user call.
	 * Prevents changing display name in the first place.
	 *
	 * @param int $user_id User ID.
	 */
	public static function fix_post($user_id) {
		$login_name = isset($_POST['user_login']) ? $_POST['user_login'] : '';
		if ($login_name === '') {
			$user = get_user_by('id', $user_id);
			if ($user && isset($user->user_login)) {
				$login_name = '';
			}
		}
		$_POST['display_name'] = $login_name;
	}

	/**
	 * Fix user by ID, if necessary.
	 * Typically will not do anything since POST data was changed.
	 *
	 * @param int $user_id User ID.
	 */
	public static function fix_user($user_id) {
		$user = get_user_by('id', $user_id);
		if ($user && $user->display_name !== $user->user_login) {
			$user->display_name = $user->user_login;
			wp_update_user($user);
		}
	}

	/**
	 * Hide display name input, it no longer serves any purpose.
	 */
	public static function hide_input() {
		?><style><?php
		?>#profile-page .user-display-name-wrap{display:none}<?php
		?></style><?php
	}
}
DISPLAY_NAME_USERNAME::init();
