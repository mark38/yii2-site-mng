var closeModel;

function getOrder(id)
{
    $('#modal-order').modal('show');
    closeModel = closeModel ? closeModel : $('#modal-order .modal-header').html();
    $('#modal-order .modal-header').html(closeModel);
    $('#modal-order .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');

    $.ajax({
        type: 'POST',
        url: $('#modal-order').attr('data-url'),
        data: {'id': id},
        dataType: 'json',
        success: function (jsonData) {
            $('#modal-order .modal-header').html(jsonData.head + $('#modal-order .modal-header').html());
            $('#modal-order .modal-body').html(jsonData.content);
        },
        error: function () {
            alert('Server error');
        }
    });
}