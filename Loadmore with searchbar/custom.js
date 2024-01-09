jQuery(document).ready(function () {
    // loadmore with page loaded
    var page = 1;
    page_load(page);

    jQuery(document).on('click', '.LoadMoreClick', function () {
        page++;
        page_load(page, '', '');
    });


    jQuery('#search').on('keyup change', function () {
        page = 1; // Reset page to 1 when searching
        var search = jQuery(this).val();
        var check_search = jQuery(this).data('search');
        page_load(page, search, check_search);
    })
})

function page_load(page, search, check_search) {

    var $lodmore = jQuery('.LoadMoreClick');

    jQuery.ajax({
        type: 'post',
        url: my_ajax_object.ajax_url,
        data: {
            action: 'LoadPostData',
            page: page,
            search: search
        },

        beforeSend: function () {
            $lodmore.prop('disabled', true).html($lodmore.data('loading-text'));
        },

        success: function (response) {

            let data = jQuery.parseJSON(response);

            if (data.html) {

                if (check_search === undefined || check_search === null || check_search === '' || check_search != 'course_search') {
                    // Variable is empty
                    jQuery('#mypost').append(data.html);

                } else {

                    jQuery('#mypost').html(data.html);
                    // Variable is not empty
                }

                if (data.max_page == page || data.max_page == 0) {

                    $lodmore.hide();

                } else {

                    $lodmore.show();
                }

                $lodmore.text('Load More').prop('disabled', false);
            }
        }
    });
}