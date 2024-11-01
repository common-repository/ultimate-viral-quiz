function uvqAddQueryString(queryString, value) {
    var url = window.location.href;
    var isQuestionMarkPresent = url && url.indexOf('?') !== -1,
        isExists1 = url && url.indexOf('?'+queryString) !== -1,
        isExists2 = url && url.indexOf('&'+queryString) !== -1,
        separator = '';
    if (queryString && !isExists1 && !isExists2) {
        separator = isQuestionMarkPresent ? '&' : '?';
        window.history.pushState( null, null, separator + queryString+"="+value);
    }

    return url;
}

var dbuzzapp;
(function($) {
  "use strict";
  //---------------------------
  dbuzzapp = angular.module('dbuzzapp', ['angularFileUpload', 'uiSwitch', 'ngAnimate']);
  //---------------------------
  dbuzzapp.controller('buzzController',['$scope', '$rootScope', '$sce', '$http',
      function($scope, $rootScope, $sce, $http) {
        $scope.showLoading = false;
        $scope.default_url = URLS.transparent_image;
        $rootScope.$watch('showLoading', function(newVal, oldVal){
            $scope.showLoading = newVal;
          });

        $scope.scrollTo = function(idHTML, addition){
          jQuery('html, body').animate({
              scrollTop: jQuery(idHTML).offset().top + addition
          }, 800);
        };

        $scope.calcAnswerBox = function(quatity, fullWidth, answerImage){
          if(!answerImage && fullWidth) {
            return "col-xs-12";
          }
          else if(quatity <= 2) {
            return "col-xs-6";
          }
          else if(quatity === 3 || quatity === 5 || quatity === 6) {
            return "col-xs-4";
          }
          else if(quatity === 4 || quatity === 7 || quatity === 8) {
            return "col-xs-3";
          }
        };

      } // end function
  ]); // end controller
  dbuzzapp.controller('quizDefault',['$scope', '$rootScope', '$sce', '$http', '$timeout',
    function($scope, $rootScope, $sce, $http, $timeout) {
      
    }]);
  //---------------------------
  dbuzzapp.controller('userController',['$scope', '$rootScope', '$upload', '$sce', '$http', '$window',
      function($scope, $rootScope, $upload, $sce, $http, $window) {
        $scope.init = function(){
            $scope.user = USER_INFOMARION;
        };
        if(USER_INFOMARION)
        {
          $scope.init();
        }
        /* Popup function */
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

            // $scope.youtubeID = null;
            // $scope.youtubeURL = null;
            // $scope.youtubeEmbedString = null;
            // $scope.youtubeEmbed = null;
            // $scope.youtubeThumbnail = null;
            // $scope.youtubeStartTime = null;
            // $scope.youtubeEndTime = null;

            $rootScope.currentProcess = null;
            $scope.currentProcess = null;
            $scope.currentPopupResultIndex = null;
            $scope.currentPopup = null;
            $scope.currentPopupQuestionIndex = null;
            $scope.currentPopupAnswerIndex = null;
          };
        $scope.popupOpen = function(){
          $scope.popCancel();
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
        $scope.popUploadAvatar = function(){
          $scope.popupOpen();
          $scope.showFromYouTube = false;
          $scope.showAvatarInstruction = true;
          $scope.currentPopup = 'avatar';

          $scope.minWidth = DIMENSION.avatar.width;
          $scope.minHeight = DIMENSION.avatar.height;

          if($scope.user.avatar && $scope.user.avatar.data !== 'default')
          {
            $scope.editText = EDITOR.text.editImage;
            $scope.showEditImage = true;
            $scope.editImage = $scope.user.avatar.image;
          }
        };
        $scope.popDone = function(){
            var tmp = {};
            tmp = $rootScope.rootImageUploaded;
            tmp.image = $scope.imageCropped;
            $scope.user.avatar = tmp;
            $scope.popCancel();
        };
        // Save user's information
        $scope.update_profile = function(){
          $rootScope.showOverlayPage = true;
          var data = $scope.user;
          $scope.showLoading = true;
          $http.post(AJAX.url + "?action=save_user_information" , data).
            success(function(data, status, headers, config) {
              alert(data.msg);
              if(data.status === 'ok') {
                jQuery('.has-success').removeClass('has-success');
                if(data.change_username === 'true') {
                  $window.location.reload();
                }
              }
              $scope.showLoading = false;
              $rootScope.showOverlayPage = false;
            }).
            error(function(data, status, headers, config) {
              alert(AJAX.message.undefine);
              $rootScope.showOverlayPage = false;
              return;
            });
        };
        // Update user's password
        $scope.change_password = function(){
          var data = $scope.user;
          $rootScope.showOverlayPage = true;
          $http.post(AJAX.url + "?action=change_password" , data).
            success(function(data, status, headers, config) {
              if(data.status === 'ok')
              {
                $scope.user = '';
                jQuery('.has-success').removeClass('has-success');
              }
              $rootScope.showOverlayPage = false;
              alert(data.msg);
            }).
            error(function(data, status, headers, config) {
              alert(AJAX.message.undefine);
              $rootScope.showOverlayPage = false;
              return;
            });
        };
        // Sigup
        $scope.signup = function(){
          var data = $scope.user;
          $rootScope.showOverlayPage = true;
          $http.post(AJAX.url + "?action=user_signup" , data).
              success(function(data, status, headers, config) {
                if(data.status === 'ok')
                {
                  alert(data.msg);
                  $window.location.href = AJAX.url_callback;
                }
                else
                {
                  alert(data.msg);
                  $rootScope.showOverlayPage = false;
                }
              }).
              error(function(data, status, headers, config) {
                alert(AJAX.message.undefine);
                $rootScope.showOverlayPage = false;
                return;
              });
        };
        // Lostpassword
        $scope.lost_password = function(){
          $rootScope.showOverlayPage = true;
          var data = $scope.user;
          $http.post(AJAX.url + "?action=lost_password" , data).
              success(function(data, status, headers, config) {
                if(data.status === 'ok')
                {
                  jQuery('.has-success').removeClass('has-success');
                  $scope.user.email = '';
                  alert(data.msg);
                }
                else {
                  alert(data.msg);
                }
                $rootScope.showOverlayPage = false;
              }).
              error(function(data, status, headers, config) {
                alert(AJAX.message.undefine);
                $rootScope.showOverlayPage = false;
                return;
              });
        };
        // Login
        $scope.login = function(){
          var data = $scope.user;
          $rootScope.showOverlayPage = true;
          $http.post(AJAX.url + "?action=user_login" , data).
              success(function(data, status, headers, config) {
                if(data.status === 'ok')
                {
                  $window.location.href = AJAX.url_callback;
                }
                else
                {
                  alert(data.msg);
                  $rootScope.showOverlayPage = false;
                }
              }).
              error(function(data, status, headers, config) {
                $rootScope.showOverlayPage = false;
                alert(AJAX.message.undefine);
                return;
              });
        };
        $rootScope.$watch('rootAvatarUploaded', function(newVal, oldVal){
          if(newVal) {
            $scope.user.avatar = newVal.url;
          }
        });
        $rootScope.$watch('showUploading', function(newVal, oldVal){
          $scope.showUploading = newVal;
        });

      } // end function
  ]); // end controller
  //---------------------------
  dbuzzapp.controller('buzzDefault',['$scope', '$rootScope', '$sce', '$http', '$window',
    function($scope, $rootScope, $sce, $http, $window) {
      // Set type
      $scope.setType = function(){
        $rootScope.showOverlayPage = true;
        $scope.buzz.gameID = GAMEINFO.gameID;
        var data = $scope.buzz;
        $scope.showLoading = true;
        $http.post(AJAX.url + "?action=set_type_buzz" , data).
          success(function(data, status, headers, config) {
            alert(data.msg);
            if(data.status === 'ok') {
              $window.location.reload();
            }
            $scope.showLoading = false;
            $rootScope.showOverlayPage = false;
          }).
          error(function(data, status, headers, config) {
            alert(AJAX.message.undefine);
            $rootScope.showOverlayPage = false;
            return;
          });
      };
    }]);// end controller

  //---------------------------
  // Right col Controller
  dbuzzapp.controller('rightcolController', [ '$rootScope', '$scope' , '$upload', '$http', '$sce',
      function($rootScope, $scope, $upload, $http, $sce) {
        $scope.tags = null;
        $scope.category = 0;
        $scope.permission = 1;
        $scope.mytags = '';

        $scope.init = function(){
          $scope.tags = GAME.tags;

          if(GAMEINFO)
          {
            var gametags = GAMEINFO.tags;
            $scope.permission = GAMEINFO.permission;
            $scope.category = GAMEINFO.category;

            angular.forEach(gametags, function (gametag, indexGameTag) {
              angular.forEach($scope.tags, function (tag, indexTag) {
                if(gametag.name === tag.name)
                {
                  $scope.tags[indexTag].isCheck = true;
                  gametags[indexGameTag] = null;
                }
              });
            });
            var count = 0;
            angular.forEach(gametags, function (tag, index) {
              if(tag!=null) {
                jQuery('#myTags').pillbox('addItems', index, [{ text: tag.name, value: tag.name}]);
              }
            });
            
          }
        };
        $scope.init();
        $scope.tagChecked = function(index){
          var countSelected = 0;
          angular.forEach($scope.tags, function (value, index) {
              if(value.isCheck) {
                countSelected++;
              }
          });
          if(countSelected > $scope.maxSelectTags) {
            $scope.tags[index].isCheck = false;
          }
        };
        $scope.$watch('permission', function(newVal, oldVal){
            $rootScope.permission = newVal;
        });
        $scope.$watch('category', function(newVal, oldVal){
            $rootScope.category = newVal;
        });
        $scope.$watch('tags', function(newVal, oldVal){
            $rootScope.tags = newVal;
        });
        $scope.$watch('mytags', function(newVal, oldVal){
            $rootScope.mytags = newVal;
        });
      }
    ]);
  //---------------------------
  // Upload Controller
  dbuzzapp.controller('uploadController', [ '$rootScope', '$scope' , '$upload', '$http', '$sce', '$timeout',
      function($rootScope, $scope, $upload, $http, $sce, $timeout) {
          $rootScope.rootImageUploaded = null;
          $scope.currentPopupAvatar = 'avatar';
          $scope.urlUpload = AJAX.url + '?action=upload_images';
          $scope.imageUploaded = null;
          $rootScope.uploadFailed = false;

          $scope.imageCropped = null;
          $scope.cropOn = false;

          /** Open action */
          $scope.editImageAction = function(){
            var tmp = null;
            switch($scope.currentPopup){
              case $scope.currentPopupAvatar:
                $scope.currentProcess = $scope.currentProcessImage;
                  $rootScope.rootImageUploaded = $scope.user.avatar;
                  $scope.destroyCrop = true;
                  break;
              case $scope.currentPopupThumbnail:
                $scope.currentProcess = $scope.currentProcessImage;
                $rootScope.rootImageUploaded = $scope.thumbImage;
                $scope.destroyCrop = true;
              break;
              case $scope.currentPopupResult:
                if(!tmp) {
                  tmp = $scope.results[$scope.currentPopupResultIndex];
                }
              case $scope.currentPopupQuestion:
                if(!tmp) {
                  tmp = $scope.questions[$scope.currentPopupQuestionIndex];
                }
              case $scope.currentPopupItem:
                if(!tmp) {
                  tmp = $scope.items[$scope.currentPopupItemIndex];
                }
              case $scope.currentPopupAnswer:
                if(!tmp) {
                  tmp = $scope.questions[$scope.currentPopupQuestionIndex].answers[$scope.currentPopupAnswerIndex];
                }
              default:
                if(tmp.data.dataType === $scope.currentProcessVideo)
                {
                  $scope.currentProcess = $scope.currentProcessVideo;
                  $scope.editVideo(tmp);
                }
                else if(tmp.data.dataType === $scope.currentProcessImage)
                {
                  $scope.currentProcess = $scope.currentProcessImage;
                  $rootScope.rootImageUploaded = tmp.data;
                  $scope.destroyCrop = true;
                }
                break;
            }
            
          };
          $scope.editVideo = function(video){
            $scope.youtubeID = video.data.youtubeID;
            $scope.youtubeEmbed = video.data.youtubeEmbed;
            $scope.youtubeEmbedString = video.data.youtubeEmbedString;
            $scope.youtubeURL = video.data.youtubeURL;
            $scope.youtubeStartTime = video.data.youtubeStartTime;
            $scope.youtubeEndTime = video.data.youtubeEndTime;
            $scope.youtubeThumbnail = video.image;

            $scope.showEditVideoWrapper = true;
            $scope.showUploadImage = false;
            $scope.showMainUpload = false;
            $scope.showDoneButton = true;
          };
          $scope.formTypeImageUrl = function(){
            $scope.showTypeImageUrl = true;
            $scope.showMainUpload = false;
            $scope.currentProcess = $scope.currentProcessImage;
            $scope.showUploadImageOvelayLover = true;
          };
          $scope.fromYoutubeVideo = function(){
            $scope.showUploadVideo = true;
            $scope.showMainUpload = false;
            $scope.currentProcess = $scope.currentProcessVideo;
            $scope.showUploadImageOvelayLover = true;
          };

          /** Process */
          $scope.setYoutubeStartTime = function(){
            if($scope.youtubeCurrentTime > $scope.youtubeEndTime) {
              $scope.youtubeEndTime = $scope.youtubeDuration;
            }
            $scope.youtubeStartTime = Math.floor($scope.youtubeCurrentTime);
          };
          $scope.setYoutubeEndTime = function(){
            if($scope.youtubeCurrentTime) {
              $scope.youtubeEndTime = Math.floor($scope.youtubeCurrentTime);
            }
          };
          $scope.processUploadVideo = function(){
            var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
            var urlChecked = ($scope.youtubeURL)?($scope.youtubeURL.match(p)) ? RegExp.$1 : false: false;
            var idChecked = ($scope.youtubeURL)?($scope.youtubeURL.length === 11) ? true : false: false;

            if(!urlChecked && !idChecked)
            {
              alert(AJAX.message.url_youtube_invalid);
              return;
            }

            if(urlChecked) {
              $scope.youtubeID = $scope.youtubeURL.split('v=')[1].split('&')[0];
            }
            else if(idChecked) {
              $scope.youtubeID = $scope.youtubeURL;
            }

            $scope.youtubeEmbed = $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + $scope.youtubeID);
            $scope.youtubeEmbedString = "https://www.youtube.com/embed/" + $scope.youtubeID;
            $scope.youtubeThumbnail = "//i3.ytimg.com/vi/"+ $scope.youtubeID +"/hqdefault.jpg";

            $scope.showUploadImageOvelay = true;

            $scope.youtubeEndTime = $scope.youtubeStartTime = $scope.youtubeCurrentTime = $scope.youtubeDuration = 0;

            $scope.$watch('youtubeDuration', function(newVal, oldVal){
              if(newVal)
              {
                $timeout(function(){
                  $scope.showUploadImageOvelay = false;
                  $scope.closePopUploadVideo();
                  $scope.showEditVideoWrapper = true;
                  $scope.showUploadImage = false;
                  $scope.showDoneButton = true;
                  $scope.showMainUpload = false;
                }, 2000);
              }
            });
          };
          $scope.uploadByUrlImage = function(){

            var p = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/;
            var url = ($scope.imageByTypeUrl)?($scope.imageByTypeUrl.match(p)) ? true : false: false;
            if(!url)
            {
              alert(AJAX.message.url_image_invalid);
              return;
            }
            if($scope.imageByTypeUrl)
            {
              $rootScope.currentProcess = $scope.currentProcessImage;
              $rootScope.showUploadImageOvelay = true;
              var uploadByUrlImage = {
                 method: 'POST',
                 url: $scope.urlUpload,
                 data: {imgurl: $scope.imageByTypeUrl, type: $scope.currentPopup},
                };
              $http(uploadByUrlImage).
                success(function(response, status, headers, config) {
                  if(response.status === 'ok') {
                    $rootScope.rootImageUploaded = response;
                  }
                  else if(response.status === 'error')
                  {
                    alert(response.message);
                    $rootScope.uploadFailed = true;
                    return false;
                  }
                  else
                  {
                    alert('Error: ' + response.message);
                    $rootScope.uploadFailed = true;
                  }
                }).
                error(function(data, status, headers, config) {
                  //console.log('error', data);
                  alert("Error: Can't upload image!");
                });
            }
          };

          $scope.uploadImage = function($files) {
              $rootScope.currentProcess = $scope.currentProcessImage;
              for (var i=0; i<$files.length; i++) {
                  var file = $files[i];
                  $scope.upload = $upload.upload({
                      url: $scope.urlUpload,
                      method: 'POST', // PUT OR POST
                      file: file,
                      data: {type: $scope.currentPopup}
                  }).progress(function(evt) {
                      $rootScope.showUploadImageOvelay = true;
                  }).success(function(response, status, headers, config) {
                      if(response.status === 'ok') {
                        $rootScope.rootImageUploaded = response;
                      }
                      else if(response.status === 'error')
                      {
                        alert(response.message);
                        $rootScope.uploadFailed = true;
                        return false;
                      }
                      else
                      {
                        alert('Error: ' + response.message);
                        $rootScope.uploadFailed = true;
                      }

                  }).error(function(response, status, headers, config) {
                      // do something
                  }).xhr(function(xhr) {
                      // to abort, use ng-click like: ng-click="abort()"
                      $scope.abort = function() {
                          xhr.abort();
                      };
                  });
              } // end for
          }; // end UploadImage

          /** Close action */
          $scope.closePopTypeImageUrl = function(){
            $scope.showTypeImageUrl = false;
            $scope.showMainUpload = true;
            $scope.showUploadImageOvelayLover = false;
          };

          $scope.closePopUploadVideo = function(){
            $scope.showUploadVideo = false;
            $scope.showMainUpload = true;
            $scope.showUploadImageOvelayLover = false;
          };

          /** Watch */
          $rootScope.$watch('rootImageUploaded', function(newVal, oldVal){
              $scope.imageUploaded = newVal;
          });

          $rootScope.$watch('currentProcess', function(newVal, oldVal){
              $scope.currentProcess = newVal;
          });

          $scope.$watch('uploadFailed', function(newVal, oldVal){
            if(newVal)
            {
              $scope.showUploadImageOvelay = false;
            }
          });
          $scope.$watch('cropOn', function(newVal, oldVal){
            if(newVal)
              {
                $scope.showUploadImage = false;
                $scope.showCropImage = true;
                $scope.showMainUpload = false;
                $scope.showDoneButton = true;
                $scope.showUploadImageOvelay = false;
                $scope.showUploadImageOvelayLover = false;
                $scope.showTypeImageUrl = false;
              }
          });
          $rootScope.$watch('showUploadImageOvelay', function(newVal, oldVal){
              $scope.showUploadImageOvelay = newVal;
          });

      } // end function
  ]); // end controller
  //---------------------------
  // Directive Crop Image
  dbuzzapp.directive('ngJcrop', function($timeout){
      return {
        restrict: 'ACE',
        controller:'uploadController',
        template: function(){
            return '<img class="place_image_here" src=\"{{ imageUploaded.url }}\" />';
        },
        link: function(scope, element){
          scope.$watch('destroyCrop', function(newVal, oldVal){
              if(newVal)
              {
                var JcropAPI = element.find('img').data('Jcrop');
                JcropAPI.destroy();
              }
          });
          scope.$watch('imageUploaded' ,function(newVal , oldVal){
            if(!scope.imageUploaded)
            {
              return;
            }
            var   img_w, img_h,
                  max_w, max_h,
                  crop_x, crop_y,
                  crop_ratio, img_ratio, max_ratio,
                  min_w, min_h,
                  origin_w, origin_h,
                  browser_width, browser_ratio, ratio;

            origin_w = scope.imageUploaded.width;
            origin_h = scope.imageUploaded.height;

            img_w = scope.imageUploaded.width;
            img_h = scope.imageUploaded.height;

            browser_width = jQuery(document).width();
            
            max_w = 560;
            max_h = 355;

            if(browser_width < max_w)
            {
              browser_ratio = max_w / (browser_width - 60);
              max_w = browser_width - 60;
              max_h = max_h / browser_ratio;
            }
            min_w = scope.minWidth;//392;
            min_h = scope.minHeight;//294;

            ratio = min_w/ min_h;

            crop_x = 0;
            crop_y = 0;

            element.find('img').Jcrop({
              minSize: [min_w,min_h],
              shape: true,
              boxWidth: max_w,
              boxHeight: max_h,
              aspectRatio: ratio,//1.33,
              setSelect: [ min_w, min_h, crop_x, crop_y ],
              trueSize: [origin_w,origin_h],
              onSelect: function () {
                var img_url;
                if(scope.imageUploaded.upload_type === OPTIONS.UVQ_UPLOAD_LOCAL)
                {
                  img_url = URLS.image_url + "?h="
                              + Math.round(arguments[0].h) 
                              + "&w="+Math.round(arguments[0].w)
                              + "&x="+Math.round(arguments[0].x)
                              + "&y="+Math.round(arguments[0].y)
                              + "&id="
                              + scope.imageUploaded.public_id;
                }
                else
                {
                  img_url = URLS.cloudinary_url + "c_crop,h_"
                              + Math.round(arguments[0].h) 
                              + ",w_"+Math.round(arguments[0].w)
                              + ",x_"+Math.round(arguments[0].x)
                              + ",y_"+Math.round(arguments[0].y)
                              + "/v"
                              + scope.imageUploaded.version
                              + "/"
                              + scope.imageUploaded.public_id +"." + scope.imageUploaded.format;
                }
                $timeout(function(){
                  scope.$apply(function(){
                    scope.cropOn = true;
                    scope.destroyCrop = false;
                    scope.imageCropped = img_url;
                  });
                });
              }, 
              onChange: function () {
                var img_url;
                if(scope.imageUploaded.upload_type === OPTIONS.UVQ_UPLOAD_LOCAL)
                {
                  img_url = URLS.image_url + "?h="
                              + Math.round(arguments[0].h) 
                              + "&w="+Math.round(arguments[0].w)
                              + "&x="+Math.round(arguments[0].x)
                              + "&y="+Math.round(arguments[0].y)
                              + "&id="
                              + scope.imageUploaded.public_id;
                }
                else
                {
                  img_url = URLS.cloudinary_url + "c_crop,h_"
                              + Math.round(arguments[0].h) 
                              + ",w_"+Math.round(arguments[0].w)
                              + ",x_"+Math.round(arguments[0].x)
                              + ",y_"+Math.round(arguments[0].y)
                              + "/v"
                              + scope.imageUploaded.version
                              + "/"
                              + scope.imageUploaded.public_id +"." + scope.imageUploaded.format;
                }
                $timeout(function(){
                  scope.$apply(function(){
                    scope.imageCropped = img_url;
                  });
                });
              }
            }); // end Crop image
          }); // end scope
        } // end link
      }; // end return
    });
  //---------------------------
  // Directive youtube video
  dbuzzapp.directive('youtube', function($window, $timeout, $interval, $http) {
    return {
      restrict: "ACE",
      controller:'uploadController',
      template: '<div class="embed-responsive-item"></div><div class="ng-hide"></div>',

      link: function(scope, element) {
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;

        $window.onYouTubeIframeAPIReady = function() {
          // For play and mark time
          player = new YT.Player(element.children()[0], {
            videoId: scope.youtubeID,
            width: '100%',
            height: '100%',
            events: {
              'onReady': function(){
                scope.youtubeStartTime = 0;
                scope.youtubeEndTime = 0;
                $interval(function(){
                  $timeout(function ytReady() {
                    scope.$apply(function(){
                        scope.youtubeCurrentTime = player.getCurrentTime();
                    });
                  });
                }, 1000);
              },
            }
          });
        }; // end window.onYouTubeIframe

        function getVideoTime(){
          if(!scope.youtubeDuration) {
            $timeout(function() {
              scope.$apply(function(){
                  scope.youtubeEndTime = player.getDuration();
                  scope.youtubeDuration = player.getDuration();
                  scope.youtubeCurrentTime = player.getCurrentTime();
              });
            });
            if(!scope.youtubeDuration) {
              setTimeout(getVideoTime, 1000);
            }
          }
          else {
            player.clearVideo();
            player.cueVideoById(scope.youtubeID);
            player.unMute();
          } // end else
        }

        scope.$watch('youtubeID', function(newValue, oldValue) {
          if (newValue !== oldValue && newValue) {
            player.cueVideoById(scope.youtubeID);
            player.mute();
            player.playVideo();
            getVideoTime();
          }
          else if (newValue !== oldValue && newValue === null) {
            player.pauseVideo();
          }
        });

      }, // end link
    };
  });
  //---------------------------
  // Directive youtube video
  dbuzzapp.directive('youtubeSrc', function($sce) {
    return {
      link: function render(scope, element, attrs) {
        attrs.$observe('youtubeSrc', function (value) {
          angular.element(element).attr("src", attrs.youtubeSrc);
        });
      }
    };
  });
  //---------------------------
  // Directive image
  dbuzzapp.directive('imageSrc', function($sce) {
    return {
      link: function render(scope, element, attrs) {
        attrs.$observe('imageSrc', function (value) {
          angular.element(element).attr("src", attrs.imageSrc);
        });
      }
    };
  });
  //---------------------------
  // Filter numberFormatA
  dbuzzapp.filter('numberFormatA', function() {
      return function(number) {
        number = number + "";
        var no;
        if (isNaN(number) || number.length <= 3) {
          return number;
        }
        if(number.length === 4) {
          return number.substr(0, 1) + "k";
        }
        if(number.length <= 6) 
        {
          no = 6 - number.length;
          return number.substr(0, 3 - no) + "k";
        } 
        if(number.length <= 9)
        {
          no = 9 - number.length;
          return number.substr(0, 3 - no) + "k";
        } 
      };
  });
  jQuery(document).ready(function(){
    jQuery("#uploadPopup").on("hidden.bs.modal", function(){
      angular.element('#uploadPopup').scope().popCancel();
    });
  })
})(jQuery);