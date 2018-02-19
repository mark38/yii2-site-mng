var viewSearchResult = false;

function modalFormActive(e, size)
{
    if (size == "modal-lg") {
        $('.modal-dialog').addClass(size);
    } else {
        $('.modal-dialog').removeClass("modal-lg");
    }

    $('#modal-form .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-pulse"></i></div>');
    $('#modal-form').modal('toggle');

    $.ajax({
        type: 'POST',
        url: e.attr('href'),
        data: false,
        dataType: 'json',
        success: function (jsonData) {
            $('#modal-form .modal-body').html(jsonData);
        },
        error: function () {
            console.log('Server Error. Please check back later.');
        }
    });
}

jQuery(document).ready(function () {
    var wow = new WOW({
        'boxClass': 'wow',
        'animateClass': 'animated',
        'offset': 0,
        'mobile': true,
        'live': true,
        'callback': function () {
            
        },
        'scrollContainer': null,
    });

    wow.init();

    if ($('.nav-geo').length > 0) {
        $('.nav-geo .dropdown-menu > li').each(function () {
            var item = $(this);
            $(this).children('a').on('click', function() {
                var id = $(item).children('a').attr('data-geobase-contact-id');
                var cityName = $(item).children('a').html();
                $('.nav-geo .dropdown-menu > li').removeClass('active');
                $(item).addClass('active');
                $.ajax({
                    type: 'POST',
                    url: '/module/geo_base/change-city',
                    data: {'geobase_contact_id' : id},
                    dataType: 'json',
                    success: function(jsonData) {
                        $('#active-city').html(cityName);
                        var workingHours = jsonData.contact.working_hours ? ' ('+jsonData.contact.working_hours+')' : '';
                        $('#geo-contact').html(jsonData.contact.phone);
                    },
                    error: function() {
                        alert('Server error');
                    }
                });
            });
        })
    }

    if ($('.promo-wrap').length > 0) {
        $('.promo-wrap').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $('.item-name').removeClass("wow fadeInDown animated").attr("style","visibility: hidden; animation-name: none;");
            $('.item-title').removeClass("wow fadeInUp animated").attr("style","visibility: hidden; animation-name: none;");
            $('.item-link').removeClass("wow fadeIn animated").attr("style","visibility: hidden; animation-name: none;");
            setTimeout(function () {
                $('#promo-slide-cnt-'+nextSlide).find('.item-name').addClass("wow fadeInDown animated").attr("style","visibility: visible; animation-name: fadeInDown;");
                $('#promo-slide-cnt-'+nextSlide).find('.item-title').addClass("wow fadeInUp animated").attr("style","visibility: visible; animation-name: fadeInUp;");
                $('#promo-slide-cnt-'+nextSlide).find('.item-link').addClass("wow fadeIn animated").attr("data-wow-delay", "1s").attr("style","visibility: visible; animation-name: fadeIn;");
            }, 500);
        });
    }

    $('.js-toggle-long-content').each(function () {
        var actionLink = $(this);
        $(actionLink).click(function () {
            if ($(actionLink).attr('data-action') == 'down') {
                $(actionLink).parent().children('.block').removeClass('short');
                $(actionLink).attr('data-action', 'up');
                $(actionLink).html('<i class="fa fa-caret-up" aria-hidden="true"></i> Скрыть')
            } else {
                $(actionLink).parent().children('.block').addClass('short');
                $(actionLink).attr('data-action', 'down');
                $(actionLink).html('<i class="fa fa-caret-down" aria-hidden="true"></i> Подробнее');
            }
        });
    });

    var searchFormView = false;
    $('#search-form-view').on('click', function () {
        if (!searchFormView) {
            searchFormView = true;
            $('.search-form form').css('display', 'block');
            $('#search-form input').focus();
            $('#search-form .dropdown-close').on('click', hideFormSearch);
            $(document).keyup(function(e) {
                if (e.keyCode == 27) {
                    hideFormSearch();
                    $('.search-result .inner').html('');
                    $('.search-result').slideUp(100);
                }
            });
        } else {
            hideFormSearch();
        }
    });

    function hideFormSearch() {
        searchFormView = false;
        viewSearchResult = false;
        $('li#search').trigger('mouseleave');
        $('.search-form form').css('display', 'none');
        $('#search').children('.content').html('');
    }

    $('#search-form input').keyup(function () {
        var search = $('#search-form input').val();
        var xhr;
        if (search.length > 2) {
            $('#search').children('.content').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></div>');

            if(xhr && xhr.readyState != 4){
                xhr.abort();
            }
            xhr = $.ajax({
                type: 'POST',
                url: '/search/shop',
                data: {'value' : search},
                dataType: 'json',
                success:function(jsonData) {
                    var html = jsonData.html;
                    $('#search').children('.content').html(jsonData.html);

                    viewSearchResult = true;
                    $('a#search-trigger').trigger('mouseenter');
                },
                error: function () {
                    console.log('Server error. Try later.');
                }
            });
        } else {
            $('#search').children('.content').html('');
            if (viewSearchResult == true) {
                console.log('test');
                viewSearchResult = false;
                $('li#search').trigger('mouseleave');
            }
        }
    });

    $('.search-result-close').click(function () {
        $('#search .container').html('');
        hideFormSearch();
    });
});
