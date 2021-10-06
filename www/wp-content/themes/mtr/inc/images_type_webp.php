<?php
function filter_webp_quality( $quality, $mime_type ) {
    if ( 'image/webp' === $mime_type ) {
        return 75;
    }
    return $quality;
}
add_filter( 'wp_editor_set_quality', 'filter_webp_quality', 10, 2 );
add_filter(
    'site_option_upload_filetypes',
    function ( $filetypes ) {
        $filetypes = explode( ' ', $filetypes );
        if ( ! in_array( 'webp', $filetypes, true ) ) {
            $filetypes[] = 'webp';
            $filetypes   = implode( ' ', $filetypes );
        }

        return $filetypes;
    }
);
function webp_upload_mimes($existing_mimes) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');
function webp_is_displayable($result, $path) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }

    return $result;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);
