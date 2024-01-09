<?php
add_action('wp_ajax_nopriv_LoadPostData', 'LoadPostData');
add_action('wp_ajax_LoadPostData', 'LoadPostData');

function LoadPostData()
{
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 3,
        'paged' => $paged,
        'order' => 'DESC',
        's' => $search,
    );

    $query = new WP_Query($args);

    $page_id = $query->max_num_pages; ?>

    <?php
    $response = [];

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $response['html'] .= '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="course-item bg-light">
                <div class="position-relative overflow-hidden">';
            $response['html'] .= '<img class="img-fluid" src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="">
                </div>
                <div class="text-center p-4 pb-0">';
            $response['html'] .= '<h5 class="mb-4">' . get_the_title() . '</h5>
                    <hr>
                    <h3 class="mb-0">Google Rating</h3>
                    <div class="mb-3">';
            $rating = get_field('rating', get_the_ID());

            if (!empty($rating)) {
                $full_stars = floor((float)$rating);

                $half_star = ((float)$rating - $full_stars);

                for ($i = 1; $i <= $full_stars; $i++) {
                    $response['html'] .= '<small class="fa fa-star"></small>';
                }
                if ($half_star) {
                    $response['html'] .= '<small class="fa fa-star-half"></small>';
                }
            }

            $response['html'] .= ' </div>
                </div>
            </div>
        </div>';
        endwhile;
    else :
        $response['html'] .= '<div class="error-msg"><p class="display_error">Data Not Found</p></div>';
    endif;
    $response['max_page'] = $page_id;
    echo json_encode($response);
    wp_reset_postdata(); // Restore global post data
    wp_die(); // Terminate the AJAX request properly
}
