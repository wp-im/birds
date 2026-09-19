<?php
/**
 * Basic WordPress page template.
 *
 * @package Birds
 */

get_header();
?>
<main class="workspace" id="top">
	<div class="main-stack">
		<section class="window page-window" aria-labelledby="page-title">
			<div class="titlebar">
				<span class="control" aria-hidden="true"></span>
				<h1 class="window-title" id="page-title"><?php the_title(); ?></h1>
				<span class="zoom" aria-hidden="true"></span>
			</div>
			<div class="window-body">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<article class="page-content bodytext">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="post-pages">' . esc_html__( 'Pages:', 'birds' ), 'after' => '</div>' ) ); ?>
					</article>
				<?php endwhile; ?>
			</div>
		</section>
	</div>
	<?php get_template_part( 'template-parts/context-rail' ); ?>
</main>
<?php
get_footer();
