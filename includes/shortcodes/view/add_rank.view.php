<div class="uvq-editor-container" ng-app="dbuzzapp" ng-controller="buzzController">
<div class="editor-container" ng-controller="rank">

    <main class="main col-xs-12 col-md-8" role="main">
        <section class="create-content-buzz">
          <div class="head-widget">
            <h2 class="title-widget">
                <a href="javascript:;"><?php esc_html_e("Rank's content", "uvq") ?></a>
            </h2>
          </div>
          <div class="dbuzz-form-container">
            <ul class="list-unstyled list-inline clearfix">
              <li class="col-xs-4 col-sm-3">
                <div class="thumbnail-buzz upload-media">
                  <div ng-click="popUploadThumbnail()" class="thumbnail-detail upload-thumbnail" style="background-image: url({{ thumbImage.cropped_url }})">
                    <span class="upload-text {{ thumbImage ? 'upload-edit-text' : '' }}">{{ thumbImage ? '<?php esc_html_e("Click to edit Image", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to add Photo", 'ultimate-viral-quiz') ?>' }}</span>
                    <span ng-show="thumbImage" ng-click="removeImageThumbnail()" class="upload-remove ti-close"></span>
                  </div>
                </div>
              </li>
              <li class="col-xs-8 col-sm-9">
                  <div class="form-group">
                    <input ng-model="gameTitle" type="text" name="username" maxlength="500" class="form-control" id="username" value="" placeholder="<?php esc_attr_e("Enter Title", 'ultimate-viral-quiz') ?>">
                  </div>
                  <div class="form-group">
                      <textarea ng-model="gameDesc" name="description" class="form-control" id="description" value="" rows="3" placeholder="<?php esc_attr_e("Enter Description", 'ultimate-viral-quiz') ?>"></textarea>
                  </div>
              </li>
            </ul>
            <br/>
            <fieldset class="scheduler-border">
              <legend class="scheduler-border"><?php esc_html_e("Items management", 'ultimate-viral-quiz') ?></legend>
              <div class="editor-questions col-xs-12">
                  <ul class="list-unstyled clearfix">
                    <li class="question-item col-xs-12" ng-repeat="item in items">
                      <hr ng-show="$index!=0">
                      <div class="editor-item-title editor-input form-group">
                        <input ng-model="item.title" type="text" name="item-title" maxlength="500" class="form-control input-lg" id="item-title" value="" rows="3" placeholder="<?php esc_attr_e("Title", 'ultimate-viral-quiz') ?>" />
                      </div>
                      <div class="question-media upload-media">
                        <div ng-click="popUploadItem($index)" class="list-item-thumbnail upload-thumbnail" style="background-image: url({{ item.image }})">
                          <span ng-show="items.length > minItem" ng-click="removeItem($index)" class="remove ti-trash"></span>
                          <span class="upload-text {{ item.image ? 'upload-edit-text' : '' }}">{{ item.image ? item.data.dataType == currentProcessVideo ? '<?php esc_html_e("Click to edit Video", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to edit Image", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to add Photo/Video", 'ultimate-viral-quiz') ?>' }}</span>
                          <span ng-click="removeImageItem($index); $event.stopPropagation()" ng-show="item.image" class="upload-remove ti-close"></span>
                          <div class="form-group">
                            <textarea ng-click="$event.stopPropagation()" ng-model="item.caption" maxlength="500" class="question-caption form-control" value="" placeholder="<?php esc_attr_e("Image's caption", 'ultimate-viral-quiz') ?>"></textarea>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <hr>
                  <div class="add-button col-sm-3">
                     <a ng-show="maxItems > items.length" ng-click="addItem()" class="col-xs-12 dbuzz-ghost-btn blue-theme text-center" href="javascript:;"><?php esc_html_e("Add item", 'ultimate-viral-quiz') ?></a>
                  </div>
              </div>
            </fieldset>
          </div>
          <?php include_once 'editor_form_upload.view.php'; ?>
        </section>
      </main><!-- /.main -->

<?php include_once 'editor_sidebar.view.php'; ?>

</div>

</div>
