<?php if(isset($this->post) && !empty($this->post)): ?>
<div class="uvq-view-container" ng-app="dbuzzapp" ng-controller="buzzController">
<div ng-controller="poll">
  <article class="poll-content buzz-content entry clearfix">

      <div class="row clearfix">
        <div class="col-xs-12 item-content">
            <span class="text-description-content"><?php echo bb_esc_html(get_the_content()) ?></span>
        </div>
      </div>
      <br/>
      <div class="item-content clearfix">

        <div ng-hide="isPlaying" class="second-content row">
          <div class="col-xs-12">
            <div ng-show="questions.length > 1" class="process-bar-wrapper">
              <ul class="question-box list-unstyled list-inline">
                <li ng-repeat="question in questions" class="{{  $index <= currentQuestion ? 'active' : '' }}"><span>{{ $index + 1 }}</span></li>
              </ul>
            </div>
            <div class="game-wrapper">
              <div ng-hide="showResult" class="game-container">
                <div ng-repeat="question in questions" ng-init="question.showCaption=true" ng-show="$index == currentQuestion">
                  <div class="question-media">

                    <div ng-show="calculating" class="calculating">
                      <div class="circle">&nbsp;</div>
                      <div class="circle">&nbsp;</div>
                      <div class="circle">&nbsp;</div>
                      <div class="circle">&nbsp;</div>
                    </div>

                    <img ng-hide="question.data.dataType!='image' || calculating" class="media-detail" image-src="{{ question.image }}"/>
                    <div ng-hide="question.data.dataType!='video' || calculating" class="embed-responsive embed-responsive-16by9">
                      <iframe ng-hide="question.data.dataType!='video'" youtube-src="{{ question.data.youtubeEmbedString }}"></iframe>
                    </div>

                    <div ng-show="question.voted && questions[$index+1]" ng-click="nextQuestion()" class="next-poll ti-angle-right"></div>
                    <div class="media-caption">
                      <span ng-click="question.showCaption=!question.showCaption" class="close-caption {{ question.showCaption ? 'ti-close' : 'ti-plus' }}"></span>
                      <span ng-show="question.showCaption" class="text">{{ question.caption }}</span>
                    </div>
                  </div>
                  <div class="poll-votes question-answers">
                    <ul class="list-unstyled list-inline clearfix {{ (question.voted)?'voted':'before-vote' }}">
                      <li ng-repeat="answer in question.answers" ng-click="vote($index, $parent.$index)" class="col-xs-12 {{ (question.voted && question.votedIndex == $index)?'active':'' }}">
                          <span ng-hide="question.voted" class="vote-ico ti-check pull-right"></span>
                          <span ng-show="question.voted" class="vote-percent pull-right">{{ (answer.totalVoted/question.totalVoted)*100 | number:0  }}%</span>
                          <img ng-show="question.useImage" class="img-vote col-xs-3 col-md-2" image-src="{{ answer.image }}" />
                          <span class="text"> {{ answer.text }}
                            <i class="text-muted" ng-show="question.voted">({{ answer.totalVoted }} <?php esc_html_e("voted", 'ultimate-viral-quiz') ?>)</i>
                          </span>
                          <div ng-show="question.voted" class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" style="width: {{ (answer.totalVoted/question.totalVoted)*100 | number:0  }}%"></div>
                          </div>
                      </li>
                    </ul>
                  </div>
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