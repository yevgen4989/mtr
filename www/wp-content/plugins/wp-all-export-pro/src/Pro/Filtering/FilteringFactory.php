<?php

namespace Wpae\Pro\Filtering;
use Wpae\App\Service\Addons\AddonNotFoundException;
use Wpae\App\Service\Addons\AddonService;

/**
 * Class FilteringFactory
 * @package Wpae\Pro\Filtering
 */
class FilteringFactory
{
    public static function getFilterEngine()
    {
        $addonService = new AddonService();

        if (\XmlExportEngine::$is_comment_export) {
            return new FilteringComments();
        }
        if (\XmlExportEngine::$is_woo_review_export) {
            if(\XmlExportEngine::get_addons_service()->isWooCommerceAddonActive()) {
                return new \Pmwe\Pro\Filtering\FilteringReviews();
            }
        }
        if (\XmlExportEngine::$is_user_export && $addonService->isUserAddonActive()){
            return new \FilteringUsers();
        } else if(\XmlExportEngine::$is_user_export && !$addonService->isUserAddonActive()) {

            throw new AddonNotFoundException(\__('The User Export Add-On Pro is required to run this export. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));
        }

        if ( (!\XmlExportEngine::$is_comment_export && !\XmlExportEngine::$is_woo_review_export) && ( isset(\XmlExportEngine::$exportOptions['cc_type']) && in_array('acf', \XmlExportEngine::$exportOptions['cc_type']) ) && !$addonService->isAcfAddonActive() ){
            throw new AddonNotFoundException(\__('The ACF Export Add-On Pro is required to run this export. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));
        }

        if (\XmlExportEngine::$is_woo_customer_export && $addonService->isUserAddonActive()){

            return new \FilteringCustomers();
        } else if(\XmlExportEngine::$is_woo_customer_export && !$addonService->isUserAddonActive()) {

            throw new AddonNotFoundException(\__('The User Export Add-On Pro is required to run this export. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));
        }


        if(isset (\XmlExportEngine::$exportOptions['cpt']) && 'product' === \XmlExportEngine::$exportOptions['cpt'] && $addonService->isWooCommerceAddonActive()) {
           } else if(isset (\XmlExportEngine::$exportOptions['cpt']) && 'product' === \XmlExportEngine::$exportOptions['cpt'] && !$addonService->isWooCommerceAddonActive()) {
            throw new AddonNotFoundException(\__('The WooCommerce Export Add-On Pro is required to run this export. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));
        }

        if(isset (\XmlExportEngine::$exportOptions['cpt']) && 'shop_order' === \XmlExportEngine::$exportOptions['cpt'] && $addonService->isWooCommerceAddonActive()) {
        }
        else if(isset (\XmlExportEngine::$exportOptions['cpt']) && 'shop_order' === \XmlExportEngine::$exportOptions['cpt'] && !$addonService->isWooCommerceAddonActive()) {
            throw new AddonNotFoundException(\__('The WooCommerce Export Add-On Pro is required to run this export. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));

        }


        if (\XmlExportEngine::$is_taxonomy_export){
            return new FilteringTaxonomies();
        }

        // WooCommerce Post Types
        if ( ! empty(\XmlExportEngine::$post_types) and class_exists('WooCommerce')){
            if (@in_array("product", \XmlExportEngine::$post_types)){

                if(!\XmlExportEngine::get_addons_service()->isWooCommerceAddonActive()) {
                    throw new AddonNotFoundException(\__('The WooCommerce add-on is required. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));
                }

                return new \Pmwe\Pro\Filtering\FilteringProducts();
            }
            if (@in_array("shop_order", \XmlExportEngine::$post_types)){

                if(!@\XmlExportEngine::get_addons_service()->isWooCommerceAddonActive()) {
                    throw new AddonNotFoundException(\__('The WooCommerce add-on is required. If you already own it, you can download the add-on here: <a href="http://www.wpallimport.com/portal/downloads" target="_blank">http://www.wpallimport.com/portal/downloads</a>', \PMXE_Plugin::LANGUAGE_DOMAIN));
                }

                return new \Pmwe\Pro\Filtering\FilteringOrders();
            }
        }
        return new FilteringCPT();
    }

    public static function render_filtering_block( $engine, $isWizard, $post, $is_on_template_screen = false )
    {

        if ( $isWizard or $post['export_type'] != 'specific' ) return;

        if(!current_user_can(\PMXE_Plugin::$capabilities)) return;
        ?>
        <div class="wpallexport-collapsed wpallexport-section closed">
            <div id="wpallexport-filtering-container" class="wpallexport-content-section wpallexport-filtering-section" <?php if ($is_on_template_screen):?>style="margin-bottom: 10px;"<?php endif; ?>>
                <div class="wpallexport-collapsed-header" style="padding-left: 25px;">
                    <h3><?php _e('Filtering Options','wp_all_export_plugin');?></h3>
                </div>
                <div class="wpallexport-collapsed-content" style="padding: 0;">
                    <div class="wpallexport-collapsed-content-inner">
                        <?php
                        if(is_array($post['cpt'])) {
                            $post['cpt'] = $post['cpt'][0];
                        }
                        if (strpos($post['cpt'], 'custom') !== 0) { ?>
                            <?php include_once PMXE_ROOT_DIR . '/views/admin/export/blocks/filters.php'; ?>
                        <?php } else { ?>
                            <div class="wpallexport-free-edition-notice wpallexport-user-export-notice" style="display: block; width: auto;" >
                                <p style="margin-top: 7px; margin-bottom: 7px;">
                                    <?php echo 'The Gravity Forms Export Add-On doesn\'t support filtering at this time.'; ?>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}