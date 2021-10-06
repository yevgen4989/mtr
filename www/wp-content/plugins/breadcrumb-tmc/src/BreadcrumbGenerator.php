<?php

/**
 * @author: przemyslaw.jaworowski@gmail.com
 * Date: 2020-03-15
 * Time: 06:06
 */

namespace breadcrumb_tmc\v1_3_6;
use pathnode\PathNode;
use sundawp\v1_0_9\SundaWP;


if ( !class_exists( 'sundawp\v1_0_9\SundaWP' ) ) {

    require dirname( plugin_dir_path( __FILE__ ) ) . '/lib/SundaWP/SundaWP.php';
}


class BreadcrumbGenerator {

    public static function getDisplay() {

        /** @var string[] $nodes */
        $nodes = array();
        global $wp;

        //  Apply filter - Trim Words
        $trimWords         = apply_filters( 'breadcrumbTmc/trimWords', 55 );

	    //  Apply filter - Ending Character
	    $endingCharacter   = apply_filters( 'breadcrumbTmc/endingCharacter', '&hellip;' );

        //  Apply filter Separator Mark
        $separatorMark    = apply_filters( 'separatorMark_breadcrumbTmc' , '/' ); /* deprecated */
        $separatorMark    = apply_filters( 'breadcrumbTmc/separatorMark' , $separatorMark );


	    //  ----------------------------------------
	    //  Home
	    //  ----------------------------------------

	    if ( SundaWP::getHomePathLinkHtml() ) {

		    $homeText  = apply_filters( 'homeText', __('Главная', 'breadcrumb-tmc') ); /* Deprecated */
		    $homeText  = apply_filters( 'breadcrumbTmc/homeLabel', $homeText );

		    $homeNode  = new PathNode();
	        $homeNode->setLabel( wp_trim_words( $homeText, $trimWords, $endingCharacter ) );
	        $homeNode->setHref( get_home_url() );
	        $homeNode->setPriority( 100 );
	        $homeNode->setSeparator( $separatorMark );

            $nodes[]   = $homeNode;
        }


        //  ----------------------------------------
        //  Post Type Archive
        //  ----------------------------------------

        if ( ( ( is_archive() || is_single() || is_home() ) and !is_front_page() ) and SundaWP::getArchiveUrl() ) {

	        $archiveLabel = apply_filters( 'breadcrumbTmc/archiveLabel', __( null, 'breadcrumb-tmc') );

	        if ( !$archiveLabel ) {

		        $archiveLabel = SundaWP::getArchiveLabel();
	        }

	        $archiveNode = new PathNode();
	        $archiveNode->setLabel( wp_trim_words( $archiveLabel, $trimWords, $endingCharacter ) );
	        $archiveNode->setHref( SundaWP::getArchiveUrl() );
	        $archiveNode->setName( 'archiveNode' );
	        $archiveNode->setPriority( 200 );
	        $archiveNode->setSeparator( $separatorMark );

            $nodes[] = apply_filters( 'breadcrumbTmc/archiveNode', $archiveNode );

	        //  Clear array from null elements
	        $nodes = array_filter( $nodes );
        }


        //  ----------------------------------------
        //  Category Archive
        //  ----------------------------------------

        if ( is_category() and SundaWP::getCategoryUrl() ) {

		    $categoryNode = new PathNode();
	        $categoryNode->setLabel( wp_trim_words( SundaWP::getCategoryLabel(), $trimWords, $endingCharacter ) );
	        $categoryNode->setHref( SundaWP::getCategoryUrl() );
	        $categoryNode->setPriority( 300 );
	        $categoryNode->setSeparator( $separatorMark );

	        $nodes[] = $categoryNode;
        }


        //  ----------------------------------------
        //  Tag archive
        //  ----------------------------------------

        if ( is_tag() and SundaWP::getTagUrl() ) {

	        $tagNode = new PathNode();
	        $tagNode->setLabel( wp_trim_words( SundaWP::getTagLabel(), $trimWords, $endingCharacter ) );
	        $tagNode->setHref( SundaWP::getTagUrl() );
	        $tagNode->setPriority( 300 );
	        $tagNode->setSeparator( $separatorMark );

	        $nodes[] = $tagNode;
        }


        //  ----------------------------------------
        //  Author
        //  ----------------------------------------

        if ( is_author() and get_author_posts_url( get_the_author_meta( 'ID' ) ) ) {

            $authorNode = new PathNode();
            $authorNode->setLabel( wp_trim_words( get_the_author_meta( 'display_name' ), $trimWords, $endingCharacter ) );
            $authorNode->setHref( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
            $authorNode->setPriority( 300 );
            $authorNode->setSeparator( $separatorMark );

            $nodes[] = $authorNode;
        }


        //  ----------------------------------------
        //  Single post
        //  ----------------------------------------

        if ( ( ( is_single() || is_page() ) and !is_front_page() ) and SundaWP::getSingleUrl() ){

	        $parentNodes = array(); //  Parent nodes in natural order.

	        $postParentId = wp_get_post_parent_id( get_queried_object_id() );

	        while( $postParentId ){

		        $ParentNode = new PathNode();
		        $ParentNode->setLabel( get_the_title( $postParentId ) );
		        $ParentNode->setHref( get_permalink( $postParentId ) );
		        $ParentNode->setPriority( 400 );
		        $ParentNode->setSeparator( $separatorMark );

		        $parentNodes[] = $ParentNode;

		        $postParentId = wp_get_post_parent_id( $postParentId );
	        }

	        
	        // Switch order and merge.
	        $parentNodes    = array_reverse( $parentNodes );
	        $nodes          = array_merge( $nodes, $parentNodes );


            // Terms of post

            $termsTaxonomy = apply_filters( 'breadcrumbTmc/termsNode/taxonomyName', false );

            if ( !taxonomy_exists( $termsTaxonomy ) ){

                $termsTaxonomy = false;
            }

            $terms = get_the_terms( get_queried_object_id(), $termsTaxonomy );

            if ( $terms ){

                foreach( $terms as $term ){

                    $termLink = get_term_link( $term->term_id, $termsTaxonomy );
                    $termName = $term->name;
                    $TermsNode = new PathNode();
                    $TermsNode->setLabel( $termName );
                    $TermsNode->setHref( $termLink );
                    $TermsNode->setPriority( 500 );
                    $TermsNode->setSeparator( $separatorMark );

                    $TermsNodes[] = $TermsNode;
                }

                $nodes = array_merge( $nodes, $TermsNodes );
            }


            $SingleNode = new PathNode();
	        $SingleNode->setLabel( wp_trim_words( SundaWP::getSingleLabel(), $trimWords, $endingCharacter ) );
	        $SingleNode->setHref( SundaWP::getSingleUrl() );
	        $SingleNode->setPriority( 600 );
	        $SingleNode->setSeparator( $separatorMark );

            $nodes[] = $SingleNode;
        }


	    //  ----------------------------------------
	    //  404 page
	    //  ----------------------------------------

	    if ( is_404() ) {

		    $pageNotFoundNode = new PathNode();
		    $pageNotFoundNode->setLabel( wp_trim_words( SundaWP::getPageNotFoundLabel(), $trimWords, $endingCharacter ) );
		    $pageNotFoundNode->setHref( SundaWP::getPageNotFoundUrl() );
		    $pageNotFoundNode->setPriority( 700 );
		    $pageNotFoundNode->setSeparator( $separatorMark );

	        $nodes[] = $pageNotFoundNode;
	    }



//        $numOfArrayElements = count( $nodes );
//        //  Apply filter lastNode
//        $nodes[$numOfArrayElements - 1] = apply_filters( 'breadcrumbTmc/lastNode', $nodes[$numOfArrayElements - 1] );
//        //  Apply filter allNodes
//        $nodes = apply_filters( 'breadcrumbTmc/allNodes', $nodes );
//        //  Clear array from null elements
//        $nodes = array_filter( $nodes );
//        foreach ( $nodes as $node ) {
//
//            if (rtrim($node->getHref(), '/') == rtrim(home_url($wp->request), '/')){
//                $nodesString[] = sprintf('<li class="breadcrumb-item active">%1$s</li> ',$node->getLabel() );
//            }else{
//                $nodesString[] = sprintf('<li class="breadcrumb-item">%1$s</li> ',$node->getDisplay() );
//            }
//        }
//        //  Lets rock! Return
//        return '<ol class="breadcrumb">'.implode( '', $nodesString).'</ol>';
//
//
//
//




		$numOfArrayElements = count( $nodes );
	    //  Apply filter lastNode
	    $nodes[$numOfArrayElements - 1] = apply_filters( 'breadcrumbTmc/lastNode', $nodes[$numOfArrayElements - 1] );
	    //  Apply filter allNodes
	    $nodes = apply_filters( 'breadcrumbTmc/allNodes', $nodes );
	    //  Clear array from null elements
	    $nodes = array_filter( $nodes );
        $numOfArrayElements = count( $nodes );
        //  Folding individual crumbs
        $i = 0;
		foreach ( $nodes as $key => $node ) {

		    $i++;

            if (rtrim($node->getHref(), '/') == rtrim(home_url($wp->request), '/')){
                $nodeElement = sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item"><span itemprop="name">%1$s</span></a><meta itemprop="position" content="'.($key+1).'"/></li>',$node->getLabel() );
            }else{
                $nodeElement = sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="text-mblue hover:underline">%1$s<meta itemprop="position" content="'.($key+1).'"/></li> ',$node->getDisplay() );
            }

		    $nodeSeparator  = sprintf('<li><span class="mx-2"> %1$s </span></li>', $node->getSeparator() );

		    if ( $i == $numOfArrayElements ) {

                //  Last crumb must have empty separator
                $nodeSeparator = '';
            }

            $nodesString[] = $nodeElement.$nodeSeparator;
		}

//        <ul itemscope itemtype="http://schema.org/BreadcrumbList">
//               <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
//                      <a itemprop="item" href="ссылка">
//                             <span itemprop="name">название_1_пункта </span>
//                      </a>
//                      <meta itemprop="position" content="1" />
//               </li>
//               <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
//                     <a itemprop="item" href="ссылка" >
//                            <span itemprop="name">название_2_пункта</span>
//                     </a>
//                     <meta itemprop="position" content="2" />
//               </li>
//               <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
//                     <a itemprop="item" href="ссылка">
//                            <span itemprop="name">название_3_пункта</span>
//                     </a>
//                     <meta itemprop="position" content="3" />
//               </li>
//        </ul>




	    //  Lets rock!
        return '<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="list-reset flex text-gray-dark">'.implode( '', $nodesString).'</ol>';
    }


    /**
     * Prints out getDisplay() method.
     *
     * @return void
     */

    public static function printDisplay() {

        echo static::getDisplay();
    }
}
