<?php
if ( is_user_logged_in() ) {
    echo '<div class="bbuvq-redirect" data-href="'.home_url().'"></div>';
}
?>
<div class="uvq-user-container" ng-app="dbuzzapp" ng-controller="buzzController">
    <div ng-controller="userController">
        <section>
          	  <div class="head-widget">
          	    <h2 class="title-widget">
          	      <a href="javascript:;"><?php esc_html_e('Forgot password?', 'ultimate-viral-quiz') ?></a>
          	    </h2>
          	  </div>
          	  <?php if(isset($_GET['secret_key']) && $_GET['secret_key']) : ?>
          	  	<h4 class="text-danger"><?php do_action( '_d_get_newpassword_action', $_GET['secret_key'] ); ?></h4>
          	  <?php else : ?>
          	  <div class="dbuzz-form-container">
          	    <p class="lead"><?php esc_html_e('Please enter your email address and check email', 'ultimate-viral-quiz') ?></p>
          	    <div class="row">
          	        <form id="lostPassword" name="lostPassword" class="dbuzz-form dbuzz-input-lg col-xs-12 col-sm-12 col-md-8 col-lg-8" method="post" accept-charset="utf-8" role="form" enctype="multipart/form-data" novalidate="novalidate">
          	          <div class="form-group">
          	            <label for="email"><?php esc_html_e('Your email address', 'ultimate-viral-quiz') ?></label>
          	            <div class="input-group">
          	              <span class="input-group-addon"> <span class="ti-email"></span> </span>
          	              <input type="text" ng-model="user.email" name="email" maxlength="200" class="form-control input-lg" id="email" value="">
          	            </div>
          	          </div>
          	          <button type="submit" id="btn-lostPassword" class="dbuzz-ghost-btn blue-theme"><?php esc_html_e('Send instruction', 'ultimate-viral-quiz') ?></button>
          	        </form>
          	      </div>
          	  </div>
          	<?php endif; ?>
          	</section>
    </div><!-- /.main -->      
</div>
