<?php 
 $categories = get_terms( array(
    'taxonomy' => 'college_group_category',
    'hide_empty' => true
    ));
echo '<form action="" method="GET">';
            if ( !empty($categories) ) :
                foreach( $categories as $category ):
                    $tax_langtl++;
                            $output.= '<div class="single_taxonomy"><input type="checkbox" name="categories" class="filter_chks" value="'. esc_attr( $category->slug ) .'" id="categ_'. esc_attr( $category->term_id ) .'">
                            <label class="sidebar_chk_lbl" for="categ_'. esc_attr( $category->term_id ) .'">'. esc_html( $category->name ) .'</label></div>';
                    endforeach;
                echo $output;
            endif;
            echo '<input type="submit" value="Filter" name="filter">';
            echo '</form>';