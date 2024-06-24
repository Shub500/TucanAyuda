<?php 
/**
 * Template Name:College Student Group
 *
 */
get_header();
the_content();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
session_start();

if(isset($_SESSION['categories'])){
    if(isset($_POST['categories']) || empty($_POST['categories'])){
        $_SESSION['categories'] = !empty($_POST['categories']) ? $_POST['categories'] : array();
        $cats = $_SESSION['categories'];
    }else{
        $cats = $_SESSION['categories'];
    }
}else{
    if(isset($_POST['categories']) || empty($_POST['categories'])){
        $cats = $_SESSION['categories'] = !empty($_POST['categories']) ? $_POST['categories'] : array();
    }else{
        $cats = array();
    }
}
?>
<div class="container">
    <div class="category_filter">
        <?php
         $categories = get_terms( array(
            'taxonomy' => 'college_group_category',
            'hide_empty' => true
            ));
        echo '<form id="catfilter" action="" method="POST">';
                    if ( !empty($categories) ) :
                        foreach( $categories as $category ):
                            $tax_langtl++;
                                    $output.= '<div class="single_taxonomy"><input type="checkbox" name="categories[]" class="filter_chks" value="'. esc_attr( $category->term_id) .'" id="categ_'. esc_attr( $category->term_id ) .'" '.(in_array($category->term_id,$cats) ? "checked" : "").'>
                                    <label class="sidebar_chk_lbl" for="categ_'. esc_attr( $category->term_id ) .'">'. esc_html( $category->name ) .'</label></div>';
                            endforeach;
                        echo $output;
                    endif;
                    echo '<input type="submit" value="Filter" name="filter">';
					echo '<input type="button" value="Reset" onclick="reset_form()">';
                    echo '</form>';
        ?></div>
    <div class="college_student_ggroups">
        <?php  
        if(!empty($cats)){
            $tax_query = array(
            array(
                'taxonomy' => 'college_group_category',
                'field'    => 'term_ids',
                'terms'    => $cats,
                ),
            );

        }else{
            $tax_query = array();
        }
        

        $args = array(
            'post_type'=>'college_group',
            'posts_per_page' => 5,
            'paged' => $paged,
            'tax_query' => $tax_query,
        );
        
        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) : $loop->the_post();
            echo '<div class="single_college_group">';
                     the_content();
            echo '</div>';
            endwhile;
        
            $total_pages = $loop->max_num_pages;
        
            if ($total_pages > 1){
        
                $current_page = max(1, get_query_var('paged'));

        
                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => '/page/%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text'    => __('« prev'),
                    'next_text'    => __('next »'),
                ));
            }    
        }
        ?>
		<p class="csg-disclaimer">
		No estamos afiliados ni asociados oficialmente con estos grupos.<br>
		Si usted es un organizador de un grupo enumerado aquí y desea eliminarlo de la lista, notifíquenoslo.
	</p>
    </div>
	
	
</div>
<?php
get_footer();
?>
<script>
	function reset_form(){
		jQuery('[name="categories[]"]').each(function() {
			this.checked = false;
		});
		jQuery('#catfilter').submit();
	}

</script>