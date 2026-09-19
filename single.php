<?php
/**
 * A single post becomes a thread page.
 *
 * @package Birds
 */

get_header();
?>
<main class="workspace" id="top">
	<div class="main-stack">
		<section class="window thread-window" aria-labelledby="thread-title">
			<div class="titlebar">
				<span class="control" aria-hidden="true"></span>
				<span class="window-title" id="thread-title"><?php esc_html_e( 'Thread', 'birds' ); ?></span>
				<span class="zoom" aria-hidden="true"></span>
			</div>
			<div class="window-body">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/post' ); ?>
				<?php endwhile; ?>
			</div>
		</section>
		<?php comments_template(); ?>
		<?php the_post_navigation( array( 'prev_text' => '<span class="meta-nav">' . esc_html__( '← Previous', 'birds' ) . '</span><span class="post-nav-title">%title</span>', 'next_text' => '<span class="meta-nav">' . esc_html__( 'Next →', 'birds' ) . '</span><span class="post-nav-title">%title</span>' ) ); ?>
	</div>
	<?php get_template_part( 'template-parts/context-rail' ); ?>
</main>
<?php
get_footer();
