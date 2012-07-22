/**
 * User: mgyura
 * Date: 6/26/12
 */

jQuery(window).load(function () {
    jQuery('.flexslider').flexslider({
        slideshow: true,
        controlNav: pydsmug.locationicon,
        animation: pydsmug.animate,
        smoothHeight: pydsmug.smoothtall,
        directionNav: false,
        touch: true
    });
});