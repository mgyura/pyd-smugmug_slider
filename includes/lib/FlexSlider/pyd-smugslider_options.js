/**
 * User: mgyura
 * Date: 6/26/12
 */

jQuery(window).load(function () {
    jQuery('.flexslider').flexslider({
        slideshow: pydsmug.startup,
        controlNav: pydsmug.locationicon,
        animation: pydsmug.animate,
        smoothHeight: pydsmug.smoothtall,
        directionNav: pydsmug.navdirection,
        animationLoop: pydsmug.loopit,
        slideshowSpeed: pydsmug.slidespeed,
        animationSpeed: pydsmug.animatespeed,
        initDelay: pydsmug.delayinit,
        randomize: pydsmug.randomizeit,
        pauseOnHover: pydsmug.hoverpause,
        pauseOnAction: true,
        touch: true
    });
});