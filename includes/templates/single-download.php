<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="post download">
                    <h1 class="entry-title">
                        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h1>

                    <div class="clear"></div>
                    
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail( 'original' ); ?>
                    </div>
                    
                    <div class="entry-download-link">
                        <?php if( SDW::check_download() ): ?>
                            <a class="download_active" href="<?php echo add_query_arg( array( 'getfile' => '' ), get_permalink() ); ?>">
                                <?php _e( 'Download', 'sdw' ); ?>
                            </a>
                        <?php else: ?>
                            <a class="download_disabled" href="<?php echo wp_login_url( admin_url() ); ?>">
                                <?php _e( 'Please subscribe to download', 'sdw' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php endwhile; endif; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
