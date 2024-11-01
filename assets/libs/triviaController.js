(function($) {
  "use strict";
  dbuzzapp.controller('trivia',['$scope', '$rootScope', '$sce', '$http', '$timeout',
    function($scope, $rootScope, $sce, $http, $timeout) {
      $scope.gameID = null;
      if(typeof game !== 'undefined')
      {
        $scope.gameID = game.gameID;
        $scope.questions  = game.questions;
        angular.forEach($scope.questions, function (valueQuestion, indexQuestion) {
          angular.forEach($scope.questions[indexQuestion].answers, function (valueAnswer, indexAnswer) {
            if($scope.questions[indexQuestion].answers[indexAnswer].text.length >= 20)
            {
              $scope.questions[indexQuestion].answerFullWidth = true;
              return true;
            }
          });
        });
      }
      $scope.resetGame = function(){
        angular.forEach($scope.questions, function (question, index) {
          if(question.answered) {
            delete question.answered;
          }
          question.showCaption = false;
        });
        $scope.result = null;
        $scope.scrollTo('#question-0');
      };
      $scope.chooseAnswer = function(indexAnswer, indexQuestion){
          if($scope.questions[indexQuestion].answered) {
            return;
          }
          $scope.questions[indexQuestion].answered = {
                                                        index: indexAnswer,
                                                        correct: $scope.questions[indexQuestion].answers[indexAnswer].correct
                                                      };
          $scope.questions[indexQuestion].showCaption = true;

          $scope.calcResult();
      };
      $scope.calcResult = function(){
        var done = true;
        var count_true_answer = 0;
        angular.forEach($scope.questions, function (question, index) {
          if(!question.answered) {
            done = false;
          }
          else if(question.answered.correct) {
            count_true_answer++;
          }
        });
        if(!done) {
          return;
        }

        $scope.calculating = true;
        $scope.scrollTo('#calculating');
        var data = {};
        data.count_true_answer = count_true_answer;
        data.gameID = game.gameID;

        $http.post(AJAX.url + "?action=trivia_result" , data).
              success(function(data, status, headers, config) {
                if(data.status === 'ok')
                {
                  $scope.result = {title:data.result.title, image:data.result.image, data:data.result.data};
                  setTimeout(function(){ $scope.scrollTo('#result'); }, 200);
                }
                $scope.calculating = false;
              }).
              error(function(data, status, headers, config) {
                alert(AJAX.message.undefine);
                $scope.calculating = false;
                return;
              });
      };
      $scope.scrollTo = function(idHTML){
        jQuery('html, body').animate({
            scrollTop: jQuery(idHTML).offset().top - 100
        }, 800);
      };
      $scope.updateHeightBox = function(){
        setTimeout(function(){ jQuery('.auto-height').matchHeight(); }, 300);
      };
      $scope.updateHeightBox();
    }]);
  //---------------------------
  //---------------------------
  dbuzzapp.controller('triviaEditor',['$scope', '$rootScope', '$sce', '$http', '$window', 
      function($scope, $rootScope, $sce, $http, $window) {
          // $scope.showAlertOverlay = false;
          // $scope.showUploadMedia = false;
          // $scope.showDoneButton = false;

          // $scope.showUploadImage = false;

          // $scope.showCropImage = false;
          // $scope.showCropImageOption = false;
          // $scope.showUploadImageOvelay = false;

          $scope.tags = null;
          $scope.permission = 1;
          $scope.maxSelectTags = 3;

          $scope.gameID = null;

          $scope.currentPopup = null;
          $scope.currentPopupThumbnail = 'thumbnail';
          $scope.currentPopupAnswer = 'answers';
          $scope.currentPopupResult = 'results';
          $scope.currentPopupQuestion = 'questions';
          $scope.currentPopupQuestionResult = 'questionResult';

          $scope.currentPopupResultIndex = null;

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

          $scope.results = [
              {title:"", image:"", data:"", range: {begin:"", end:""}}
          ];
          $scope.maxResult = 8;
          $scope.minResult = 1;
          $scope.showAddResult = true;
          $scope.currentNumResult = $scope.results.length;

          $scope.questions = [{
            useImage: false,
            caption:"",
            image:"",
            data:"",
            result: {
              caption:"",
              image:"",
              data:"",
            },
            answers: [
              {
                image: "",
                data: "",
                text: "",
                correct: "",
              },
              {
                image: "",
                data: "",
                text: "",
                correct: "",
              }
            ]
          }];
          $scope.maxQuestion = 20;
          $scope.minQuestion = 1;
          $scope.showAddQuestion = true;
          $scope.currentNumQuestion = $scope.questions.length;

          $scope.maxAnswer = 6;
          $scope.minAnswer = 2;

          /* Init */

          $scope.init = function(){
            $scope.gameID = GAMEINFO.gameID;
            $scope.gameTitle = GAMEINFO.gameTitle;
            $scope.gameDesc = GAMEINFO.gameDesc;
            $scope.thumbImage = GAMEINFO.thumbImage;

            $scope.questions = GAMEINFO.questions;
            $scope.results = GAMEINFO.results;
          };

          if(GAMEINFO) {
            $scope.init();
          }

          /* Add function */

          $scope.addResult = function(){
            if($scope.results.length < $scope.maxResult)
            {   
              $scope.results.push({title:"", image:"", data:"", range: {begin:"", end:""}});
            }
          };

          $scope.$watchCollection('results + questions' ,function(n){
              for (var e = $scope.questions.length + 1, i = $scope.results.length, s = Math.floor(e / i), o = e % i, r = 0, u = 0; u < i; u++) {
                  var h = r,
                      f = r + s - 1;
                  o > 0 && (f++, o--);
                  r = f + 1;
                  $scope.results[u].range.begin = h;
                  $scope.results[u].range.end = f;
              }
          });

          $scope.addQuestion = function(){
            if($scope.questions.length < $scope.maxQuestion)
            {
              var tmp = {
                useImage: false,
                  caption:"",
                  image:"",
                  data:"",
                  result: {
                    caption:"",
                    image:"",
                    data:"",
                  },
                  answers: [
                    {
                      image: "",
                      data: "",
                      text: "",
                      correct: "",
                    },
                    {
                      image: "",
                      data: "",
                      text: "",
                      correct: "",
                    }
                  ]
              };
              $scope.questions.push(tmp);
            }
          };
          $scope.addAnswer = function(index){
            var tmp = {
                    image: "",
                    data: "",
                    text: "",
                    correct: "",
                  };
            $scope.questions[index].answers.push(tmp);
          };
          /* Remove function */
          $scope.removeResult = function(indexResult){
              angular.forEach($scope.results, function (value, index) {
                  if(indexResult === index)
                  {
                    $scope.results.splice($scope.results.indexOf(value), 1);
                  }
              });
          };
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
          $scope.removeImageResult = function(index){
            $scope.results[index].image = null;
            $scope.results[index].data = null;
          };
          $scope.removeImageQuestion = function(index){
            $scope.questions[index].image = null;
            $scope.questions[index].data = null;
            return;
          };
          $scope.removeImageQuestionResult = function(index){
            $scope.questions[index].result.image = null;
            $scope.questions[index].result.data = null;
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
            $scope.showResultInstruction = false;
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
            $scope.currentPopupResultIndex = null;
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
              case $scope.currentPopupResult:
                angular.forEach($scope.results, function (value, index) {
                  if($scope.currentPopupResultIndex === index)
                  {
                    if($scope.currentProcess === $scope.currentProcessImage)
                    {
                      tmp = $rootScope.rootImageUploaded;
                      tmp.cropped_url = $scope.imageCropped;
                      tmp.dataType = $scope.currentProcessImage;

                      $scope.results[index].data = tmp;
                      $scope.results[index].image = $scope.imageCropped;
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

                      $scope.results[index].data = tmp;
                      $scope.results[index].image = $scope.youtubeThumbnail;
                    }
                  }
                });
                break;
              case $scope.currentPopupQuestion:
                  if($scope.currentProcess === $scope.currentProcessImage)
                  {
                    tmp = $rootScope.rootImageUploaded;
                    tmp.cropped_url = $scope.imageCropped;
                    tmp.dataType = $scope.currentProcessImage;

                    if($scope.uploadResult)
                    {
                      $scope.questions[$scope.currentPopupQuestionIndex].result.data = tmp;
                      $scope.questions[$scope.currentPopupQuestionIndex].result.image = $scope.imageCropped;
                    }
                    else
                    {
                      $scope.questions[$scope.currentPopupQuestionIndex].data = tmp;
                      $scope.questions[$scope.currentPopupQuestionIndex].image = $scope.imageCropped;
                    }
                    $scope.uploadResult = false;
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
                    if($scope.uploadResult)
                    {
                      $scope.questions[$scope.currentPopupQuestionIndex].result.data = tmp;
                      $scope.questions[$scope.currentPopupQuestionIndex].result.image = $scope.youtubeThumbnail;
                    }
                    else
                    {
                      $scope.questions[$scope.currentPopupQuestionIndex].data = tmp;
                      $scope.questions[$scope.currentPopupQuestionIndex].image = $scope.youtubeThumbnail;
                    }
                    $scope.uploadResult = false;
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
          $scope.popUploadQuestion = function(index, uploadResult){
            $scope.uploadResult = uploadResult;

            $scope.currentPopup = $scope.currentPopupQuestion;
            $scope.currentPopupQuestionIndex = index;

            $scope.showLandscapeInstruction = true;
            $scope.minWidth = DIMENSION.questions.width;
            $scope.minHeight = DIMENSION.questions.height;

            $scope.popupOpen();

            var dataQuestion = (uploadResult) ? $scope.questions[index].result.data : $scope.questions[index].data;

            if(dataQuestion)
            {
              $scope.showEditImage = true;
              $scope.editImage = (uploadResult) ? $scope.questions[index].result.image : $scope.questions[index].image;
              var dataType = (uploadResult) ? $scope.questions[index].result.data.dataType : $scope.questions[index].data.dataType;

              switch(dataType){
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
          $scope.popUploadResult = function(index){
            $scope.popupOpen();

            $scope.currentPopup = $scope.currentPopupResult;
            $scope.currentPopupResultIndex = index;

            $scope.showLandscapeInstruction = true;
            $scope.minWidth = DIMENSION.results.width;
            $scope.minHeight = DIMENSION.results.height;

            $scope.showUploadMedia=true;
            $scope.showAlertOverlay=true;
            $scope.showUploadImage=true;
            $scope.showFromURL = true;
            $scope.showFromFile = true;
            $scope.showFromYouTube = true;
            if($scope.results[index].data)
            {
              $scope.showEditImage = true;
              $scope.editImage = $scope.results[index].image;

              switch($scope.results[index].data.dataType){
                case $scope.currentProcessImage:
                  $scope.editText = EDITOR.text.editImage;
                  break;
                case $scope.currentProcessVideo:
                  $scope.editText = EDITOR.text.editVideo;
                  break;
              } // end switch
              
            }// end if
            //$scope.showThumbnailInstruction = true;
          };
          $scope.popeEitResultText = function(index){
            jQuery('#editTextPopup').modal();
            $scope.showTextEditor = true;
            $scope.showAlertOverlay = true;
            $scope.currentPopupResultIndex = index;
            if($scope.results[$scope.currentPopupResultIndex].text)
            {
              $scope.editorText = $scope.results[$scope.currentPopupResultIndex].text;
            }
          };
          $scope.popTextEditorCancel = function(){
            jQuery('#editTextPopup').modal('hide');
            $scope.editorText = null;
            $scope.currentPopupResultIndex = null;

            $scope.showTextEditor = false;
            $scope.showAlertOverlay = false;
          };
          $scope.popTextEditorDone = function(){
            $scope.results[$scope.currentPopupResultIndex].text = $scope.editorText;

            $scope.popTextEditorCancel();
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
            data.content.results = $scope.results;

            data.gameType = GAMETYPE.triviaquiz;
            
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
  ]); // end controller
  //---------------------------
})(jQuery);