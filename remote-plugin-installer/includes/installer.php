<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Download and install plugin from a remote zip URL.
 *
 * @param string $download_url URL to the plugin zip file.
 * @return true|WP_Error
 */
function rpi_install_remote_plugin( $download_url ) {
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';

    $upgrader = new Plugin_Upgrader();
    $result   = $upgrader->install( $download_url );

    if ( is_wp_error( $result ) ) {
        return $result;
    }

    if ( $result ) {
        return true;
    }

    return new WP_Error( 'install_failed', __( 'Installation failed.', 'remote-plugin-installer' ) );
}
