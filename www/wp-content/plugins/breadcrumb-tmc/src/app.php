<?php

/**
 * @author: przemyslaw.jaworowski@gmail.com
 * Date: 2021-04-15
 * Time: 06:06
 */


use breadcrumb_tmc\v1_3_6\BreadcrumbGenerator;

if ( ! class_exists( 'pathnode\PathNode' ) ) {
    require __DIR__ . '/models/PathNode.php';
}

if ( ! class_exists( 'BreadcrumbGenerator' ) ) {
    require __DIR__ . '/BreadcrumbGenerator.php';
}


function breadcrumb_tmc_addShortCode() {

    function getBreadcrumbDisplay() {

        return BreadcrumbGenerator::getDisplay();
    }

    add_shortcode( 'breadcrumb-tmc', 'getBreadcrumbDisplay' );
}

add_action( 'init', 'breadcrumb_tmc_addShortCode' );
