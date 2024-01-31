<?php get_header();?>
    <main class="main">
        <div class="container">
            <h2 class="title main-title">Статьи</h2>
            <div class="filter">
                <div class="filter-top">
                    <div class="filter-item filter-date">
                        <label for="date" class="filter-item__label">Дата публикации</label>
                        <input type="date" class="filter-item__input" id="date">
                    </div>
                    <div class="filter-item filter-select">
                        <label for="cat" class="filter-item__label">Категория</label>
                        <input type="date" class="filter-item__input" id="cat">
                    </div>
                </div>
            </div>
            <?php 
                $response = wp_remote_get( 'http://wp.test/wp-json/asad/v1/posts?per_page=3&page=1' );
                if( 200 === wp_remote_retrieve_response_code( $response ) ) :
                $posts = json_decode( wp_remote_retrieve_body( $response ), true );
                $total = $posts['total'];
            ?>
            <div class="posts">
                <?php foreach( $posts['data'] as $post ) : ?>
                    <div class="post">
                        <img src="<?php echo $post['featured_img']; ?>" alt="" class="post__image">
                        <div class="post__info">
                            <div class="post__date"><?php echo $post['date']; ?></div>
                            <div class="post__categories"><?php echo $post['categories']; ?></div>
                            <div class="post__text">
                                <h3 class="post__title"><?php echo $post['title']; ?></h3>
                                <p class="post__des"><?php echo $post['excerpt']; ?></p>
                            </div>
                            <div class="post__author">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/author.svg" alt="" class="post__author_icon">
                                <span class="post__author_name">Н. Дмитриева, С. Строителев</span>
                            </div>
                        </div>
                    </div>
                <?php
                    endforeach;
                ?>
            </div>
            <?php
                endif; 
            ?>
            <button class="loadmore" id="loadmore">Загрузить еще</button>
        </div>
    </main>
<?php get_footer();?>