'use strict';
import $ from 'jquery/dist/jquery.min';
window.$ = window.jQuery = $;
import * as Vivus from 'vivus/dist/vivus';
require('./autocompleteJQueryPlugIn');


import Menu from "./Menu";
import SearchCountry from "./SearchCountry";
import Calculator from "./Calculator";
import SearchPosts from "./SearchPosts";
import ContactsMap from "./ContactsMap";
import FixerPosition from "./FixerPosition";
import ContentGridShower from "./ContentGridShower";
import Recall from "./Recall";
import FastFormController from "./FastFormController";
import FormElementController from "./FormElementController";
import parallaxController from './parallaxController';
import searchContributorChinaController from './serchContributorChinaController';
import FastTrackingFormController from './FastTrackingFormController';
import ReCheckTrackNumberController from './ReCheckTrackNumberController';
import blogPaginatorController from './blogPaginatorController';

$(document).ready(function () {
    new FastFormController();
    new FastTrackingFormController();
    new ReCheckTrackNumberController();
    new Menu();
    new SearchCountry();
    new Calculator();
    new SearchPosts();
    new ContactsMap();
    new FixerPosition('.action-form');
    new FixerPosition('.action-form.country-pc');
    new FormElementController();
    new parallaxController();
    new searchContributorChinaController();
    new blogPaginatorController();

    let gridItems = $('.content-grid__item');
    for (let i = 0; i < gridItems.length; i++) {
        new ContentGridShower(gridItems.eq(i));
    }

    new Recall();

    try {
        new Vivus('fly', {duration: 100});

        new Vivus('world', {duration: 200});

        new Vivus('box', {duration: 200});

        new Vivus('truck', {duration: 200});
    } catch (e) {
    }


    $('.scrollTo').bind('click', function (ev) {
        ev.preventDefault();
        ev.stopPropagation();

        $("html, body").animate({
            scrollTop: $($(this).attr("href")).offset().top + "px"
        }, {
            duration: 500,
            easing: "swing"
        });
    })
});
