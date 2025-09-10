<?php
/**
 * Plugin Name: Remote Plugin Installer
 * Description: Download and install plugins from a remote site.
 * Version: 1.0.0
 * Author: Example Author
 * Text Domain: remote-plugin-installer
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'RPI_DIR' ) ) {
    define( 'RPI_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'RPI_URL' ) ) {
    define( 'RPI_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Load plugin text domain for translations.
 */
function rpi_load_textdomain() {
    load_plugin_textdomain( 'remote-plugin-installer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'rpi_load_textdomain' );

require_once RPI_DIR . 'includes/api-client.php';
require_once RPI_DIR . 'includes/installer.php';
require_once RPI_DIR . 'admin/settings-page.php';
require_once RPI_DIR . 'admin/plugin-list.php';

/**
 * Register settings page under Settings menu.
 */
function rpi_add_admin_menu() {
    add_options_page(
        __( 'Plugin Remoto', 'remote-plugin-installer' ),
        __( 'Plugin Remoto', 'remote-plugin-installer' ),
        'manage_options',
        'remote-plugin-installer',
        'rpi_render_settings_page'
    );
}
add_action( 'admin_menu', 'rpi_add_admin_menu' );

/**
 * Enqueue admin styles on plugin page.
 */
function rpi_enqueue_assets( $hook ) {
    if ( 'settings_page_remote-plugin-installer' !== $hook ) {
        return;
    }

    wp_enqueue_style( 'rpi-admin', RPI_URL . 'assets/css/style.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'rpi_enqueue_assets' );
