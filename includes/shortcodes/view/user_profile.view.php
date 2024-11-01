<?php
if ( !is_user_logged_in() ) {
    echo '<div class="bbuvq-redirect" data-href="'.home_url().'"></div>';
}
?>
<div class="uvq-user-container" ng-app="dbuzzapp" ng-controller="buzzController">
    <div ng-controller="userController">
        <section>
          <div class="head-widget">
            <h2 class="title-widget">
              <a href="javascript:;"><?php esc_html_e('My profile', 'ultimate-viral-quiz') ?></a>
            </h2>
            <div class="nav-carousel"></div>
          </div>
          <div class="dbuzz-form-container row">
                <form id="updateProfile" name="updateProfile" action="" class="dbuzz-form dbuzz-input-lg col-xs-12 col-sm-12 col-md-8 col-lg-8" method="post" accept-charset="utf-8" role="form" enctype="multipart/form-data" novalidate="novalidate">
                  <p class="lead"><?php esc_html_e('General', 'ultimate-viral-quiz') ?></p>
                  <div class="form-group">
                    <label for="avatar"><?php esc_html_e('Avatar', 'ultimate-viral-quiz') ?> <small><i>(<?php esc_html_e('Click on Avatar to Edit', 'ultimate-viral-quiz') ?>)</i></small></label>
                    <div class="input-group">
                      <img ng-click="popUploadAvatar()" src="{{user.avatar.image}}" alt="" class="img-thumbnail avatar">
                    </div>
                  </div>
                  <!-- <ul class="list-inline list-unstyled">
                    <li>
                      <img ng-click="popUploadAvatar()" src="{{user.avatar}}" alt="" class="img-thumbnail avatar">
                      <div ng-show="showUploading" class="load-more col-xs-12 clearfix">
                        <div class="loader">
                          <div class="circle">&nbsp;</div>
                          <div class="circle">&nbsp;</div>
                          <div class="circle">&nbsp;</div>
                          <div class="circle">&nbsp;</div>
                        </div>
                        <br>
                      </div>
                    </li>
                    <li>
                      <div class="form-group has-warning">
                        <input type="file" name="image" id="avatar" ng-file-select accept="image/*" 
                                   ng-file-change="uploadImage($files)">
                        <p class="help-block tips" style="display:block"><?php esc_html_e('JPG, GIF or PNG, Max size: 2MB', 'ultimate-viral-quiz') ?></p>
                      </div>
                    </li>
                  </ul> -->
                  <div class="form-group">
                    <label for="fullname"><?php esc_html_e('Fullname', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-info-alt"></span> </span>
                      <input ng-model="user.fullname" type="text" name="fullname" maxlength="50" class="form-control input-lg" id="fullname" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="username"><?php esc_html_e('Username', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-user"></span> </span>
                      <input <?php if(!$able_edit_username) esc_html_e('disabled') ?> ng-model="user.username" type="text" name="username" maxlength="60" class="form-control input-lg" id="username" value="" current-username="<?php echo ($user->user_login) ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email"><?php esc_html_e('Email', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-email"></span> </span>
                      <input <?php if(!$able_edit_email) esc_html_e('disabled') ?> ng-model="user.email" type="text" name="email" maxlength="100" class="form-control input-lg" id="email" value="" current-email="<?php echo ($user->user_email) ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="aboutme"><?php esc_html_e('About me', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-info-alt"></span> </span>
                      <textarea name="aboutme" ng-model="user.aboutme" class="form-control input-lg" id="aboutme" maxlength="500"></textarea>
                    </div>
                  </div>
                  <hr>
                  <p class="lead"><?php esc_html_e('Social', 'ultimate-viral-quiz') ?></p>
                  <div class="form-group">
                    <label for="website"><?php esc_html_e('Website', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-direction"></span> </span>
                      <input type="text" ng-model="user.website" name="website" maxlength="200" class="form-control input-lg" id="website" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="facebook"><?php esc_html_e('Facebook', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-facebook"></span> </span>
                      <input type="text" ng-model="user.facebook" name="facebook" maxlength="200" class="form-control input-lg" id="facebook" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="google"><?php esc_html_e('Google+', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-google"></span> </span>
                      <input type="text" ng-model="user.google" name="google" maxlength="200" class="form-control input-lg" id="google" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="twitter"><?php esc_html_e('Twitter', 'ultimate-viral-quiz') ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"> <span class="ti-twitter-alt"></span> </span>
                      <input type="text" ng-model="user.twitter" name="twitter" maxlength="200" class="form-control input-lg" id="twitter" value="">
                    </div>
                  </div>
                  <button id="btn-update-profile" type="submit" class="dbuzz-ghost-btn blue-theme"><?php esc_html_e('Update', 'ultimate-viral-quiz') ?></button>
                </form>
          </div>
        </section>

        <?php include_once 'editor_form_upload.view.php'; ?>
    </div><!-- /.main -->      
</div>
