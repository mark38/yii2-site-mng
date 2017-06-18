var selectLink = {
    options : {
        'url' : '',
        'inputId' : '',
        'modelName' : '',
        'modelValue' : '',
        'modalId' : '',
    },

    init : function(e, options) {
        this.options = options;
    },

    getLinks : function (categoriesId, parent, e) {
        var getChildren = true;
        if (e) {
            if ($(e).attr('data-status') == 'hide') {
                $(e).attr('data-status', 'show');
                $(e).html('<i class="fa fa-minus-square-o" aria-hidden="true"></i>');
            } else {
                $(e).attr('data-status', 'hide');
                $(e).html('<i class="fa fa-plus-square-o" aria-hidden="true"></i>');
                $('#children-block-link-'+parent).slideUp(200, function() {
                    $('#children-block-link-'+parent).html('')
                });
                getChildren = false;
            }
        }

        if (getChildren) {
            if (!parent) {
                $('#map-'+this.options.modelName).html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
                if ($('#'+selectLink.options.inputId).val()) {
                    $('#modal-'+selectLink.options.inputId+' a.btn-danger').removeClass('hide');
                } else {
                    $('#modal-'+selectLink.options.inputId+' a.btn-danger').addClass('hide');
                }
            } else {
                $('#children-block-link-'+parent).html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
                $('#children-block-link-'+parent).slideDown(200);
            }

            $.ajax({
                type: 'POST',
                url: selectLink.options.url+'/links.php',
                data: {categories_id: categoriesId, parent: parent},
                dataType: 'json',
                success: function (jsonData) {
                    if (jsonData.length > 0) {
                        var html = '<ul class="list-unstyled select-links-list">';
                        $.each(jsonData, function (data, val) {
                            var childLink = '';
                            var childBlock = '';
                            if (val.child_exist == 1) {
                                childLink = '<a href="#" class="get-children text-muted" onclick="selectLink.getLinks('+categoriesId+', '+val.id+', $(this))" data-status="hide"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>';
                                childBlock = '<div id="children-block-link-'+val.id+'" class="children-block"></div>';
                            }

                            html += '<li>' +
                                childLink +
                                '<a href="#" class="select-link-choose" data-id="'+val.id+'" onclick="selectLink.choose($(this))">'+val.anchor+'</a>' +
                                childBlock +
                                '</li>';
                        });
                        html += '</ul>';

                        if (parent) {
                            $('#children-block-link-'+parent).html(html);
                        } else {
                            $('#map-'+selectLink.options.modelName).html(html);
                        }
                    }
                },
                error: function () {
                    console.log('Server error');
                }
            });
        }
    },

    choose : function (e) {
        if ($(e).hasClass('active')) return false;

        $('.select-link-choose').each(function () {
            $(this).removeClass('active');
        });

        $(e).addClass('active');
        var links_id = $(e).attr('data-id');

        $.ajax({
            type: 'POST',
            url: selectLink.options.url+'/choose-link.php',
            data: {links_id: links_id},
            dataType: 'json',
            success: function (jsonData) {
                $('#'+selectLink.options.inputId+'-choose-url').html(jsonData.url);
                $('#'+selectLink.options.inputId+'-choose-links_id').html(links_id);
            },
            error: function () {
                console.log('Server error');
            }
        });
    },

    confirmChoose : function () {
        var url = $('#'+selectLink.options.inputId+'-choose-url').html();
        var links_id = $('#'+selectLink.options.inputId+'-choose-links_id').html();

        if (!links_id || !url) {
            alert('Ссылка не выбрана');
            return false;
        }

        $('#modal-show-'+selectLink.options.inputId).html(url);
        $('#'+selectLink.options.inputId).val(links_id);

        $('#modal-'+selectLink.options.inputId).modal('hide');

        return true;
    },

    delete : function () {
        $('#modal-show-'+selectLink.options.inputId).html('Выбрать');
        $('#'+selectLink.options.inputId).val('');

        $('#'+selectLink.options.inputId+'-choose-url').html('Ссылка не выбрана');
        $('#'+selectLink.options.inputId+'-choose-links_id').html('');

        $('#modal-'+selectLink.options.inputId).modal('hide');

        return true;
    }
}

$(document).ready(function() {
    $.fn.selectLink = function (opts) {
        if (this.length > 0) {
            this.each(function () {
                selectLink.init(this, opts);
            });
        }
    }
});