jQuery(document).ready(function($ ) {

    $('.ddwmd_form').on("submit", function(e) {
        e.preventDefault();
        send_data_to_mailchimp( $(this) );
    });

    /**
     * Ajax method that sends data to mailchimp and, if successful, displays a success message.
     */
    function send_data_to_mailchimp( el ) {
        $.ajax({
            url: DDWMD_ajax.ajaxurl,
            type: el.attr('method'),
            data: {
                action: 'ddwmd_integrate',
                nonce: DDWMD_ajax.nonce,
                email: el.find("#ddwmd_email").val(),
            },
            success: function(data) {
                const successMessage = data.data.message;
                el.trigger('reset');
                alert(successMessage);

            },
            error: function(error) {
                console.log(error);
                alert(error);
            }
        });
    }

});