<div id="uploadPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myLargeModalLabel"> <span class="ti-upload"></span> <?php esc_html_e("Add Media", 'ultimate-viral-quiz') ?></h4>
          </div>
          <div class="modal-body body-popup">

            <div ng-show="showUploadImageOvelay" class="popup-process-overlay">
              <div class="loading-popup">
                <div class="circle">&nbsp;</div>
                <div class="circle">&nbsp;</div>
                <div class="circle">&nbsp;</div>
                <div class="circle">&nbsp;</div>
              </div>
            </div>

            <div ng-show="showUploadImageOvelayLover" class="popup-overlay"></div>

            <div ng-show="showMainUpload" class="main-content-popup">
              <ul class="list-unstyled list-inline clearfix list-option">
                <li ng-show="showFromFile" class="col-xs-4" ng-controller="uploadController">
                  <div class="upload-option">
                      <span class="ico-option ti-folder"></span>
                      <span class="text"><?php esc_html_e("From file", 'ultimate-viral-quiz') ?></span>
                      <input class="input-upload-form-file" name="image" ng-file-select name="image" type="file" accept="image/*" 
                                   ng-file-change="uploadImage($files)">
                  </div>
                </li>
                <li ng-show="showFromURL" class="col-xs-4">
                  <div class="upload-option" ng-click="formTypeImageUrl()">
                      <span class="ico-option ti-link"></span>
                      <span class="text"><?php esc_html_e("From url", 'ultimate-viral-quiz') ?></span>
                  </div>
                </li>
                <li ng-show="showFromYouTube" class="col-xs-4">
                  <div class="upload-option" ng-click="fromYoutubeVideo()">
                      <span class="ico-option ti-youtube"></span>
                      <span class="text"><?php esc_html_e("From Youtube", 'ultimate-viral-quiz') ?></span>
                  </div>
                </li>
                <li ng-show="showEditImage" class="col-xs-4">
                  <div ng-click="editImageAction()" style="background-image: url({{ editImage }})" class="upload-option edit-option">
                      <span class="ico-option ti-pencil"></span>
                      <span class="text">{{ editText }}</span>
                  </div>
                </li>
              </ul>
              <div class="dimensions">
                <span class="heading"><?php esc_html_e("Images dimensions", 'ultimate-viral-quiz') ?></span>
                <span class="desc-text"><?php esc_html_e("Please make sure you upload an image <b>at least</b> of these dimensions:", 'ultimate-viral-quiz') ?></span>
                <ul class="clearfix">
                  <?php $instructions = array(
                    'thumbnail' => array(
                      'width' => 392,
                      'height' => 294
                    ),
                    'results' => array(
                      'width' => 448,
                      'height' => 336
                    ),
                    'answers' => array(
                      'width' => 190,
                      'height' => 178
                    ),
                    'questions' => array(
                      'width' => 448,
                      'height' => 336
                    ),
                    'items' => array(
                      'width' => 448,
                      'height' => 336
                    ),
                    'wide_landscape' => array(
                      'width' => 448,
                      'height' => 235
                    ),
                    'avatar' => array(
                      'width' => 150,
                      'height' => 150
                	),
                ); ?>
                  <li ng-show="showThumbnailInstruction"><?php printf(__("Thumbnail: %sx%s", 'ultimate-viral-quiz'),$instructions['thumbnail']['width'],$instructions['thumbnail']['height']) ?></li>
                  <li ng-show="showAnswersInstruction"><?php printf(__("Answer: %sx%s", 'ultimate-viral-quiz'),$instructions['answers']['width'],$instructions['answers']['height']) ?></li>
                  <li ng-show="showLandscapeInstruction"><?php printf(__("Landscape: %sx%s", 'ultimate-viral-quiz'),$instructions['questions']['width'],$instructions['questions']['height']) ?></li>
                  <li ng-show="showWideLandscapeInstruction"><?php printf(__("Wide Landscape: %sx%s", 'ultimate-viral-quiz'),$instructions['wide_landscape']['width'],$instructions['wide_landscape']['height']) ?></li>
                  <li ng-show="showPortraitInstruction"><?php printf(__("Portrait: %sx%s", 'ultimate-viral-quiz'),$instructions['results']['width'],$instructions['results']['height']) ?></li>
                  <li ng-show="showAvatarInstruction"><?php printf(__("Avatar: %sx%s", 'ultimate-viral-quiz'),$instructions['avatar']['width'],$instructions['avatar']['height']) ?></li>
                </ul>
              </div>
            </div>

            <div ng-show="showTypeImageUrl" class="sub-popup clearfix col-xs-12 col-md-8 center-block">
              <ul class="list-unstyled">
                <li>
                  <div class="form-group">
                    <label><?php esc_html_e("Enter the image's URL", 'ultimate-viral-quiz') ?></label>
                    <input ng-model="imageByTypeUrl" type="text" maxlength="200" class="form-control" value="" placeholder="<?php esc_attr_e("Enter the image's URL", 'ultimate-viral-quiz') ?>">
                    <br>
                    <p class="text-muted"><?php esc_html_e("Example: http://image-url/image.jpg", 'ultimate-viral-quiz') ?></p>
                  </div>
                </li>
                <li class="pull-right">
                  <button type="button" ng-click="closePopTypeImageUrl()" class="dbuzz-ghost-btn text-center"><?php esc_html_e("Cancel", 'ultimate-viral-quiz') ?></button>
                  <button type="button" ng-click="uploadByUrlImage()" class="dbuzz-ghost-btn blue-theme text-center"><?php esc_html_e("Done", 'ultimate-viral-quiz') ?></button>
                </li>
              </ul>
            </div>

            <div ng-show="showUploadVideo" class="sub-popup clearfix col-xs-12 col-md-8 center-block">
              <ul class="list-unstyled">
                <li>
                  <div class="form-group">
                    <label><?php esc_html_e("Choose YouTube Clip", 'ultimate-viral-quiz') ?></label>
                    <input ng-model="youtubeURL" type="text" maxlength="200" class="form-control" value="" placeholder="<?php esc_attr_e("Enter clip URL or ID", 'ultimate-viral-quiz') ?>">
                    <br>
                    <p class="text-muted"><?php esc_html_e("We support URLs, video IDs and embed codes from Youtube. Make sure you choose a publicly available video.", 'ultimate-viral-quiz') ?></p>
                  </div>
                </li>
                <li class="pull-right">
                  <button type="button" ng-click="closePopUploadVideo()" class="dbuzz-ghost-btn text-center"><?php esc_html_e("Cancel", 'ultimate-viral-quiz') ?></button>
                  <button type="button" ng-click="processUploadVideo()" class="dbuzz-ghost-btn blue-theme text-center"><?php esc_html_e("Done", 'ultimate-viral-quiz') ?></button>
                </li>
              </ul>
            </div>

            <div ng-show="showCropImage" class="crop-image-container">
              <div class="crop-image" ng-jcrop></div>
            </div>

            <div ng-show="showEditVideoWrapper" id="edit-video-container">
              <div class="embed-responsive embed-responsive-16by9">
                <youtube></youtube>
              </div>
              <br/>
              <ul class="mark-video-time list-unstyled list-inline">
                <li>
                  <div ng-click="setYoutubeStartTime()" class="btn btn-default"><?php esc_html_e("Mark Start", 'ultimate-viral-quiz') ?></div>
                  <p class="text-center">{{ youtubeStartTime * 1000 | date:"HH:mm:ss" : "UTC" }}</p>
                </li>
                <li>
                  <div ng-click="setYoutubeEndTime()" class="btn btn-default"><?php esc_html_e("Mark End", 'ultimate-viral-quiz') ?></div>
                  <p class="text-center">{{ youtubeEndTime * 1000 | date:"HH:mm:ss" : "UTC" }}</p>
                </li>
              </ul>
            </div>

          </div>
          <div class="modal-footer">
            <button ng-click="popCancel()" type="button" class="dbuzz-ghost-btn text-center" data-dismiss="modal"><?php esc_html_e("Cancel", 'ultimate-viral-quiz') ?></button>
            <button ng-show="showDoneButton" ng-click="popDone()" type="button" class="dbuzz-ghost-btn blue-theme text-center"><?php esc_html_e("Done", 'ultimate-viral-quiz') ?></button>
          </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div id="editTextPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myLargeModalLabel"> <span class="ti-pencil-alt"></span> <?php esc_html_e("Edit text", 'ultimate-viral-quiz') ?></h4>
          </div>
          <div class="modal-body body-popup">
            <div class="sub-popup clearfix col-xs-12 center-block">
              <ul class="list-unstyled">
                <li>
                  <div class="form-group">
                    <textarea ng-model="editorText" maxlength="1000" rows="5" class="form-control" placeholder="<?php esc_attr_e("Enter text", 'ultimate-viral-quiz') ?>"></textarea>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button ng-click="popTextEditorCancel()" type="button" class="dbuzz-ghost-btn text-center" data-dismiss="modal"><?php esc_html_e("Cancel", 'ultimate-viral-quiz') ?></button>
            <button ng-click="popTextEditorDone()" type="button" class="dbuzz-ghost-btn blue-theme text-center"><?php esc_html_e("Done", 'ultimate-viral-quiz') ?></button>
          </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
    