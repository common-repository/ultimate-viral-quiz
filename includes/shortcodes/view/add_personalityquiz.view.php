<div class="uvq-editor-container" ng-app="dbuzzapp" ng-controller="buzzController">
<div class="editor-container" ng-controller="quizPersonality">

<main class="main col-xs-12 col-md-8" role="main">
<section class="create-content-buzz">
  <div class="head-widget">
    <h2 class="title-widget">
      <a href="javascript:;"><?php esc_html_e("Quiz's content", "uvq") ?></a>
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
      <legend class="scheduler-border"><?php esc_html_e("Manage the Questionnaire's Results", 'ultimate-viral-quiz') ?></legend>
      <div class="editor-results col-xs-12">
          <ul class="list-unstyled list-inline">
            <li class="result-item col-sm-3 col-xs-6" ng-repeat="result in results">
              <div class="result-item-contain clearfix">
                <span ng-show="results.length > minResult" ng-click="removeResult($index)" class="remove ti-trash"></span>
                <div class="form-group">
                  <input type="text" maxlength="300" class="form-control" value="" ng-model="result.title" placeholder="<?php esc_attr_e("Title", 'ultimate-viral-quiz') ?>">
                </div>
                <div class="result-media upload-media">
                  <div ng-click="popUploadResult($index)"style="background-image: url({{ result.image }})" class="upload-thumbnail" >
                    <span class="upload-text {{ result.image ? 'upload-edit-text' : '' }}">{{ result.image ? result.data.dataType == currentProcessVideo ? '<?php esc_html_e("Click to edit Video", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to edit Image", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to add Photo/Video", 'ultimate-viral-quiz') ?>' }}</span>
                    <span ng-click="removeImageResult($index); $event.stopPropagation()" ng-show="result.image" class="upload-remove ti-close"></span>
                  </div>
                </div>
                <a ng-click="popeEitResultText($index)" class="col-xs-12 dbuzz-ghost-btn text-center" href="javascript:;"><?php esc_html_e("Edit text", 'ultimate-viral-quiz') ?></a>
              </div>
            </li>
          </ul>
          <div class="add-button col-sm-3">
            <a ng-show="results.length < maxResult" ng-click="addResult()" class="col-xs-12 dbuzz-ghost-btn blue-theme text-center" href="javascript:;"><?php esc_html_e("Add result", 'ultimate-viral-quiz') ?></a>
          </div>
      </div>
    </fieldset>
    <br/>
    <fieldset class="scheduler-border">
      <legend class="scheduler-border"><?php esc_html_e("Manage the Questions", 'ultimate-viral-quiz') ?></legend>
      <div class="editor-questions col-xs-12">
          <ul class="list-unstyled clearfix">
            <li class="question-item col-xs-12" ng-repeat="question in questions">
              <h2 class="question-number">
                <span class="number label label-primary">{{ $index + 1 }}</span>
              </h2>
              <div class="move-button">
                  <div ng-click="moveUpQuestion($index)" ng-show="$index > 0 && questions.length > 1" class="move-up"><span class="ti-angle-up"></span></div>
                  <div ng-click="moveDownQuestion($index)" ng-show="$index < questions.length - 1" class="move-down"><span class="ti-angle-down"></span></div>
              </div>
              <div class="question-media upload-media">
                <div ng-click="popUploadQuestion($index)" style="background-image: url({{ question.image }})" class="upload-thumbnail">
                  <span ng-show="questions.length <= minQuestion ? false : true" ng-click="removeQuestion($index)" class="remove ti-trash"></span>
                  <span class="upload-text {{ question.image ? 'upload-edit-text' : '' }}">{{ question.image ? question.data.dataType == currentProcessVideo ? '<?php esc_html_e("Click to edit Video", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to edit Image", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to add Photo/Video", 'ultimate-viral-quiz') ?>' }}</span>
                  <span ng-click="removeImageQuestion($index); $event.stopPropagation()" ng-show="question.image" class="upload-remove ti-close"></span>
                  <div class="form-group">
                    <textarea ng-click="$event.stopPropagation()" ng-model="question.caption" maxlength="500" class="question-caption form-control" value="" placeholder="<?php esc_attr_e("Type your caption...", 'ultimate-viral-quiz') ?>"></textarea>
                  </div>
                </div>
              </div>
              <div class="switch-contain">
                <switch id="enabled" name="enabled" ng-model="question.useImage" class="green"></switch> 
                <span class="text"><?php esc_html_e("Use image for Answers?", 'ultimate-viral-quiz') ?></span>
              </div>

              <div class="answers-contain">
                <ul class="list-unstyled list-inline">
                  <li class="answer-item col-sm-4 col-xs-6" ng-repeat="answer in question.answers">
                    <span ng-click="removeAnswer($index, $parent.$index)" ng-show="question.answers.length > minAnswer ? true : false" class="remove ti-trash"></span>
                    <div class="answer-item-contain clearfix">
                      <div class="form-group">
                        <input ng-model="answer.caption" placeholder="<?php esc_attr_e("Enter text", 'ultimate-viral-quiz') ?>" type="text" maxlength="500" class="form-control" value="">
                      </div>
                      <div class="answer-media upload-media">
                        <div ng-click="popUploadAnswer($index, $parent.$index)" ng-show="question.useImage" style="background-image: url({{ answer.image }})" class="upload-thumbnail">
                          <span class="upload-text {{ answer.image ? 'upload-edit-text' : '' }}">{{ answer.image ? answer.data.dataType == currentProcessVideo ? '<?php esc_html_e("Click to edit Video", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to edit Image", 'ultimate-viral-quiz') ?>' : '<?php esc_html_e("Click to add Photo/Video", 'ultimate-viral-quiz') ?>' }}</span>
                          <span ng-click="removeImageAnswer($index, $parent.$index); $event.stopPropagation()" ng-show="answer.image" class="upload-remove ti-close"></span>
                        </div>
                      </div>
                      <p class="lead text-center" data-toggle="modal" data-target="#explain-associate-result">
                        <?php esc_html_e("Associate to results:", 'ultimate-viral-quiz') ?> 
                        <span class="label label-info label-help"><span class="ti-help"></span></span>
                      </p>
                      <ul class="associates list-unstyled">
                        <li ng-repeat="assoc in answer.associate">
                          <div class="ass-thumbnail col-xs-2">
                            <div class="thumb-detail" style="background-image: url({{ results[$index].image }})" ></div>
                          </div>
                          <div class="ass-title col-xs-7">{{ results[$index].title }}</div>
                          <div class="ass-point col-xs-3">
                            <select ng-model="assoc.point" class="persionality-assoc form-control">
                              <option>0</option>
                              <option>1</option>
                              <option>2</option>
                              <option>3</option>
                            </select>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </li>
                </ul> <!-- answers -->
                <div class="add-button col-sm-3">
                  <a ng-click="addAnswer($index)" ng-show="question.answers.length < maxAnswer ? true : false" class="col-xs-12 dbuzz-ghost-btn blue-theme text-center" href="javascript:;"><?php esc_html_e("Add answer", 'ultimate-viral-quiz') ?></a>
                </div>
              </div>
            </li>

          </ul>
          <hr>
          <div class="add-button col-sm-3">
            <a ng-click="addQuestion()" ng-show="questions.length < maxQuestion" id="add_question_button" class="col-xs-12 dbuzz-ghost-btn blue-theme text-center" href="javascript:;"><?php esc_html_e("Add question", 'ultimate-viral-quiz') ?></a>
          </div>
      </div>
    </fieldset>
  </div>
    <?php include_once 'editor_form_upload.view.php'; ?>
</section>
</main><!-- /.main -->

<?php include_once 'editor_sidebar.view.php'; ?>

</div>

<div id="explain-associate-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="label label-info label-help"><span class="ti-help"></span></span> <?php esc_html_e("Explain associate result", 'ultimate-viral-quiz') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php esc_html_e("<b>Choose which results are associated with this answer:</b><br>0 - no association<br>1 - normal<br>2 - strongly associated", 'ultimate-viral-quiz') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php esc_html_e("OK", 'ultimate-viral-quiz') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
