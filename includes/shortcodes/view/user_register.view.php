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
            <a href="javascript:;"><?php esc_html_e('User Signup', 'ultimate-viral-quiz') ?></a>
          </h2>
        </div>
        <div class="dbuzz-form-container">
          <p class="lead"><?php esc_html_e('Signup with this form', 'ultimate-viral-quiz') ?></p>
          <div class="row">
              <form id="signupForm" name="signupForm" action="" class="dbuzz-form dbuzz-input-lg col-xs-12 col-sm-12 col-md-8 col-lg-8" method="post" accept-charset="utf-8" role="form" enctype="multipart/form-data" novalidate="novalidate">
                <div class="form-group">
                  <label for="nickname"><?php esc_html_e('Fullname', 'ultimate-viral-quiz') ?></label>
                  <div class="input-group">
                    <span class="input-group-addon"> <span class="ti-info-alt"></span> </span>
                    <input ng-model="user.fullname" type="text" name="nickname" maxlength="250" class="form-control input-lg" id="nickname" value="" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="username"><?php esc_html_e('Username', 'ultimate-viral-quiz') ?></label>
                  <div class="input-group">
                    <span class="input-group-addon"> <span class="ti-user"></span> </span>
                    <input ng-model="user.username" type="text" name="username" maxlength="50" class="form-control input-lg" id="username" value="" required>
                  </div>
                </div>
                <div class="form-group">
                    <label for="password"><?php esc_html_e('Password', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-key"></span> </span>
                      <input ng-model="user.password" type="password" name="password" maxlength="40" class="form-control input-lg" id="password" value="" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="repassword"><?php esc_html_e('Confirm Password', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-key"></span> </span>
                      <input ng-model="user.repassword" type="password" name="repassword" maxlength="40" class="form-control input-lg" id="repassword" value="" required>
                    </div>
                </div>
                <div class="form-group">
                  <label for="email"><?php esc_html_e('Email', 'ultimate-viral-quiz') ?></label>
                  <div class="input-group">
                    <span class="input-group-addon"> <span class="ti-email"></span> </span>
                    <input ng-model="user.email" type="email" name="email" maxlength="250" class="form-control input-lg" id="email" value="" required>
                  </div>
                </div>
                <button type="submit" id="btn-signup" class="dbuzz-ghost-btn blue-theme"><?php esc_html_e('Register', 'ultimate-viral-quiz') ?></button>
              </form>
        </div>
      </section>
    </div><!-- /.main -->      
</div>
