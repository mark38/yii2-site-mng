var content = {
    save : function(index)
    {
        flash.load();

        $.ajax({
            type: 'POST',
            url: $('#form-content-'+index).attr('action'),
            data: $('#form-content-'+index).serialize(),
            dataType: 'json',
            success: function(jsonData) {
                flash.success(jsonData.message);
            },
            error: function() {
                alert('Server error');
            }
        });

        return false;
    }
}

$( document ).ready(function() {

});