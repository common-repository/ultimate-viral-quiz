<?php
if ( !is_user_logged_in() ) {
    echo '<div class="bbuvq-redirect" data-href="'.home_url().'"></div>';
}
?>
<div class="uvq-user-container" ng-app="dbuzzapp" ng-controller="buzzController">
    <div ng-controller="userController">
      <div class="head-widget">
        <h2 class="title-widget">
          <a href="javascript:;"><?php esc_html_e('Change password', 'ultimate-viral-quiz') ?></a>
        </h2>
        <div class="nav-carousel"></div>
      </div>
      <div class="row">
          <form id="changePassword" name="changePassword" action="" class="dbuzz-form dbuzz-input-lg col-xs-12 col-sm-12 col-md-8 col-lg-8" method="post" accept-charset="utf-8" role="form" enctype="multipart/form-data" novalidate="novalidate">
            <div class="form-group">
                <label for="oldpassword"><?php esc_html_e('Old Password', 'ultimate-viral-quiz') ?></label>
                <div class="input-group">
                  <span class="input-group-addon"> <span class="ti-key"></span> </span>
                  <input type="password" ng-model="user.oldpassword" name="oldpassword" maxlength="40" class="form-control input-lg" id="oldpassword" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="newpassword"><?php esc_html_e('New Password', 'ultimate-viral-quiz') ?></label>
                <div class="input-group">
                  <span class="input-group-addon"> <span class="ti-key"></span> </span>
                  <input type="password" ng-model="user.password" name="password" maxlength="40" class="form-control input-lg" id="password" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="repassword"><?php esc_html_e('Confirm Password', 'ultimate-viral-quiz') ?></label>
                <div class="input-group">
                  <span class="input-group-addon"> <span class="ti-key"></span> </span>
                  <input type="password" ng-model="user.repassword" name="repassword" maxlength="40" class="form-control input-lg" id="repassword" value="">
                </div>
            </div>
            <ul class="list-unstyled list-inline">
              <li><button type="submit" id="btn-changepass" class="dbuzz-ghost-btn blue-theme"><?php esc_html_e('Update', 'ultimate-viral-quiz') ?></button></li>
            </ul>
          </form>
      </div>
</div>
