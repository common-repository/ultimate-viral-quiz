<?php
if ( is_user_logged_in() )
{
    $url = home_url();
    if(isset($_GET['url_callback']) && !empty($_GET['url_callback'])) {
        $url = urldecode($_GET['url_callback']);
    }
    echo '<div class="bbuvq-redirect" data-href="'.$url.'"></div>';
    return;
}
?>
<div class="uvq-user-container" ng-app="dbuzzapp" ng-controller="buzzController">
    <div ng-controller="userController">
        <section>
            <div class="head-widget">
              <h2 class="title-widget">
                <a href="javascript:;"><?php esc_html_e('User login', 'ultimate-viral-quiz') ?></a>
              </h2>
            </div>
            <div class="dbuzz-form-container">
              <p class="lead">
                  <?php esc_html_e('Login with email or username', 'ultimate-viral-quiz') ?>
                  <?php echo apply_filters( 'uvq_demo_text', ''); ?>
              </p>
              <div class="row">
                  <form id="loginForm" name="loginForm" action="" class="dbuzz-form dbuzz-input-lg col-xs-12 col-sm-12 col-md-8 col-lg-8" method="post" accept-charset="utf-8" role="form" enctype="multipart/form-data" novalidate="novalidate">
                    <div class="form-group">
                      <label for="username"><?php esc_html_e('Username/Email', 'ultimate-viral-quiz') ?></label>
                      <div class="input-group">
                        <span class="input-group-addon"> <span class="ti-user"></span> </span>
                        <input type="text" ng-model="user.username" name="username" maxlength="100" class="form-control input-lg" id="username" value="">
                      </div>
                      
                    </div>
                    <div class="form-group">
                        <label for="password"><?php esc_html_e('Password', 'ultimate-viral-quiz') ?></label>
                        <div class="input-group">
                          <span class="input-group-addon"> <span class="ti-key"></span> </span>
                          <input type="password" ng-model="user.password" name="password" maxlength="40" class="form-control input-lg" id="password" value="">
                        </div>
                    </div>
                    <ul class="list-unstyled list-inline">
                      <li><button type="submit" id="btn-login" class="dbuzz-ghost-btn blue-theme"><?php esc_html_e('Login', 'ultimate-viral-quiz') ?></button></li>
                      <li><a href="<?php esc_html_e('') ?>"><?php esc_html_e('Forgot password?', 'ultimate-viral-quiz') ?></a></li>
                    </ul>
                  </form>
                </div>
            </div>
        </section>
    </div><!-- /.main -->      
</div>
