<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TakieDela
 */

get_header();
?>

	<main class="main">
        <div class="container">
            <h2 class="title main-title">Статьи</h2>
            <form action="" method="POST" id="filter" class="filter">
                <div class="filter-top">
                    <div class="filter-item filter-date">
                        <label for="date" class="filter-item__label">Дата публикации</label>
						<input type="text" name="date" placeholder="--/--/----" class="filter-item__input" id="filter-date" maxlength="10"
						onkeyup="
							var v = this.value;
							if (v.match(/^\d{2}$/) !== null) {
								this.value = v + '/';
							} else if (v.match(/^\d{2}\/\d{2}$/) !== null) {
								this.value = v + '/';
						}"
						>
                    </div>
                </div>
				<div class="filter-bottom">
					<button class="filter__btn" id="filter-btn">Применить фильтры</button>
				</div>
            </form>
			<?php
				if ( have_posts() ) : ?>
					<div class="posts">
						<?php while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', get_post_type() );
						endwhile; ?>
					</div>
					<?php get_template_part( 'loadmore' ); ?>
					<?php the_posts_pagination();
				else :
					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
		</div>
	</main><!-- #main -->

<?php
get_footer();
