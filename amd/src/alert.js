// WWU addon: Allows hiding of alerts via session cookie.
// t_reis06
/* jshint ignore:start */
define(['jquery'], function($) {

    "use strict"; // jshint ;_;

    return {
        init: function() {
            $(document).ready(function($) {
                $('.useralerts .close').on('click', function(e){
                    var elem = $(e.target).parentsUntil(".essentialalerts", ".useralerts")[0];
                    document.cookie = elem.id + '=closed;path=/';
                });
            });
        }
    };
});
/* jshint ignore:end */
