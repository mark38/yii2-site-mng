var content = {
    save : function(index)
    {
        flash.load();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var form = $('#form-content-'+index).serialize();

        /*alert(form);*/

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

function getChildren(e)
{
    var parent = $(e).attr('data-parent');
    var categoriesId = $(e).attr('data-categories-id');
    var level = $(e).attr('data-level');

    if ($(e).attr('data-status') == 'hide') {
        $(e).attr('data-status', 'show');
        $(e).html('<i class="fa fa-plus-square-o" aria-hidden="true"></i>');

        $('#children-block-link-'+parent).slideUp(100, function () {
            $('#children-block-link-'+parent).html('');
        });
    } else {
        $(e).attr('data-status', 'hide');
        $(e).html('<i class="fa fa-minus-square-o" aria-hidden="true"></i>');

        $('#children-block-link-'+parent).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
        $('#children-block-link-'+parent).slideDown(200);

        $.ajax({
            type: 'POST',
            url: '/mng/map/get-children',
            data: {parent: parent, categories_id: categoriesId, level: level},
            dataType: 'json',
            success: function (jsonData) {
                $('#children-block-link-'+parent).html(jsonData.content);
            },
            error: function () {
                alert('Server error');
            }
        });
    }
}

$( document ).ready(function() {
});