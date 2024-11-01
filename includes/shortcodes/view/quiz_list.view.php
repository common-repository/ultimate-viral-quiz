<?php
$this->post = get_post($id);
if(isset($this->post) && !empty($this->post)):
	global $post;
	$post = $this->post;
	setup_postdata( $post );
	
$content = get_post_meta($id, 'uvq_quiz_content', true);
$thumb = get_post_meta($id, 'uvq_quiz_thumb', true);

$items = $content->items;
$footerText = $content->footerText;

$thumbnail = isset($thumb->cropped_url) ? $thumb->cropped_url : BESTBUG_UVQ_URL . '/assets/images/default_thumbnail.jpg';
  ?>
<article class="list-content buzz-content entry clearfix">
    <!-- <h1 class="entry-title"><?php echo get_the_title() ?></h1> -->
      <div class="item-info">
        <span class="vcard author">
          <span class="fn">
            <?php esc_html_e('Created by', 'ultimate-viral-quiz') ?>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))) ?>" title="<?php printf(esc_attr__(" %s", 'ultimate-viral-quiz'), get_the_author_meta('nickname')) ?>" rel="author"><?php the_author_meta('nickname') ?></a>
          </span>
        </span>
        <span class="item-date">
          <span class="post-date updated"><?php the_date() ?></span>
        </span>
      </div>
    <br>
    <div class="row">
        <div class="col-xs-12 item-content">
            <span class="text-description-content"><?php echo bb_esc_html(get_the_content()) ?></span>
        </div>
        <br>
        <div class="list-detail col-xs-12">
          <ul class="list-unstyled">
            <?php if($items)
                    foreach ($items as $key => $item) : ?>
            <li>
              <div class="list-head">
                <span class="list-number"><?php echo ($key + 1) ?></span>
                <h2 class="list-title"><?php echo bb_esc_html($item->title) ?></h2>
              </div>
              <div class="list-media">
                <?php if(isset($item->data->dataType) && $item->data->dataType=='video') : ?>
                      <div class="embed-responsive embed-responsive-16by9">
                          <iframe src="<?php esc_attr_e($item->data->youtubeEmbedString) ?>&;modestbranding=1&;showinfo=0&;autohide=1&;rel=0;"></iframe>
                      </div>
                <?php elseif(isset($item->data->dataType) && $item->data->dataType=='image') : ?>
                    <img class="full-width" src="<?php esc_attr_e($item->image)?>" alt="<?php esc_attr_e($item->title) ?>">
                 <?php endif; ?>
              </div>
              <div class="text">
                  <?php echo bb_esc_html($item->caption) ?>
              </div>
            </li>
          <?php endforeach; ?>
          </ul>
          <div class="footer-text">
            <?php echo bb_esc_html($footerText) ?>
          </div>
        </div>

    </div>

    <?php //get_template_part( _D_TEMPLATES_DIR . 'content', 'taxonomy') ?>

    <hr>

</article>

<?php
wp_reset_postdata();
endif;
?>