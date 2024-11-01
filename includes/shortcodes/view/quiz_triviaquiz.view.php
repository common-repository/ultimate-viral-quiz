<?php if(isset($this->post) && !empty($this->post)): ?>
<div class="uvq-view-container" ng-app="dbuzzapp" ng-controller="buzzController">
<div ng-controller="trivia">
	<article class="trivia-content buzz-content entry clearfix">
      <div class="row clearfix">
        <div class="col-xs-12 item-content">
          <span class="text-description-content"><?php echo bb_esc_html(get_the_content()) ?></span>
        </div>
      </div>
      <br>

      <div class="item-content clearfix">

        <div class="second-content row">
          <div class="col-xs-12">
            <div class="trivia-process process-bar-wrapper sub-fixed" data-sticky data-sticky-wrap data-margin-top="0">
              <div class="trivia-cup"><span class="ti-crown"></span></div>
              <ul class="question-box list-unstyled list-inline">
                <li ng-click="scrollTo('#question-'+$index)" ng-repeat="question in questions" ng-init="question.showCaption=true" class="{{  (question.answered)?( question.answered.correct) ? 'box-correct' : 'box-wrong' : '' }}"><span>{{ $index + 1 }}</span></li>
              </ul>
            </div>
            <br>
            <div class="game-wrapper">
              <div class="game-container">
                <div ng-repeat="question in questions">
                  <div id="question-{{ $index }}" class="question-media">
                    <span class="list-number clearfix">{{ $index + 1 }}</span>

                    <div ng-show="!question.answered || (question.answered && !question.result.image)">
                      <img ng-show="question.data.dataType=='image'" class="media-detail" ng-src="{{ question.image }}" alt=""> 

                      <div ng-show="question.data.dataType=='video'" class="media-detail embed-responsive embed-responsive-16by9">
                        <iframe ng-show="question.data.dataType=='video'" youtube-src="{{ question.data.youtubeEmbedString }}"></iframe>
                      </div>
                    </div>

                    <div ng-show="(question.answered && question.result.image)">
                      <img ng-show="(question.result.data.dataType=='image')" class="media-detail" ng-src="{{ question.result.image }}" alt=""> 
                      <div ng-show="question.result.data.dataType=='video'" class="media-detail embed-responsive embed-responsive-16by9">
                        <iframe ng-show="question.result.data.dataType=='video'" youtube-src="{{ question.result.data.youtubeEmbedString }}"></iframe>
                      </div>
                    </div>
                    
                    <div class="media-caption">
                      <span ng-click="question.showCaption=!question.showCaption" class="close-caption {{ question.showCaption ? 'ti-close' : 'ti-plus' }}"></span>
                      <span ng-show="question.showCaption" class="text">{{ (question.answered && question.result.caption)?question.result.caption:question.caption }}</span>
                    </div>

                  </div>
                  <div class="question-answers">
                    <ul class="list-unstyled list-inline clearfix {{ (question.answered) ? 'voted':'before-vote' }}">
                      <li ng-click="chooseAnswer($index, $parent.$index)" class="auto-height 
                      {{ (question.answered && answer.correct) ?'box-correct':'' }}
                      {{ (question.answered.index == $index && !question.answered.correct)?'box-wrong' :'' }}
                      {{ calcAnswerBox(question.answers.length, question.answerFullWidth, answer.image) }}"
                      ng-repeat="answer in question.answers">
                        <img ng-show="question.useImage" class="img-answer" ng-src="{{ answer.image }}" alt="">
                        <span class="text {{ (question.answers.length >=3 && (question.answers.length % 3 != 0 || question.answers.length % 2 != 0))?'':'text-center' }}">{{ answer.text }}</span>
                      </li>
                    </ul>
                  </div>
                  <br/><br/>
                </div>
              </div>

              <div id="calculating" ng-show="calculating" class="calculating">
                <div class="circle">&nbsp;</div>
                <div class="circle">&nbsp;</div>
                <div class="circle">&nbsp;</div>
                <div class="circle">&nbsp;</div>
              </div>
			  
			  <?php $force_share = get_option(BESTBUG_UVQ_PREFIX . 'force_share') ?>
			  
              <div ng-show="result" id="result" class="game-result <?php if($force_share == 'yes') echo 'uvq-force-share' ?>">
                	<hr>
				
					<?php if($force_share == 'yes') include 'content_locker.view.php'; ?>
				
				    <div class="uvq-locker-content">
		                <h2 class="text-result-title text-danger">{{ result.title }}</h2>
		                <div class="content-result-media">
		                  <div ng-show="item.data.dataType=='video'" class="embed-responsive embed-responsive-16by9">
		                    <iframe ng-show="result.data.dataType=='video'" youtube-src="{{ result.data.youtubeEmbedString }}"></iframe>
		                  </div>
		                  <img ng-show="result.data.dataType=='image'" ng-src="{{ result.image }}" class="result-media-detail" alt="{{ result.title }}">
		                </div>
		                <br>
		                <p><a ng-click="resetGame()" class="col-xs-12 col-md-3 dbuzz-ghost-btn blue-theme text-center" href="javascript:;">
		                  <?php esc_html_e("Play again?", 'ultimate-viral-quiz') ?></a></p>
				    </div>
              </div>

            </div>
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