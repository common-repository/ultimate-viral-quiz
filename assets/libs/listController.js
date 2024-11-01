(function($) {
  "use strict";
  //---------------------------
  // List Controller
  dbuzzapp.controller('list',['$scope', '$rootScope', '$sce', '$http', '$window',
      function($scope, $rootScope, $sce, $http, $window) {
          $scope.items = [
                {title:"", image:"", data:"", caption:"" },
            ];

          $scope.gameID = null;

          $scope.urlUpload = AJAX.url + '?action=upload_images';

          $scope.currentPopup = null;
          $scope.currentPopupThumbnail = 'thumbnail';
          $scope.currentPopupItem = 'items';

          $scope.currentProcess = null;
          $scope.currentProcessVideo = 'video';
          $scope.currentProcessImage = 'image';
          $scope.minWidth = 0;
          $scope.minHeight = 0;

          $scope.maxItems = 20;
          $scope.minItem = 1;

          /* Init */

          $scope.init = function(){
            $scope.gameID = GAMEINFO.gameID;
            $scope.gameTitle = GAMEINFO.gameTitle;
            $scope.gameDesc = GAMEINFO.gameDesc;
            $scope.thumbImage = GAMEINFO.thumbImage;

            $scope.items = GAMEINFO.items;
            $scope.footerText = GAMEINFO.footerText;
          };

          if(GAMEINFO) {
            $scope.init();
          }

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
            $scope.currentPopup = null;
            //$scope.currentPopupItemIndex = null;
          };
          $scope.popDone = function(){
            var tmp = {};
            switch($scope.currentPopup){
              case $scope.currentPopupThumbnail:
                tmp = $rootScope.rootImageUploaded;
                tmp.cropped_url = $scope.imageCropped;
                $scope.thumbImage = tmp;
                break;
              case $scope.currentPopupItem:
                if($scope.currentProcess === $scope.currentProcessImage)
                {
                  tmp = $rootScope.rootImageUploaded;
                  tmp.cropped_url = $scope.imageCropped;
                  tmp.dataType = $scope.currentProcessImage;

                  $scope.items[$scope.currentPopupItemIndex].data = tmp;
                  $scope.items[$scope.currentPopupItemIndex].image = $scope.imageCropped;
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

                  $scope.items[$scope.currentPopupItemIndex].data = tmp;
                  $scope.items[$scope.currentPopupItemIndex].image = $scope.youtubeThumbnail;
                }
                break;
            }
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
          $scope.popUploadItem = function(index){
            $scope.currentPopup = $scope.currentPopupItem;
            $scope.currentPopupItemIndex = index;

            $scope.showLandscapeInstruction = true;
            $scope.minWidth = DIMENSION.items.width;
            $scope.minHeight = DIMENSION.items.height;

            $scope.popupOpen();
            if($scope.items[index].data)
            {
              $scope.showEditImage = true;
              $scope.editImage = $scope.items[index].image;
                switch($scope.items[index].data.dataType){
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
          /* Add function */
          $scope.addItem = function(){
            if($scope.items.length < $scope.maxItems) {
              $scope.items.push({title:"", image:"", data:"", caption:"" });
            }
          }; 
          /* Remove function */
          $scope.removeItem = function(itemIndex){
            angular.forEach($scope.items, function (value, index) {
              if(itemIndex === index)
              {
                  $scope.items.splice($scope.items.indexOf(value), 1);
              }
            });
          };
          $scope.removeImageItem = function(index){
            $scope.items[index].image = null;
            $scope.items[index].data = null;
            return;
          };
          $scope.removeImageThumbnail = function(){
            $scope.thumbImage = null;
          };
          /* Move function */
          $scope.moveUpItem = function(index){
            if($scope.items[index - 1])
            {
              var tmp = $scope.items[index - 1];
              $scope.items[index - 1] = $scope.items[index];
              $scope.items[index] = tmp;
            }
          };
          $scope.moveDownItem = function(index){
            if($scope.items[index + 1])
            {
              var tmp = $scope.items[index + 1];
              $scope.items[index + 1] = $scope.items[index];
              $scope.items[index] = tmp;
            }
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

            data.content.items = $scope.items;
            data.content.footerText = $scope.footerText;

            data.gameType = GAMETYPE.list;
            
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
          $rootScope.$watch('tags', function(newVal, oldVal){
              $scope.tags = newVal;
          });
          $rootScope.$watch('mytags', function(newVal, oldVal){
              $scope.mytags = newVal;
          });
      }
    ]);

})(jQuery);