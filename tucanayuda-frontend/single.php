<?php


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );

			the_post_navigation( array(
				'prev_text' => '<span class="nav-label">' . esc_html__( 'Previous Post', 'bari' ) . '</span><span class="nav-link">%title</span>',
				'next_text' => '<span class="nav-label">' . esc_html__( 'Next Post', 'bari' ) . '</span><span class="nav-link">%title</span>',
			) );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

        <a class="back-list" href="https://tucanayuda.com/preguntas-destacadas/">Volver a la lista</a>

		</main><!-- #main -->

		<?php get_template_part( 'template-parts/more-posts' ); ?>
	</div><!-- #primary -->

<?php
get_footer();
