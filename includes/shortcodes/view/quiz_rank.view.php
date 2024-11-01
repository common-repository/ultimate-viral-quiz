<?php if(isset($this->post) && !empty($this->post)): ?>
<div class="uvq-view-container" ng-app="dbuzzapp" ng-controller="buzzController">
<div ng-controller="rankFrontend">
    <article class="rank-content list-content buzz-content entry clearfix">
      <div class="row">
          <div class="col-xs-12 item-content">
            <span class="text-description-content"><?php echo bb_esc_html(get_the_content()) ?></span>
          </div>
          <br>
          <div class="list-detail col-xs-12">
            <ul class="list-unstyled">
              <li id="item-{{ item.VotedIndex }}" class="clearfix" ng-repeat="item in items | orderBy:'-vote_up'">
                <div class="col-rank pull-left">
                    <div ng-click="vote('count_vote_up', $index)" class="vote-up ti-angle-up {{ (voted.index == item.VotedIndex && voted.type == 'count_vote_up')?'active':'' }}"></div>
                    <div class="point-up">+{{ item.count_vote_up | numberFormatA }}</div>
                    <div class="point-down">-{{ item.count_vote_down | numberFormatA }}</div>
                    <div ng-click="vote('count_vote_down', $index)" class="vote-down ti-angle-down {{ (voted.index == item.VotedIndex && voted.type == 'count_vote_down')?'active':'' }}"></div>
                </div>
                <div class="col-rank-content">
                  <div class="list-head">
                    <span class="list-number">{{ $index + 1 }}</span>
                    <h2 class="list-title">{{ item.title }}</h2>
                  </div>
                  <div class="list-media">
                    <img ng-show="item.data.dataType=='image'" class="full-width" image-src="{{ item.image }}"/>
                    <div ng-show="item.data.dataType=='video'" class="embed-responsive embed-responsive-16by9">
                      <iframe ng-hide="item.data.dataType!='video'" youtube-src="{{ item.data.youtubeEmbedString }}"></iframe>
                    </div>
                  </div>
                  <div class="text">
                      {{ item.caption }}
                  </div>
                </div>
              </li>
            </ul>

          </div>

      </div>
      <hr>

  </article>
  </div>
</div>
<?php
endif;
?>