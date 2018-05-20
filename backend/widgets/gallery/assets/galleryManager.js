var galleryManager = {
    options : {
        'group': '',
        'url': '',
        'route': '',
        'gallery_types_id': '',
        'gallery_groups_id': '',
        'gallery_images_id': '',
        'widget_id': ''
    },

    init : function(e, options) {
        this.options = options;
    },

    prepareUpload : function(e) {
        if (!this.options.gallery_groups_id) {
            alert(1);
            $.ajax({
                type: 'POST',
                url: this.options.url,
                data: {'action' : 'add_group', 'gallery_types_id' : this.options.gallery_types_id},
                dataType: 'json',
                success: function(jsonData) {
                    galleryManager.options.gallery_groups_id = jsonData.gallery_groups_id;
                    if (galleryManager.options.group == 1) {
                        $('#input-'+galleryManager.options.widget_id+' input[type="hidden"]').children('input').val(galleryManager.options.gallery_groups_id);
                    }
                    galleryManager.uploadGallery(e);
                },
                error: function() {
                    console.log('Error in function prepareUpload');
                }
            });
        } else {
            galleryManager.uploadGallery(e);
        }
    },

    uploadGallery : function(e) {
        var data = new FormData();

        for (var i=0; i<$(e).prop('files').length; i++) {
            data.append('group', this.options.group);
            data.append('route', this.options.route);
            data.append('action', 'upload_image');
            data.append('gallery_types_id', this.options.gallery_types_id);
            data.append('gallery_groups_id', this.options.gallery_groups_id);
            data.append('gallery_images_id', this.options.gallery_images_id);
            data.append('image', $(e).prop('files')[i]);

            $.ajax({
                type: 'POST',
                url: this.options.url,
                processData: false,
                contentType: false,
                data: data,
                dataType: 'json',
                success: function(jsonData) {
                    if (jsonData.success == false) {
                        console.log(jsonData.message);
                    } else {
                        console.log(galleryManager.options.group);
                        if (galleryManager.options.group == 0) {
                            galleryManager.options.gallery_images_id = jsonData.gallery_images_id;
                            $('#input-'+galleryManager.options.widget_id+' input[type="hidden"]').val(galleryManager.options.gallery_images_id);
                        }
                    }

                    if (i == $(e).prop('files').length) {
                        galleryManager.getGallery();
                    }
                },
                error: function() {
                    console.log('Error in function uploadGallery');
                }
            });
        }
    },

    getGallery : function() {
        var data = new FormData();

        data.append('group', this.options.group);
        data.append('route', this.options.route);
        data.append('action', 'get_gallery');
        data.append('gallery_types_id', this.options.gallery_types_id);
        data.append('gallery_groups_id', this.options.gallery_groups_id);
        data.append('gallery_images_id', this.options.gallery_images_id);

        $.ajax({
            type: 'POST',
            url: galleryManager.options.url,
            processData: false,
            contentType: false,
            data: data,
            dataType: 'json',
            success: function(jsonData) {
                $('.gallery-manager').html(jsonData.gallery);
            },
            error: function() {
                console.log('Error in function getGallery');
            }
        });
    },
    
    deleteImage : function (gallery_images_id) {
        var data = new FormData();

        data.append('group', this.options.group);
        data.append('route', this.options.route);
        data.append('action', 'delete_image');
        data.append('gallery_types_id', this.options.gallery_types_id);
        data.append('gallery_groups_id', this.options.gallery_groups_id);
        data.append('gallery_images_id', gallery_images_id);

        $.ajax({
            type: 'POST',
            url: galleryManager.options.url,
            processData: false,
            contentType: false,
            data: data,
            dataType: 'json',
            success: function(jsonData) {
                if (galleryManager.options.group == 0) {
                    $('#input-'+galleryManager.options.widget_id+' input[type="hidden"]').val('');
                }
            },
            error: function() {
                console.log('Error in function deleteImage');
            }
        })
    },

    sortableImage : function (wId) {
        var data = new FormData();

        var images = new Object();
        $('#content-'+wId+' > ul > li').each(function (seq, val) {
            if (imagesId = $(this).children('div').attr('data-image-id')) {
                images[seq] = imagesId;
            }
        });

        if (images) {
            data.append('action', 'sortable_images');
            data.append('images', JSON.stringify(images));
            data.append('gallery_types_id', this.options.gallery_types_id);
            data.append('gallery_groups_id', this.options.gallery_groups_id);

            $.ajax({
                type: 'POST',
                url: galleryManager.options.url,
                processData: false,
                contentType: false,
                data: data,
                dataType: 'json',
                success: function(jsonData) {
                },
                error: function() {
                    console.log('Error in function sortableImage');
                }
            });
        }
    }
};

$( document ).ready(function() {
    $.fn.galleryManager = function (opts) {
        if (this.length > 0) {
            this.each(function () {
                galleryManager.init(this, opts);
            });
        }
    };
});