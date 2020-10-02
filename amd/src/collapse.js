// WWU Addon. Allows collapsing of topics in courses.
// Robin Tschudi

define(['jquery', 'core/url'], function ($, url) {

    var hide = function (section) {
        $("#section-" + section).find(".content").each(function (index, element) {
            element.hidden = true;
        });
        let arrow = $("#collapseicon" + section);
        arrow.attr("src", url.fileUrl("/pix/t", "collapsed.png"));
        arrow.click(function () { show(section); });
    };

    var show = function (section) {
        $("#section-" + section).find(".content").each(function (index, element) {
            element.hidden = false;
        });
        let arrow = $("#collapseicon" + section);
        arrow.attr("src", url.fileUrl("/pix/t", "expanded.png"));
        arrow.click(function () { hide(section); });
    };

    var init = function (sectionindex) {
        let arrow = $("#collapseicon" + sectionindex);
        arrow.click(function () {hide(sectionindex); });
    };
    return {
        init: init,
    };
});