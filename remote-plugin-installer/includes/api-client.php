<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Simple API client for communicating with remote site.
 */
class RPI_API_Client {

    /**
     * Base URL of remote API.
     *
     * @var string
     */
    private $base_url = 'https://midominio.com/api/';

    /**
     * Validate a token against the API.
     *
     * @param string $token API token.
     * @return array|false
     */
    public function validate_token( $token ) {
        $response = wp_remote_post(
            $this->base_url . 'validate-token',
            array(
                'headers' => array( 'Content-Type' => 'application/json' ),
                'body'    => wp_json_encode( array( 'token' => $token ) ),
                'timeout' => 15,
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( isset( $data['valid'] ) && $data['valid'] ) {
            return $data;
        }

        return false;
    }

    /**
     * Get available plugins from API.
     *
     * @param string $token API token.
     * @return array|false
     */
    public function get_plugins( $token ) {
        $transient_key = 'rpi_plugins_' . md5( $token );
        $plugins       = get_transient( $transient_key );

        if ( false === $plugins ) {
            $url      = $this->base_url . 'my-plugins?token=' . urlencode( $token );
            $response = wp_remote_get( $url, array( 'timeout' => 15 ) );

            if ( is_wp_error( $response ) ) {
                return false;
            }

            $plugins = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( is_array( $plugins ) ) {
                set_transient( $transient_key, $plugins, HOUR_IN_SECONDS );
            }
        }

        return $plugins;
    }
}
