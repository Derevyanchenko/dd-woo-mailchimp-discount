jQuery(document).ready(function($) {

    $('.ddwoomd_form').on("submit", function(e) {
        e.preventDefault();

        send_data_to_mailchimp( $(this) );
    });

    /**
     * rewfsd
     */
    function send_data_to_mailchimp(el) {
        $.ajax({
            url: ddWooMD_ajax.ajaxurl,
            type: el.attr('method'),
            data: {
                // action: el.attr('action'),
                action: 'send_data_to_mailchimp',
                nonce: ddWooMD_ajax.nonce,
                email: el.find("#ddwoomd_email").val(),
            },
            success: function(data) {
                console.log( 'success send' );
                console.log(data);
                el.trigger('reset');
                alert(data);

            },
            error: function(error) {
                console.log(error);
                alert(error);
            }
        });
    }

});