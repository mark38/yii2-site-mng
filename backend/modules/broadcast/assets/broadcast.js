(function($) {
    var send = false;

    if ($('#mail-send-status').length > 0) getStatus();

    $('#check-user-all').on('click', function() {
        if ($(this).prop('checked')) {
            $('.check-user').prop('checked', true);
        } else {
            $('.check-user').prop('checked', false);
        }
    });


    $('#brodcast-send').on('click', function () {
        $(this).attr('disabled', 'disabled');
        if (!send) {
            send = true;
            var redirect_url = $(this).attr('data-url-redirect');
            var address_url = $(this).attr('data-url-address');
            var broadcast_address = new Array();
            $('.check-user').each(function(){
                var i = broadcast_address.length;
                broadcast_address[i] = new Object();
                broadcast_address[i].id = $(this).attr('data-checkbox-address-id');
                broadcast_address[i].action = $(this).prop('checked') ? 'add' : 'del';
            });

            $.ajax({
                type: 'POST',
                url: address_url,
                data: {broadcast_address: broadcast_address},
                dataType: 'json',
                success: function(jsonData) {
                    window.location.href = redirect_url;
                },
                error: function() {
                    alert('Server error')
                }
            });
        }
    });

    function getStatus() {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var url = $('#mail-send-status').attr('data-status-url');
        var broadcast_send_id = $('#mail-send-status').attr('data-broadcast-send-id');

        $.ajax({
            type: 'POST',
            url: url,
            data: {broadcast_send_id: broadcast_send_id, _csrf : csrfToken},
            dataType: 'json',
            success: function(jsonData) {
                var success = true;
                for (i=0; i<jsonData.address.length; i++) {
                    if (jsonData.address[i].status == 0) {
                        success = false;
                    } else {
                        if ($('#status-'+jsonData.address[i].id).hasClass('text-danger')) {
                            $('#status-'+jsonData.address[i].id).removeClass('text-danger').addClass('text-success').html('Отправлено');
                        }
                    }
                }

                if (!success) {
                    setTimeout(function() {
                        getStatus();
                    }, 2000);
                } else {
                    $('#mail-send-status').removeClass('text-danger').addClass('text-success').html('Письма отправлены');
                }
            },
            error: function() {
                alert('Server error')
            }
        }, 'json');
    }
})(jQuery);