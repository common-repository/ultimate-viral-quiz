<div class="row">
<?php
if ( $this->quizzes->have_posts() ) :
	while ( $this->quizzes->have_posts() ) : $this->quizzes->the_post(); ?>
	<div class="item clearfix col-sm-12 list-buzz">
		<div class="thumbnail detail-contain col-xs-3 col-md-3 col-sm-3 col-lg-3">
			
			<a title="<?php esc_attr_e(get_the_title()) ?>" href="<?php echo esc_url(get_permalink()) ?>">
				<?php
					$thumb = get_post_meta(get_the_ID(), 'uvq_quiz_thumb', true);
					if(isset($thumb->cropped_url)):
				?>
					<img src="<?php echo($thumb->cropped_url) ?>">
				<?php else: ?>
					<img src="<?php echo get_template_directory_uri() . '/assets/images/default_thumbnail.jpg' ?>">
				<?php endif; ?>
				<div class="detail-button ti-new-window"></div>
			</a>
		</div>
		<div class="item-content col-xs-9 col-lg-9 col-md-9 col-sm-9">
			<div class="item-head">
				<h3 title="<?php esc_attr_e(get_the_title()) ?>">
					<a href="<?php echo esc_url(get_permalink()) ?>"><?php the_title() ?></a>
				</h3>
				<div class="item-info">
					<div class="item-author">
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))) ?>" title="<?php printf(esc_attr__(" Posts by %s ", 'ultimate-viral-quiz'), the_author_meta('nickname')) ?> <?php the_author_meta('nickname') ?>" rel="author">
							<?php the_author_meta('nickname') ?>
						</a>
					</div>
					<span class="item-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
				</div>
				<div class="item-meta">
					<!-- <div class="item-ti-icon">
						<span class="ti-eye"></span>
						<?php //_e((int)get_post_meta( get_the_ID(), BESTBUG_UVQ_PREFIX.'view', true )) ?>
					</div> -->
					<div class="item-ti-icon">
						<span class="ti-comment"></span>
						<?php echo bb_esc_html(wp_count_comments(get_the_ID())->approved) ?>
					</div>
				</div>
			</div>
			<div class="item-desc hidden-xs">
				<?php the_excerpt() ?>
			</div>
		</div>
		</div>
	<?php endwhile;  ?>
        <div class="uvq-pager">
            <?php echo paginate_links( array(
            	'format' => '?paged=%#%',
            	'current' => max( 1, $paged ),
            	'total' => $this->quizzes->max_num_pages
            ) ); ?>
        </div>
        <?php

	wp_reset_postdata();
endif;
?>
</div>