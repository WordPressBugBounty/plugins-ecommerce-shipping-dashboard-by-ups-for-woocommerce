(function ($) {
    "use strict";

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function () {
        $("#ups_integration_now_button").click(function () {
            var valid = true,
                errorMessage = "";

            if (
                $("#store_domain").val() == "" ||
                $("#store_domain").val() == "undefined"
            ) {
                errorMessage = "please enter your store domain \n";
                valid = false;
                $('.storedomain-error-tag').show();
                if (!valid && errorMessage.length > 0) {
                    return false;
                } else {
                    $('.storedomain-error-tag').hide();
                }
            }

            if (
                $("#site_email").val() == "" ||
                $("#site_email").val() == "undefined"
            ) {
                errorMessage = "Please enter your business email address \n";
                valid = false;
                $('.businessmail-error-tag').show();
                if (!valid && errorMessage.length > 0) {
                    return false;
                } else {
                    $('.businessmail-error-tag').hide();
                }
            }

            let counter = 0;
            var checkboxSelectorContainer = document.querySelector('#terms-wrap');
            var checkboxes = checkboxSelectorContainer.querySelectorAll('[type=checkbox]');
            for (let i = 0; i < checkboxes.length; i++) {
                if (!checkboxes[i].checked) {
                    counter++;
                    errorMessage = "please accept terms and conditions \n";
                    valid = false;
                    $('.terms-error-tag').show();
                    if (!valid && errorMessage.length > 0) {
                        return false;
                    }
                }
            }

            if (counter < 1) {
                $('.terms-error-tag').hide();
            }
        });
    });

})(jQuery);
