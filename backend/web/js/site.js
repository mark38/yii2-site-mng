var flash = {
    load : function() {
        $('#alert-flash > h4').html('<i class="fa fa-spinner fa-pulse"></i>');
        $('#alert-flash > p').html('');
        $('#alert-flash').fadeIn(100);
    },

    success : function(message) {
        setTimeout(function() {
            $('#alert-flash').fadeIn(function() {
                $('#alert-flash > h4').html('<i class="icon fa fa-check"></i> OK!');
                $('#alert-flash > p').html(message);
            });
        }, 200);
    }
}

$( document ).ready(function() {
    $('#alert-flash > .close').on('click', function(){ $('#alert-flash').fadeOut(100); });
});