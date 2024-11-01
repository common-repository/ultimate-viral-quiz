<?php if(isset($this->post) && !empty($this->post)): ?>
<div class="uvq-view-container" ng-app="dbuzzapp" ng-controller="buzzController">
<div ng-controller="playPersonalityQuiz">
	<article class="personality-quiz-content buzz-content entry clearfix">
	      <div class="item-content clearfix">
	        <div ng-hide="startGame" class="first-content row">
	          <div class="col-md-6 col-xs-12">
	            <img class="thumbnail-buzz-content" src="<?php esc_attr_e($thumbnail) ?>" alt="<?php esc_attr_e(get_the_title()) ?>" />
	            <div class="clearfix"></div>
	            <br/>
	            <a ng-click="playGame()" class="dbuzz-ghost-btn blue-theme clearfix col-xs-12 text-center" href="javascript:;"><?php esc_html_e("Let's play", 'ultimate-viral-quiz') ?></a>
	            <div class="clearfix"></div>
	            <br/>
	          </div>
	          <div class="col-md-6 col-xs-12 text-description-content">
	            <?php echo bb_esc_html(get_the_content()) ?>
	          </div>
	        </div>

	        <div id="game-contain" ng-show="showGame" class="second-content row">
	          <div class="col-xs-12">
	            <div class="process-bar-wrapper" data-sticky data-sticky-wrap data-margin-top="0">
	              <span ng-click="backQuestion()" class="back-control ti-angle-left"></span>
	              <ul class="question-box list-unstyled list-inline">
	                <li ng-repeat="question in questions" class="{{  $index <= currentQuestion ? 'active' : '' }}"><span>{{ $index + 1 }}</span></li>
	              </ul>
	            </div>
	            <div class="game-wrapper">
	              <div ng-show="$index == currentQuestion" class="game-container animate-show" ng-repeat="question in questions" ng-init="question.showCaption=true">
	                <div class="question-media">
	                  <img ng-hide="question.data.dataType!='image'" class="media-detail" image-src="{{ question.image }}"/>
	                  <div ng-hide="question.data.dataType!='video'" class="embed-responsive embed-responsive-16by9">
	                    <iframe ng-hide="question.data.dataType!='video'" youtube-src="{{ question.data.youtubeEmbedString }}"></iframe>
	                  </div>
	                  <div class="media-caption">
	                    <span ng-click="question.showCaption=!question.showCaption" class="close-caption {{ question.showCaption ? 'ti-close' : 'ti-plus' }}"></span>
	                    <span ng-show="question.showCaption" class="text">{{ question.caption }}</span>
	                  </div>
	                </div>
	                <div  class="question-answers">
	                  <ul class="list-unstyled list-inline clearfix before-vote">
	                    <li ng-repeat="answer in question.answers" ng-click="chooseAnswer($index, $parent.$index)" class="
	                    auto-height {{ calcAnswerBox(question.answers.length, question.answerFullWidth, answer.image) }}
	                    ">
	                      <img ng-hide="!answer.image" class="img-answer" image-src="{{ answer.image }}" alt="{{answer.caption}}" />
	                      <span ng-show="answer.caption" class="text text-center">{{ answer.caption }}</span>
	                    </li>
	                  </ul>
	                </div>
	              </div>

	            </div>
	          </div>
	        </div>

	        <!-- calculating -->
	        <div ng-show="isCalculating" class="calculating">
	          <div class="circle">&nbsp;</div>
	          <div class="circle">&nbsp;</div>
	          <div class="circle">&nbsp;</div>
	          <div class="circle">&nbsp;</div>
	        </div>

			<?php $force_share = get_option(BESTBUG_UVQ_PREFIX . 'force_share') ?>
	        <!-- result -->
	        <div ng-hide="!result" class="game-result <?php if($force_share == 'yes') echo 'uvq-force-share' ?>">
				
				<?php if($force_share == 'yes') include 'content_locker.view.php'; ?>
				
				<div class="uvq-locker-content">
		          <h2 class="result-title">{{ result.title }}</h2>
		          <div class="content-result-media">
		            <div ng-hide="result.data.dataType!='video'" class="embed-responsive embed-responsive-16by9">
		              <iframe youtube-src="{{ result.data.youtubeEmbedString }}"></iframe>
		            </div>
		            <img ng-hide="result.data.dataType!='image'" class="result-media-detail" image-src="{{ result.image }}" alt="{{ result.title }}" />
		          </div>
		          <p class="result-text">{{ result.text }}</p>
		          <p><a ng-click="resetGame()" class="col-xs-12 col-md-3 dbuzz-ghost-btn blue-theme text-center" href="javascript:;"><?php esc_html_e("Play again?", 'ultimate-viral-quiz') ?></a></p>
				</div>
				
	        </div>

	      </div>

	      <hr>

	  </article>
  </div>
</div>
<?php
endif;
?>