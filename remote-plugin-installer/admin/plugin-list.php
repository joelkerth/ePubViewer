<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render list of available plugins for installation.
 *
 * @param string $token API token.
 */
function rpi_render_plugin_list( $token ) {
    $api = new RPI_API_Client();

    $validation = $api->validate_token( $token );
    if ( ! $validation ) {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Invalid token.', 'remote-plugin-installer' ) . '</p></div>';
        return;
    }

    if ( isset( $_POST['rpi_install'] ) && ! empty( $_POST['download_url'] ) ) {
        check_admin_referer( 'rpi_install_plugin' );
        $download_url = esc_url_raw( $_POST['download_url'] );
        $result       = rpi_install_remote_plugin( $download_url );

        if ( is_wp_error( $result ) ) {
            echo '<div class="notice notice-error"><p>' . esc_html( $result->get_error_message() ) . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Plugin installed successfully.', 'remote-plugin-installer' ) . '</p></div>';
        }
    }

    $plugins = $api->get_plugins( $token );
    if ( ! $plugins ) {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Unable to load plugins.', 'remote-plugin-installer' ) . '</p></div>';
        return;
    }

    echo '<div class="rpi-plugin-list">';
    foreach ( $plugins as $plugin ) {
        $name         = isset( $plugin['name'] ) ? $plugin['name'] : '';
        $desc         = isset( $plugin['description'] ) ? $plugin['description'] : '';
        $download_url = isset( $plugin['download_url'] ) ? $plugin['download_url'] : '';

        echo '<div class="rpi-card">';
        echo '<h2>' . esc_html( $name ) . '</h2>';
        echo '<p>' . esc_html( $desc ) . '</p>';
        echo '<form method="post">';
        wp_nonce_field( 'rpi_install_plugin' );
        echo '<input type="hidden" name="download_url" value="' . esc_url( $download_url ) . '" />';
        echo '<button type="submit" name="rpi_install" class="button button-primary">' . esc_html__( 'Install', 'remote-plugin-installer' ) . '</button>';
        echo '</form>';
        echo '</div>';
    }
    echo '</div>';
}
