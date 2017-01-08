var files;

function formContactAction() {
    var data = $('#form-contact').serialize();

    $.ajax({
        type: 'POST',
        url: $('#form-contact').attr('data-url'),
        data: data,
        dataType: 'json',
        success: function (jsonData) {
            if (jsonData.success) {
                $('#modal-contact .modal-body').html(jsonData.content);
                $('#modal-contact .modal-footer button.btn-close').html('Закрыть');
                $('#contact-action').addClass('hide');
            } else {

            }
        },
        error: function () {
            alert('Server error');
        }
    });
}

function formContact(action, id) {
    var data = {};
    $('#modal-contact').modal('show');
    $('#modal-contact .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');
    $('#modal-contact .modal-footer button.btn-close').html('Отмена');
    $('#modal-contact .modal-footer button.btn-action').attr('onclick', 'formContactAction()');
    $('#contact-action').addClass('hide');
    switch (action) {
        case "ch":
            $('#modal-contact .header-content').html('Редактирование контакта');
            $('#modal-contact .modal-footer button.btn-action').html('Изменить');
            data['id'] = id;
            break;
        case "rem":
            $('#modal-contact .header-content').html('Подтверждение удаления контакта');
            $('#modal-contact .modal-footer button.btn-action').html('Удалить');
            data['id'] = id;
            break;
        default:
            $('#modal-contact .header-content').html('Новый контакт');
            $('#modal-contact .modal-footer button.btn-action').html('Добавить');
    }

    $.ajax({
        type: 'POST',
        url: $('#modal-contact').attr('data-url'),
        data: data,
        dataType: 'json',
        success: function (jsonData) {
            $('#modal-contact .modal-body').html(jsonData.content);
            $('#contact-action').removeClass('hide');
        },
        error: function () {
            alert('Server error');
        }
    });
}

function formUploadContactsAction() {
    event.stopPropagation();
    event.preventDefault();

    var data = new FormData();
    $.each(files, function(key, value) {
        data.append(key, value);
    });
    
    $.ajax({
        type: 'POST',
        url: $('#form-contacts-upload').attr('data-url'),
        processData: false,
        contentType: false,
        data: data,
        dataType: 'json',
        success: function (jsonData) {
            if (jsonData.success) {
                $('#modal-contact .modal-body').html(jsonData.content);
                $('#contact-action').addClass('hide');
            } else {

            }
        },
        error: function () {
            alert('Server error');
        }
    });
}

function formUploadContacts() {
    $('#modal-contact').modal('show');
    $('#modal-contact .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');
    $('#modal-contact .modal-footer button.btn-close').html('Отмена');
    $('#modal-contact .header-content').html('Загрузка файла с контактами');
    $('#modal-contact .modal-footer button.btn-action').attr('onclick', 'formUploadContactsAction()');
    $('#modal-contact .modal-footer button.btn-action').html('Загрузить');
    $('#contact-action').addClass('hide');

    $.ajax({
        type: 'POST',
        url: $('#action-contacts-upload').attr('data-url'),
        dataType: 'json',
        success: function (jsonData) {
            $('#modal-contact .modal-body').html(jsonData.content);
            $('#contact-action').removeClass('hide');


            $('#form-contacts-upload input[type=file]').on('change', prepareUpload);
            $('#form-contacts-upload').on('submit', formUploadContactsAction);
        },
        error: function () {
            alert('Server error');
        }
    });
}

function prepareUpload(event)
{
    files = event.target.files;
}

function actionCheckbox(e) {
    if ($(e).html() == 'Снять все отметки') {
        $(e).html('Отметить все');
        var check = false;
    } else {
        $(e).html('Снять все отметки');
        var check = true;
    }
    $('input[type="checkbox"]').each(function () {
        $(this).prop('checked', check);
    });
}

jQuery(document).ready(function () {

});