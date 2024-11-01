(function($) {
  "use strict";
  dbuzzapp.controller('poll',['$scope', '$rootScope', '$sce', '$http', '$window',
      function($scope, $rootScope, $sce, $http, $window) {
        $scope.currentQuestion = 0;

        $scope.init = function(){
            $scope.questions = GAMEINFO.questions;
            $scope.gameID = GAMEINFO.gameID;
          };

          if(GAMEINFO) {
            $scope.init();
          }
          $scope.nextQuestion = function(){
            $scope.currentQuestion = $scope.currentQuestion + 1;
          };
          $scope.vote = function(answerIndex, questionIndex){
            if(!USERS.is_user_logged_in)
            {
              if(confirm(AJAX.msg.must_login_to_use)) {
                $window.location.href = URLS.login;
              }
              return;
            }

            if($scope.questions[questionIndex].voted) {
              return;
            }
            $scope.calculating = true;
            var data = {};
            data.gameID = $scope.gameID;
            data.questionIndex = questionIndex;
            data.answerIndex = answerIndex;

            $scope.showLoading = true;
            $http.post(AJAX.url + "?action=poll_vote" , data).
              success(function(data, status, headers, config) {
                alert(data.msg);
                if(data.status === 'ok')
                {
                  $scope.questions[questionIndex].voted = true;
                  $scope.questions[questionIndex].votedIndex = answerIndex;
                  $scope.questions[questionIndex].totalVoted++;
                  $scope.questions[questionIndex].answers[answerIndex].totalVoted++;
                }
                  //$scope.itemActive = index;

                $scope.calculating = false;
              }).
              error(function(data, status, headers, config) {
                alert(AJAX.message.undefine);
                $scope.calculating = false;
                return;
              });
          };
      }]);
  //---------------------------
  // Rank Controller
  dbuzzapp.controller('pollEditor',['$scope', '$rootScope', '$sce', '$http', '$window',
      function($scope, $rootScope, $sce, $http, $window) {
          $scope.tags = null;
          $scope.permission = 1;
          $scope.maxSelectTags = 3;

          $scope.gameID = null;

          $scope.currentPopup = null;
          $scope.currentPopupThumbnail = 'thumbnail';
          $scope.currentPopupAnswer = 'answers';
          $scope.currentPopupQuestion = 'questions';

          $scope.currentProcess = null;
          $scope.currentProcessVideo = 'video';
          $scope.currentProcessImage = 'image';
          $scope.minWidth = 0;
          $scope.minHeight = 0;

          $scope.showMainUpload = true;

          $scope.youtubeEmbed = null;

          $scope.imageByTypeUrl = null;

          $scope.thumbImage = null;

          $scope.imageCropped = null;

          $scope.urlUpload = AJAX.url + '?action=upload_images';

          $scope.editText = EDITOR.text.editImage;

          $scope.explain = {
            show: false,
            top: 0,
            left: 0
          };

          $scope.questions = [{
            useImage: true,
            totalVoted: 0,
            caption:"",
            image:"",
            data:"",
            answers: [
              {
                image: "",
                data: "",
                text: "",
                totalVoted: 0,
                voted: [],
              },
              {
                image: "",
                data: "",
                text: "",
                totalVoted: 0,
                voted: [],
              }
            ]
          }];
          $scope.maxQuestion = 20;
          $scope.minQuestion = 1;
          $scope.showAddQuestion = true;
          $scope.currentNumQuestion = $scope.questions.length;

          $scope.maxAnswer = 8;
          $scope.minAnswer = 2;

          /* Init */

          $scope.init = function(){
            $scope.gameID = GAMEINFO.gameID;
            $scope.gameTitle = GAMEINFO.gameTitle;
            $scope.gameDesc = GAMEINFO.gameDesc;
            $scope.thumbImage = GAMEINFO.thumbImage;

            $scope.questions = GAMEINFO.questions;
          };

          if(GAMEINFO) {
            $scope.init();
          }

          /* Add function */
          $scope.addQuestion = function(){
            if($scope.questions.length < $scope.maxQuestion)
            {
              var tmp = {
                useImage: true,
                totalVoted: 0,
                caption:"",
                image:"",
                data:"",
                answers: [
                  {
                    image: "",
                    data: "",
                    text: "",
                    totalVoted: 0,
                    voted: [],
                  },
                  {
                    image: "",
                    data: "",
                    text: "",
                    totalVoted: 0,
                    voted: [],
                  }
                ]
              };
              $scope.questions.push(tmp);
            }
          };
          $scope.addAnswer = function(index){
            var associateTmp = [];
            var tmp = {
                    image: "",
                    data: "",
                    text: "",
                    totalVoted: 0,
                    voted: [],
                  };
            $scope.questions[index].answers.push(tmp);
          };
          /* Remove function */
          $scope.removeAnswer = function(answerIndex, questionIndex){
            angular.forEach($scope.questions[questionIndex].answers, function (value, index) {
                  if(answerIndex === index)
                  {
                      $scope.questions[questionIndex].answers.splice($scope.questions[questionIndex].answers.indexOf(value), 1);
                  }
              });
          };
          $scope.removeQuestion = function(questionIndex){
            angular.forEach($scope.questions, function (value, index) {
                  if(questionIndex === index)
                  {
                      $scope.questions.splice($scope.questions.indexOf(value), 1);
                  }
              });
          };
          $scope.removeImageQuestion = function(index){
            $scope.questions[index].image = null;
            $scope.questions[index].data = null;
            return;
          };
          $scope.removeImageAnswer = function(answerIndex, questionIndex){
            $scope.questions[questionIndex].answers[answerIndex].image = null;
            $scope.questions[questionIndex].answers[answerIndex].data = null;
          };
          $scope.removeImageThumbnail = function(){
            $scope.thumbImage = null;
          };
          /* Move function */
          $scope.moveUpQuestion = function(index){
            if($scope.questions[index - 1])
            {
              var tmp = $scope.questions[index - 1];
              $scope.questions[index - 1] = $scope.questions[index];
              $scope.questions[index] = tmp;
            }
          };
          $scope.moveDownQuestion = function(index){
            if($scope.questions[index + 1])
            {
              var tmp = $scope.questions[index + 1];
              $scope.questions[index + 1] = $scope.questions[index];
              $scope.questions[index] = tmp;
            }
          };
          /* Done and Cancel */
          $scope.popCancel = function(){
            jQuery('#uploadPopup').modal('hide');
            $scope.showAlertOverlay = false;
            $scope.showUploadMedia = false;
            $scope.showDoneButton = false;

            $scope.showUploadImage = false;

            $scope.showCropImage = false;
            $scope.showCropImageOption = false;
            $scope.showUploadImageOvelay = false;

            $scope.showAnswersInstruction = false;
            $scope.showLandscapeInstruction = false;
            $scope.showQuestionInstruction = false;
            $scope.showThumbnailInstruction = false;

            $rootScope.showUploadImageOvelay = false;
            $scope.showTypeImageUrl = false;
            $scope.showFromYouTube = false;

            $scope.showEditVideoWrapper = false;
            $scope.showEditImage = false;

            $scope.imageByTypeUrl = null;
            $scope.youtubeURL = null;
            $scope.youtubeID = null;

            switch($scope.currentProcess){
              case $scope.currentProcessImage:
                $rootScope.rootImageUploaded = null;
                $scope.cropOn = false;
                $scope.destroyCrop = true;
                break;
              case $scope.currentProcessVideo:
                $scope.youtubeEmbed = null;
                break;
            }

            $scope.youtubeID = null;
            $scope.youtubeURL = null;
            $scope.youtubeEmbedString = null;
            $scope.youtubeEmbed = null;
            $scope.youtubeThumbnail = null;
            $scope.youtubeStartTime = null;
            $scope.youtubeEndTime = null;

            $rootScope.currentProcess = null;
            $scope.currentProcess = null;
            $scope.currentPopup = null;
            $scope.currentPopupQuestionIndex = null;
            $scope.currentPopupAnswerIndex = null;
          };
          $scope.popDone = function(){
            var tmp = {};
            switch($scope.currentPopup){
              case $scope.currentPopupThumbnail:
                tmp = $rootScope.rootImageUploaded;
                tmp.cropped_url = $scope.imageCropped;
                $scope.thumbImage = tmp;
                break;
              case $scope.currentPopupQuestion:
                  if($scope.currentProcess === $scope.currentProcessImage)
                  {
                    tmp = $rootScope.rootImageUploaded;
                    tmp.cropped_url = $scope.imageCropped;
                    tmp.dataType = $scope.currentProcessImage;

                    $scope.questions[$scope.currentPopupQuestionIndex].data = tmp;
                    $scope.questions[$scope.currentPopupQuestionIndex].image = $scope.imageCropped;
                  }
                  else if($scope.currentProcess === $scope.currentProcessVideo)
                  {
                    tmp.dataType = $scope.currentProcessVideo;
                    tmp.youtubeID = $scope.youtubeID;
                    tmp.youtubeURL = $scope.youtubeURL;
                    tmp.youtubeEmbedString = $scope.youtubeEmbedString + "?start="+$scope.youtubeStartTime+"&end="+$scope.youtubeEndTime+"&version=3";
                    tmp.youtubeEmbed = $sce.trustAsResourceUrl(tmp.youtubeEmbedString);
                    tmp.youtubeStartTime = $scope.youtubeStartTime;
                    tmp.youtubeEndTime = $scope.youtubeEndTime;

                    $scope.questions[$scope.currentPopupQuestionIndex].data = tmp;
                    $scope.questions[$scope.currentPopupQuestionIndex].image = $scope.youtubeThumbnail;
                  }
                  break;
              case $scope.currentPopupAnswer:
                  if($scope.currentProcess === $scope.currentProcessImage)
                  {
                    tmp = $rootScope.rootImageUploaded;
                    tmp.cropped_url = $scope.imageCropped;
                    tmp.dataType = $scope.currentProcessImage;

                    $scope.questions[$scope.currentPopupQuestionIndex].answers[$scope.currentPopupAnswerIndex].data = tmp;
                    $scope.questions[$scope.currentPopupQuestionIndex].answers[$scope.currentPopupAnswerIndex].image = $scope.imageCropped;
                  }
                  else if($scope.currentProcess === $scope.currentProcessVideo)
                  {
                    tmp.dataType = $scope.currentProcessVideo;
                    tmp.youtubeID = $scope.youtubeID;
                    tmp.youtubeURL = $scope.youtubeURL;
                    tmp.youtubeEmbedString = $scope.youtubeEmbedString + "?start="+$scope.youtubeStartTime+"&end="+$scope.youtubeEndTime+"&version=3";
                    tmp.youtubeEmbed = $sce.trustAsResourceUrl(tmp.youtubeEmbedString);
                    tmp.youtubeStartTime = $scope.youtubeStartTime;
                    tmp.youtubeEndTime = $scope.youtubeEndTime;

                    $scope.questions[$scope.currentPopupQuestionIndex].answers[$scope.currentPopupAnswerIndex].data = tmp;
                    $scope.questions[$scope.currentPopupQuestionIndex].answers[$scope.currentPopupAnswerIndex].image = $scope.youtubeThumbnail;
                  }
                  break;
            }
            //$scope.imageCropped = null;
            $scope.popCancel();
          };
          /* Popup function */
          $scope.popupOpen = function(){
            jQuery('#uploadPopup').modal();
            $scope.showMainUpload = true;
            $scope.showTypeImageUrl = false;
            $scope.showUploadVideo = false;

            $scope.showUploadMedia=true;
            $scope.showAlertOverlay=true;
            $scope.showUploadImage=true;
            $scope.showFromURL = true;
            $scope.showFromFile = true;
            $scope.showFromYouTube = true;

            $scope.showUploadImageOvelayLover = false;
            $scope.showUploadImageOvelay = false;
          };
          $scope.popUploadThumbnail = function(){
            $scope.popupOpen();
            $scope.showFromYouTube = false;

            $scope.currentPopup = $scope.currentPopupThumbnail;
            $scope.showUploadMedia=true;
            $scope.showAlertOverlay=true;
            $scope.showUploadImage=true;
            $scope.showFromURL = true;
            $scope.showFromFile = true;
            $scope.showThumbnailInstruction = true;

            $scope.minWidth = DIMENSION.thumbnail.width;
            $scope.minHeight = DIMENSION.thumbnail.height;

            if($scope.thumbImage)
            {
              $scope.editText = EDITOR.text.editImage;
              $scope.showEditImage = true;
              $scope.editImage = $scope.thumbImage.cropped_url;
            }
          };
          $scope.popUploadQuestion = function(index){
            $scope.currentPopup = $scope.currentPopupQuestion;
            $scope.currentPopupQuestionIndex = index;

            $scope.showWideLandscapeInstruction = true;
            $scope.minWidth = DIMENSION.wide_landscape.width;
            $scope.minHeight = DIMENSION.wide_landscape.height;

            $scope.popupOpen();
            if($scope.questions[index].data)
            {
              $scope.showEditImage = true;
              $scope.editImage = $scope.questions[index].image;

              switch($scope.questions[index].data.dataType){
                case $scope.currentProcessImage:
                  $scope.editText = EDITOR.text.editImage;
                  // $scope.currentProcess = $scope.currentProcessImage;
                  break;
                case $scope.currentProcessVideo:
                  $scope.editText = EDITOR.text.editVideo;
                  // $scope.currentProcess = $scope.currentProcessVideo;
                  break;
              } // end switch
              
            }// end if
          };
          $scope.popUploadAnswer = function(indexAnswer, indexQuestion){
            $scope.currentPopup = $scope.currentPopupAnswer;
            $scope.currentPopupQuestionIndex = indexQuestion;
            $scope.currentPopupAnswerIndex = indexAnswer;

            $scope.showAnswersInstruction = true;
            $scope.minWidth = DIMENSION.answers.width;
            $scope.minHeight = DIMENSION.answers.height;

            $scope.popupOpen();
            $scope.showFromYouTube = false;

            if($scope.questions[indexQuestion].answers[indexAnswer].data)
            {
              $scope.showEditImage = true;
              $scope.editImage = $scope.questions[indexQuestion].answers[indexAnswer].image;

              switch($scope.questions[indexQuestion].answers[indexAnswer].data.dataType){
                case $scope.currentProcessImage:
                  $scope.editText = EDITOR.text.editImage;
                  // $scope.currentProcess = $scope.currentProcessImage;
                  break;
                case $scope.currentProcessVideo:
                  $scope.editText = EDITOR.text.editVideo;
                  // $scope.currentProcess = $scope.currentProcessVideo;
                  break;
              } // end switch
              
            }// end if
          };
          $scope.onMouseOverExplain = function(event){
            $scope.explain.top = event.clientY + 'px';
            $scope.explain.left = event.clientX + 20 + 'px';
            $scope.explain.show = true;
          };
          /* preview */
          $scope.preview = function(){
            if ($scope.gameID) {
              $window.open(AJAX.buzz_url + $scope.gameID,'_blank');
            }
          };
          /* saveDraft */
          $scope.saveDraft = function(){
            $scope.post_status = 'draft';
            $scope.savebuzz();
          };
          /* save */
          $scope.save = function(){
            $scope.post_status = 'publish';
            $scope.savebuzz();
          };
          /* Savebuzz */
          $scope.savebuzz = function(){
            var data = {};
            data.content = {};
            
            data.gameID = $scope.gameID;
            data.thumbImage = $scope.thumbImage;
            data.gameTitle = $scope.gameTitle;
            data.gameDesc = $scope.gameDesc;

            data.content.questions = $scope.questions;

            data.gameType = GAMETYPE.poll;
            
            data.permission = $scope.permission;
            data.category = $scope.category;

            data.post_status = $scope.post_status;

            $scope.mytags = jQuery('#myTags').pillbox('items');
            var tmp = '';
            angular.forEach($scope.mytags, function (value, index) {
              if(index > 0) {
                tmp += ',';
              }
              tmp += value.value;
            });
            data.tags = tmp;

            angular.forEach($scope.tags, function (value, index) {
              if(value.isCheck) {
                data.tags += ',' + value.name;
              }
            });

            //console.log('all-content', data);

            $rootScope.showLoading = true;

            $http.post(AJAX.url + "?action=save_game" , data).
              success(function(data, status, headers, config) {
                  if(data.status === 'notice')
                  {
                    $scope.gameID = data.gameID;
                    uvqAddQueryString('idQuiz', data.gameID);
                  }
                  $.growl({ title: data.title, message: data.message, location: 'br', style: data.status });
                  $rootScope.showLoading = false;
              }).
              error(function(data, status, headers, config) {
                  $.growl({ title: data.message, message: '', location: 'br', style: 'error' });
                  $rootScope.showLoading = false;
                  return;
              });
          };

          $rootScope.$watch('permission', function(newVal, oldVal){
            $scope.permission = newVal;
          });
          $rootScope.$watch('category', function(newVal, oldVal){
            $scope.category = newVal;
          });
          $rootScope.$watch('tags', function(newVal, oldVal){
              $scope.tags = newVal;
          });
          $rootScope.$watch('mytags', function(newVal, oldVal){
              $scope.mytags = newVal;
          });
      } // end function
  ]);
})(jQuery);