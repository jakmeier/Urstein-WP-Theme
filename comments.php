<?php if ( post_password_required() ) return; ?>

<?php if ( have_comments() || comments_open() ) : ?>

	<div class="comments-container">
		
		<?php if ( have_comments() ) : ?>
	
			<div class="comments-inner">
			
				<a name="comments"></a>
				
				<h3 class="comments-title">
					
					<div class="inner">
				
						<?php echo count($wp_query->comments_by_type[comment]) . ' ';
						echo _n( 'Comment' , 'Comments' , count($wp_query->comments_by_type[comment]), 'urstein' ); ?>
					
					</div>
					
				</h3>
			
				<div class="comments">
			
					<ol class="commentlist">
					    <?php wp_list_comments( array( 'type' => 'comment', 'callback' => 'urstein_comment' ) ); ?>
					</ol>
					
					<?php if (!empty($comments_by_type['pings'])) : ?>
					
						<div class="pingbacks">
											
							<h3 class="pingbacks-title">
							
								<?php echo count($wp_query->comments_by_type[pings]) . ' ';
								echo _n( 'Pingback', 'Pingbacks', count($wp_query->comments_by_type[pings]), 'urstein' ); ?>
							
							</h3>
						
							<ol class="pingbacklist">
							    <?php wp_list_comments( array( 'type' => 'pings', 'callback' => 'urstein_comment' ) ); ?>
							</ol>
								
						</div> <!-- /pingbacks -->
					
					<?php endif; ?>
							
					<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
						
						<div class="comments-nav" role="navigation">
						
							<div class="fleft">
												
								<?php previous_comments_link( '&larr; ' . __( 'Older', 'urstein' ) ); ?>
							
							</div>
							
							<div class="fright">
							
								<?php next_comments_link( __( 'Newer', 'urstein' ) . ' &rarr;' ); ?>
							
							</div>
							
							<div class="clear"></div>
							
						</div> <!-- /comment-nav-below -->
						
					<?php endif; ?>
					
				</div> <!-- /comments -->
				
			</div> <!-- /comments-inner -->
			
		<?php endif; ?>

		<?php $comments_args = array(
			
			'title_reply' =>
				'<div class="inner">' . __('Leave a Reply','urstein') . '</div>',
			
			'comment_notes_before' => 
				'',
				
			'comment_notes_after' =>
				'',
		
			'comment_field' => 
				'<p class="comment-form-comment">
					<label for="comment">' . __('Comment','urstein') . '</label>
					<textarea id="comment" name="comment" cols="45" rows="6" required></textarea>
				</p>',
			
			'fields' => apply_filters( 'comment_form_default_fields', array(
			
				'author' =>
					'<p class="comment-form-author">
						<label for="author">' . __('Name','urstein') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> 
						<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" />
					</p>',
				
				'email' =>
					'<p class="comment-form-email">
						<label for="email">' . __('Email','urstein') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> 
						<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" />
					</p>',
				
				'url' =>
					'<p class="comment-form-url">
						<label for="url">' . __('Website','urstein') . '</label>
						<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
					</p>')
			),
		);
		
		comment_form($comments_args);
		
		?>
		
	</div> <!-- /comments-container -->
	
<?php endif; ?>