// WWU Addon. Allows collapsing of topics in courses.
// Robin Tschudi

define(['jquery'], function ($) {

    const collapsed_url = M.util.image_url("t/collapsed", "core");
    const expanded_url = M.util.image_url("t/expanded", "core");

    var hide = function (section) {
        $("#section-" + section).find(".content").each(function (index, element) {
            element.hidden = true;
        });
        let arrow = $("#collapseicon" + section);
        arrow.attr("src", collapsed_url);
        arrow.click(function () { arrow.unbind("click"); show(section); });
    };

    var show = function (section) {
        $("#section-" + section).find(".content").each(function (index, element) {
            element.hidden = false;
        });
        let arrow = $("#collapseicon" + section);
        arrow.attr("src", expanded_url);
        arrow.click(function () { arrow.unbind("click"); hide(section); });
    };

    var init = function (sectionindex) {
        let arrow = $("#collapseicon" + sectionindex);
        arrow.click(function () {arrow.unbind("click"); hide(sectionindex);});
    };
    return {
        init: init,
    };
});