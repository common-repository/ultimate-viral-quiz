<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_WIDGETS' ) ) {
	/**
	 * BESTBUG_UVQ_WIDGETS Class
	 *
	 * @since	1.0
	 */
	 
class BESTBUG_UVQ_WIDGETS extends WP_Widget {
 
    function __construct() {
        $wget_options = array(
                'classname' => 'list-buzzes-widget',
                'description' => __('A list of your site’s Quizzes.', 'ultimate-viral-quiz')
        );
        parent::__construct('uvq_listbuzzes', __("List Quizzes", 'ultimate-viral-quiz'), $wget_options);
    }

    function form( $instance ) {
        $defaults = array( 
            'title' => __( 'New title', 'ultimate-viral-quiz' ),
            'is_show_index' => 'on',
            'display' => 'list',
            'col_percent' => '3',
            //'style' => 'list',
            'order' => 'DESC',
            'orderby' => '',
            'exclude_current_buzz' => 'on',
            'exclude' => '',
            'buzzcategories_ids' => '',
            'only' => '',
            'quantity' => 9,
            );

        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = esc_attr($instance['title']);
        $is_show_index = esc_attr($instance['is_show_index']);
        $display = esc_attr($instance['display']);
        //$style = esc_attr($instance['style']);
        $order = esc_attr($instance['order']);
        $orderby = esc_attr($instance['orderby']);
        $exclude_current_buzz = esc_attr($instance['exclude_current_buzz']);
        $exclude = esc_attr($instance['exclude']);
        $only = esc_attr($instance['only']);
        $quantity = esc_attr($instance['quantity']);
        $col_percent = esc_attr($instance['col_percent']);
		$buzzcategories_ids = esc_attr($instance['buzzcategories_ids']);
		
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'ultimate-viral-quiz' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['is_show_index'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('is_show_index')); ?>" name="<?php echo esc_attr($this->get_field_name('is_show_index')); ?>" /> 
            <label for="<?php echo esc_attr($this->get_field_id('is_show_index')); ?>"><?php esc_html_e( 'Display index number?', 'ultimate-viral-quiz' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['exclude_current_buzz'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('exclude_current_buzz')); ?>" name="<?php echo esc_attr($this->get_field_name('exclude_current_buzz')); ?>" /> 
            <label for="<?php echo esc_attr($this->get_field_id('exclude_current_buzz')); ?>"><?php esc_html_e( 'Exclude current quiz (for single detail page)?', 'ultimate-viral-quiz' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('display')); ?>"><?php esc_html_e('Display', 'ultimate-viral-quiz'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('display')); ?>" id="<?php echo esc_attr($this->get_field_id('display')); ?>" class="widefat">
            <?php
            $options = array(
                'list_only_title' => __('List (Only title)', 'ultimate-viral-quiz'),
                'list_only_image_title' => __('List (Image and title)', 'ultimate-viral-quiz'),
                'list_standard' => __('List (Standard)', 'ultimate-viral-quiz'),
                'grid-style' => __('Grid', 'ultimate-viral-quiz'),
                );
            foreach ($options as $key => $option) {
                echo '<option value="' . $key . '" id="' . $key . '"', $display == $key ? ' selected="selected"' : '', '>', $option, '</option>';
            }
            ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('col_percent')); ?>"><?php esc_html_e('Number of cols', 'ultimate-viral-quiz'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('col_percent')); ?>" id="<?php echo esc_attr($this->get_field_id('col_percent')); ?>" class="widefat">
            <?php
            $options = array(
                '6' => __('Two', 'ultimate-viral-quiz'),
                '4' => __('Three', 'ultimate-viral-quiz'),
                '3' => __('Four', 'ultimate-viral-quiz'),
                );
            foreach ($options as $key => $option) {
                echo '<option value="' . $key . '" id="' . $key . '"', $col_percent == $key ? ' selected="selected"' : '', '>', $option, '</option>';
            }
            ?>
            </select>
            <small><?php esc_html_e('Use if display is "Grid"', 'ultimate-viral-quiz') ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order', 'ultimate-viral-quiz'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('order')); ?>" id="<?php echo esc_attr($this->get_field_id('order')); ?>" class="widefat">
            <?php
            $options = array(
                'asc' => __('ASC', 'ultimate-viral-quiz'),
                'desc' => __('DESC', 'ultimate-viral-quiz'),
                );
            foreach ($options as $key => $option) {
                echo '<option value="' . $key . '" id="' . $key . '"', $order == $key ? ' selected="selected"' : '', '>', $option, '</option>';
            }
            ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by', 'ultimate-viral-quiz'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('orderby')); ?>" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" class="widefat">
            <?php
            $options = array(
                '' => __('Date', 'ultimate-viral-quiz'),
                'uvq_view' => __('View', 'ultimate-viral-quiz'),
                'rand' => __('Random', 'ultimate-viral-quiz'),
                );
            foreach ($options as $key => $option) {
                echo '<option value="' . $key . '" id="' . $key . '"', $orderby == $key ? ' selected="selected"' : '', '>', $option, '</option>';
            }
            ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'exclude' )); ?>"><?php esc_html_e( 'Exclude:', 'ultimate-viral-quiz' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'exclude' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'exclude' )); ?>" type="text" value="<?php echo esc_attr( $exclude ); ?>">
            <small><?php esc_html_e('Post IDs, separated by commas.', 'ultimate-viral-quiz') ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'buzzcategories_ids' )); ?>"><?php esc_html_e( 'Categories:', 'ultimate-viral-quiz' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'buzzcategories_ids' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'buzzcategories_ids' )); ?>" type="text" value="<?php echo esc_attr( $buzzcategories_ids ); ?>">
            <small><?php esc_html_e('Categories IDs, separated by commas.', 'ultimate-viral-quiz') ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'only' )); ?>"><?php esc_html_e( 'Only:', 'ultimate-viral-quiz' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'only' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'only' )); ?>" type="text" value="<?php echo esc_attr( $only ); ?>">
            <small><?php esc_html_e('Post IDs, separated by commas.', 'ultimate-viral-quiz') ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'quantity' )); ?>"><?php esc_html_e( 'Quantity:', 'ultimate-viral-quiz' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'quantity' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'quantity' )); ?>" type="text" value="<?php echo esc_attr( $quantity ); ?>">
            <small><?php esc_html_e('Enter max number of Quizzes.', 'ultimate-viral-quiz') ?></small>
        </p>
    <?php 
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $fields = array( 
            'title',
            'is_show_index',
            'display',
            //'style',
            'order',
            'orderby',
            'exclude_current_buzz',
            'exclude',
            'buzzcategories_ids',
            'only',
            'quantity',
            'col_percent',
            );
        // Fields
        foreach ($fields as $key => $field) {
            $instance[$field] = strip_tags($new_instance[$field]);
        }
        return $instance;
    }

    function widget( $args, $instance ) {
        wp_reset_query();

        $title = esc_attr($instance['title']);
        $is_show_index = esc_attr($instance['is_show_index']);
        $display = esc_attr($instance['display']);
        //$style = esc_attr($instance['style']);
        $order = esc_attr($instance['order']);
        $orderby = esc_attr($instance['orderby']);
        $exclude_current_buzz = esc_attr($instance['exclude_current_buzz']);
        $buzzcategories_ids = (esc_attr($instance['buzzcategories_ids']))?explode(",", esc_attr($instance['buzzcategories_ids'])):'';
        $exclude = (esc_attr($instance['exclude']))?explode(",", esc_attr($instance['exclude'])):'';
        $only = (esc_attr($instance['only']))?explode(",", esc_attr($instance['only'])):'';
        $quantity = esc_attr($instance['quantity']);
        $col_percent = esc_attr($instance['col_percent']);

        $buzz_args = array(
            'post_type' => BESTBUG_UVQ_POSTTYPE,
            'order' => $order,
            'posts_per_page' => $quantity,
            'post_status' => array('publish'),
			'meta_key'         => 'uvq_quiz_permission',
			'meta_value'       => '1',
        );

        if($orderby) :
            if($orderby == 'rand') :
                $buzz_args['orderby'] = $orderby;
            else :
                $buzz_args['orderby'] = 'meta_value_num';
                $buzz_args['meta_key'] = $orderby;
            endif;
        endif;
        if($exclude) {
            $buzz_args['post__not_in'] = $exclude;
        }
        if($only) {
            $buzz_args['post__in'] = $only;
        }
        if(is_single() && $exclude_current_buzz == 'on') :
            if(isset($buzz_args['post__not_in'])) :
                array_push($buzz_args['post__not_in'], $GLOBALS['post']->ID);
            else :
                $buzz_args['post__not_in'] = array($GLOBALS['post']->ID);
            endif;
        endif;

        if(!empty($buzzcategories_ids)) {
            $buzz_args['tax_query'] = array(
                                            array(
                                                'taxonomy' => 'uvq_category',
                                                'field'    => 'term_id',
                                                'terms'    => $buzzcategories_ids,
                                            ),
                                        );
        }
        $query = new WP_Query($buzz_args);

        if ($query->have_posts()) :
        ?>
        <?php echo bb_esc_html($args['before_widget']); ?>
        
        <?php if($title) echo bb_esc_html($args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title']); ?>

                <?php if($display == 'list_only_title') : ?>
                    <div class="widget_buzzes <?php echo ($is_show_index == 'on')?'show_index':'' ?>"><ul>
                <?php else: ?>
                    <div class="row list-buzz <?php esc_html_e($display) ?>">
                <?php endif; ?>
                <?php
                    $count = 0;
                    while ($query->have_posts()) : $query->the_post(); ?>
					<?php
					$thumb = get_post_meta(get_the_ID(), 'uvq_quiz_thumb', true);
					if(isset($thumb->cropped_url)):
						$thumb = $thumb->cropped_url;
					else:
						$thumb = get_template_directory_uri() . '/assets/images/default_thumbnail.jpg';
					endif;
					?>
                        <?php if($display == 'list_only_title') : ?>
                        <li class="col-lg-12">
                            <a class="caption" href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                                <?php if($is_show_index == 'on') : ?>
                                    <small class="index"><?php esc_html_e(++$count) ?></small>, 
                                <?php endif; ?> 
                                <?php the_title() ?>
                            </a> 
                        </li>
                        <?php elseif($display == 'list_only_image_title') : ?>
                        <div class="col-lg-12">
                            <div class="thumbnail">
                              <a class="thumbnail-link" href="<?php the_permalink() ?>">
                                <div class="detail-contain">
                                    <div class="image" style="background-image:url(<?php esc_html_e($thumb) ?>)">
                                        <?php if($is_show_index == 'on') : ?>
                                            <span class="index"><?php esc_html_e(++$count) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="detail-button ti-new-window"></div>
                                </div>
                                <div class="caption">
                                  <h4 title="<?php the_title() ?>"><?php the_title() ?> </h4>
                                </div>
                              </a> 
                            </div>
                          </div>
                        <?php elseif($display == 'list_standard') : ?>
                        <div class="item clearfix col-sm-12">
                            <div class="thumbnail detail-contain col-xs-3 col-md-3 col-sm-3 col-lg-3">
                              <a href="<?php the_permalink() ?>">
                                <img src="<?php esc_html_e($thumb) ?>">
                                <?php if($is_show_index == 'on') : ?>
                                    <span class="index"><?php esc_html_e(++$count) ?></span>
                                <?php endif; ?>
                                <div class="detail-button ti-new-window"></div>
                              </a> 
                            </div>
                            <div class="item-content col-xs-9 col-lg-9 col-md-9 col-sm-9">
                              <div class="item-head">
                                <h3 title="<?php the_title() ?>">
                                  <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                </h3>
                                <div class="item-meta">
                                  <!-- <div class="item-ti-icon">
                                    <span class="ti-eye"></span> <?php //_e(get_post_meta( get_the_ID(), 'uvq_view', true )) ?>
                                  </div> -->
                                  <div class="item-ti-icon">
                                    <span class="ti-comment"></span> <?php esc_html_e(wp_count_comments(get_the_ID())->approved) ?>
                                  </div>
                                  <!-- <div class="item-ti-icon">
                                    <span class="ti-heart"></span> 2
                                  </div> -->
                                </div>
                              </div>
                            </div>
                        </div>
                        <?php elseif($display == 'grid-style') : ?>
                        <div class="buzz-item col-xs-6 col-sm-<?php esc_html_e($col_percent) ?>">
                            <div class="thumbnail">
                              <a class="thumbnail-link" href="<?php the_permalink() ?>">
                                <div class="detail-contain">
                                    <img src="<?php esc_html_e($thumb) ?>">
                                    <?php if($is_show_index == 'on') : ?>
                                        <span class="index"><?php esc_html_e(++$count) ?></span>
                                    <?php endif; ?>
                                    <div class="detail-button ti-new-window"></div>
                                </div>
                                <div class="caption">
                                  <h4 title="<?php the_title() ?>"><?php the_title() ?></h4>
                                </div>
                              </a> 
                            </div>
                        </div>
                        <?php endif; ?>

                <?php endwhile; ?>

            <?php if($display == 'list_only_title') : ?>
                </ul></div>
            <?php else: ?>  
                </div>
            <?php endif; ?>
        <?php echo bb_esc_html($args['after_widget']); ?>

        <?php endif;
        
        wp_reset_query();

    } // END WIDGET FUNCTION

}

function uvq_listbuzzes_widget() {
    register_widget('BESTBUG_UVQ_WIDGETS');
}
add_action( 'widgets_init', 'uvq_listbuzzes_widget' );

}