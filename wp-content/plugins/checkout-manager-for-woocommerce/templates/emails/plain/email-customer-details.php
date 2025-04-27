<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo strtoupper( __( 'Additional details', 'woocommerce' ) ) . "\n\n";

foreach ( $fields as $field ) {
    echo wp_kses_post( $field['label'] ) . ': ' . wp_kses_post( $field['value'] ) . "\n";
}
