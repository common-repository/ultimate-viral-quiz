<?php $this->subform(); ?>
<table class="wp-list-table widefat" id="rc-shortcode-table">
	<thead>
		<tr>
			<th width="100px"><?php esc_html_e('ID', 'ultimate-viral-quiz') ?></th>
			<th><?php esc_html_e('Quiz Title', 'ultimate-viral-quiz') ?></th>
			<th><?php esc_html_e('Type', 'ultimate-viral-quiz') ?></th>
			<th><?php esc_html_e('Status', 'ultimate-viral-quiz') ?></th>
			<th><?php esc_html_e('Display', 'ultimate-viral-quiz') ?></th>
			<th width="200px" style="text-align: center"><?php esc_html_e('Action', 'ultimate-viral-quiz') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		
			$title = array(
				'personalityquiz' => esc_html__( 'Personality Quiz','ultimate-viral-quiz'),
				'triviaquiz' => esc_html__( 'Trivia Quiz','ultimate-viral-quiz'),
				'poll' => esc_html__( 'Poll','ultimate-viral-quiz'),
				'list' => esc_html__( 'List','ultimate-viral-quiz'),
				'rank' => esc_html__( 'Rank','ultimate-viral-quiz'),
			);
		
			foreach ($this->quizzes as $id => $quiz) {
				$label = get_post_meta($quiz->ID, 'uvq_type', true);
				if(isset($title[$label])) {
					$label = $title[$label];
				}
		?>
			<tr>
				<td><strong><?php echo esc_html($quiz->ID) ?></strong></td>
				<td><?php echo esc_html($quiz->post_title) ?></td>
				<td><?php echo esc_html($label) ?>
				</td>
				<td><?php echo esc_html($quiz->post_status) ?></td>
				<td>[uvq_quiz id="<?php echo esc_html($quiz->ID) ?>"]</td>
				<td style="text-align: right">
					<a class="button success bbhelp--top" bbhelp-label="<?php esc_html_e('Edit', 'ultimate-viral-quiz'); ?>" title="Edit" href="<?php echo admin_url('admin.php?page=uvq-add-'.get_post_meta($quiz->ID, 'uvq_type', true).'&idQuiz=' . $quiz->ID) ?>">
						<span class="dashicons dashicons-edit"></span>
					</a>
					<button data-base-url="<?php echo admin_url('admin.php?page=uvq-add-'.get_post_meta($quiz->ID, 'uvq_type', true).'&idQuiz='); ?>" class="bbsm-button-duplicate button primary bbhelp--top" bbhelp-label="<?php esc_html_e('Duplicate', 'ultimate-viral-quiz'); ?>" data-id="<?php echo esc_html($quiz->ID) ?>">
						<span class="dashicons dashicons-admin-page"></span></button>
					<button class="bbsm-button-delete button danger bbhelp--top" bbhelp-label="<?php esc_html_e('Delete', 'ultimate-viral-quiz'); ?>" data-id="<?php echo esc_html($quiz->ID) ?>">
						<span class="dashicons dashicons-trash"></span></button>
				</td>
			</tr>
		<?php
			}
		?>
	</tbody>
</table>

<?php $this->subform(); ?>
