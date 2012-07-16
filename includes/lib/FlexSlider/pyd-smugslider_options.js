/**
 * Created with JetBrains PhpStorm.
 * User: mgyura
 * Date: 6/26/12
 * Time: 4:34 PM
 * To change this template use File | Settings | File Templates.
 */

$(window).load(function () {
    $('.flexslider').flexslider({
        slideshow: true,
        controlNav: false,
        animation: "fade",
        smoothHeight: true,
        touch: true
    });
});