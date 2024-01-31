<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TakieDela
 */

?>

<div class="post">
	<?php takiedela_post_thumbnail(); ?>
	<div class="post__info">
		<div class="post__text">
			<div class="post__date"><?php echo date( "Y.m.d, h:i", strtotime($post->post_date)); ?></div>
			<div class="post__categories">
				<?php $categories = get_the_category(get_the_ID());
				if( $categories ){
					$out = null;
					foreach( $categories as $category ) {
						$out .=  $category->name . ' • ';
					}
					echo trim($out, ' • ');
				}
				?>
			</div>
			<h3 class="post__title"><?php the_title(); ?></h3>
			<p class="post__des"><?php echo get_the_excerpt(); ?></p>
		</div>
		<div class="post__author">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/author.svg" alt="" class="post__author_icon">
			<span class="post__author_name">Н. Дмитриева, С. Строителев</span>
		</div>
	</div>
</div>
