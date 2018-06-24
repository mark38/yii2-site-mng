var filter = {
    changePrice : function()
    {
        $('#link-sort-price > a.btn').html('<i class="fa fa-spinner fa-pulse"></i> Загрузка...');
        $('#link-sort-price').slideDown(function () {
            $('#link-sort-price a.btn-close').fadeIn(300);
        });

        var min = $('#price-min').val();
        var max = $('#price-max').val();
        var filter_url = $('#good-prices').attr('data-filter-url');
        var url = $('#good-prices').attr('data-url');
        var filter = $('#good-prices').attr('data-filter');

        if (filter) {
            filter = filter.replace(/price-\d+-\d+/, "");
            filter = filter.replace(/(^\/)|(\/$)/, "");
        }
        filter = !filter ? '' : filter+'/';
        filter += 'price-'+min+'-'+max;

        $.ajax({
            type: 'POST',
            url: filter_url,
            data: {
                url: url,
                filter: filter
            },
            dataType: 'json',
            success: function(jsonData) {
                $('#link-sort-price > a.btn').attr('href', jsonData.href).html('Показать &mdash; '+jsonData.amount);
            },
            error: function() {
                alert('Server error');
            }
        });
    },

    close : function () {
        $('#link-sort-price').slideUp(100);
        $('#link-sort-price a.btn-close').fadeOut();
    }
}

jQuery(document).ready(function($){
    $('.change-sort li a').each(function () {
        $(this).on('click', function () {
            var sort = $(this).attr('data-sort');
            var order = $(this).attr('data-order');

            $.ajax({
                type: 'POST',
                url: '/module/shop/change-sort',
                data: {'sort': sort, 'order': order},
                dataType: 'json',
                success: function (jsonData) {
                    location.reload();
                },
                error: function () {
                    alert('Server error');
                }
            });
        });
    });

    $('.change-template li a').each(function () {
        $(this).on('click', function () {
            var template = $(this).attr('data-template');
            $.ajax({
                type: 'POST',
                url: '/module/shop/change-template',
                data: {'template': template},
                dataType: 'json',
                success: function (jsonData) {
                    // location.reload();
                },
                error: function () {
                    alert('Server error');
                }
            });
        });
    });

    $('.filter-list > li').each(function () {
        var filterNode = $(this);
        $(filterNode).children('.filter-name').children('a').click(function() {
            if ($(this).children('i').hasClass('fa-angle-up')) {
                $(this).children('i').removeClass('fa-angle-up').addClass('fa-angle-down');
                $(filterNode).children('ul').slideUp();
            } else if ($(this).children('i').hasClass('fa-angle-down')) {
                $(this).children('i').removeClass('fa-angle-down').addClass('fa-angle-up');
                $(filterNode).children('ul').slideDown();
            }
        });
    });

    $('.filter-name').each(function () {
        var filterName = $(this);
        $(this).children('a').click(function() {
            if (filterName.hasClass('dropup')) {
                filterName.removeClass('dropup').addClass('dropdown');
                filterName.parent().children('ul').slideUp();
            } else if (filterName.hasClass('dropdown')) {
                filterName.removeClass('dropdown').addClass('dropup');
                filterName.parent().children('ul').removeClass('hidden').slideDown();
            }
        });
    });
});