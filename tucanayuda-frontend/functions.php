<?php

function your_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, 
      get_template_directory_uri() . '/style.css'); 

    wp_enqueue_style( 'child-style', 
      get_stylesheet_directory_uri() . '/style.css', 
      array($parent_style), 
      wp_get_theme()->get('Version') 
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');



add_action( 'wp_ajax_search_college_student_group_filter', 'search_college_student_group_filter' );
add_action( 'wp_ajax_nopriv_search_college_student_group_filter', 'search_college_student_group_filter' );

function custom_volunteer_query($query) {
    if (isset($query['custom_query_filter']) && $query['custom_query_filter'] === 'volunteer_posts') {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        $query['paged'] = $paged; // Pass the 'paged' value
        $query['post_type'] = 'volunteer'; 
        $query['posts_per_page'] = 5; // Set the number of posts per page
    }
    return $query;
}

add_filter('elementor_pro/posts/query/volunteer_posts', 'custom_volunteer_query');

function search_college_student_group_filter(){
    $cats = explode (",", $_POST['cats']);
    $total_post_cats = [];
    $posts_ids = [];
    
    foreach($cats as $cat){
        $temp_ary = get_posts(array(
            'fields'         => 'ids',
            'post_type'      => 'college_student_group',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'categories',
                    'field'    => 'slug',
                    'terms'    => $cat,
                    ),
                 ),
            ));
            $total_post_cats = array_unique(array_merge($total_post_cats,$temp_ary));
    }
    
     if($locs[0] != '' && $cats[0] == ''){
         $posts_ids = $total_post_locs;
     }else if($locs[0] == '' && $cats[0] != ''){
         $posts_ids = $total_post_cats;
     }else if($locs[0] != '' && $cats[0] != ''){
         $posts_ids = array_intersect($total_post_cats,$total_post_locs);
     }
    
    if(!empty($posts_ids)){
    $posts = get_posts(array(
         'post_type'      => 'college_student_group',
         'post_status'    => 'publish',
         'post__in'       => $posts_ids,
         'posts_per_page' => -1,
        ));
    }
        $post_json = array();
        if($posts):
            foreach($posts as $post):
                $id = $post->ID;
				if(has_post_thumbnail($id)){
					$post_thumbnail = get_the_post_thumbnail_url($id);
				}else{
					$post_thumbnail = site_url().'/wp-content/uploads/2022/02/elite.png';
				}
                $post_json[] = array(
                    'id' => $post->ID,
                    'title' => get_the_title($id),
                    'occupation' => get_field('occupation',$id),
                    'description' => get_the_excerpt($id),
                    'image' => $post_thumbnail,
                    'link' => get_the_permalink($id)
                  );
            endforeach;
        endif;
        echo json_encode($post_json);
        exit();
}

add_action('wp_footer','wp_add_mathematics_major_script',9999);
function wp_add_mathematics_major_script(){ ?>
<script>
 jQuery(document).ready(function(){
    jQuery('.fa-square').click(function(){
        jQuery('.elementor-icon-list-item').removeClass('checkeditem')
    jQuery('.print-pdf').remove()
    jQuery(this).parents('li').addClass('checkeditem')
    if(jQuery(this).parents('li').find('.combSpan').length==0){
        jQuery(this).parents('li').append('<div class="combSpan"></div>')
        jQuery(this).parents('li').find('span').appendTo(jQuery(this).parents('li').find('.combSpan'))
    }
    
    jQuery(this).parents('li').append('<div class="print-pdf"><ul class="printorpdf"><li><a href="'+window.location.href.split("#")[0]+'?print=pdf" target="__blank">Ver PDF</a></li><li><a href="'+window.location.href.split("#")[0]+'?print=print" target="__blank">Imprimir contenido</a></li></ul></div>')
})
}); 
    </script>
<?php }