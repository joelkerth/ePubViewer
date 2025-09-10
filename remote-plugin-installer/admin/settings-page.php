<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render plugin settings page.
 */
function rpi_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $message = '';

    if ( isset( $_POST['rpi_token'] ) ) {
        check_admin_referer( 'rpi_save_token' );
        $token = sanitize_text_field( wp_unslash( $_POST['rpi_token'] ) );
        update_option( 'rpi_api_token', $token );
        delete_transient( 'rpi_plugins_' . md5( $token ) );
        $message = __( 'Token saved.', 'remote-plugin-installer' );
    }

    $token = get_option( 'rpi_api_token', '' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Remote Plugin Installer', 'remote-plugin-installer' ); ?></h1>
        <?php if ( $message ) : ?>
            <div class="notice notice-success"><p><?php echo esc_html( $message ); ?></p></div>
        <?php endif; ?>
        <form method="post">
            <?php wp_nonce_field( 'rpi_save_token' ); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="rpi_token"><?php esc_html_e( 'API Token', 'remote-plugin-installer' ); ?></label>
                    </th>
                    <td>
                        <input name="rpi_token" type="text" id="rpi_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e( 'Enter the API token provided by the remote site.', 'remote-plugin-installer' ); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button( __( 'Save Token', 'remote-plugin-installer' ) ); ?>
        </form>
    </div>
    <?php

    if ( $token ) {
        rpi_render_plugin_list( $token );
    }
}
