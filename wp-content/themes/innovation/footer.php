<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Innovation
 */

?>

	<footer id="colophon" class="site-footer container">
		<div class="site-info">
			
			<span class="sep">  </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Prism by %2$s.', 'prism' ), 'prism', '<a href="http://graana.com">Graana Innovation Lab</a>' );
				?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
