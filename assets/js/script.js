(function($) {
	"use strict";
	
	$('document').ready(function(){
		var loop = false;
		$( '.uvq-locker-share-link' ).click( function(event) {
			event.preventDefault();
			var url = $(this).prop('href');			
			window.open(url, '_blank', 'resizable=yes,scrollbars=yes,titlebar=yes, width=560, height=443, top=100, left=50');
			
			if ( loop === false ) {
				loop = setInterval(focusCheck, 200, $(this).closest('.uvq-locker-wrapper') );
			}
		});
		
		$('.bbuvq-redirect').each(function(index){
			window.location.href = $(this).data('href');
			return false;
		});
		
		function after_share( $element ) {
			$element.hide();
			$element.siblings('.uvq-locker-content').show(350);
		}
		
		function focusCheck( $element ) {
			if ( document.hasFocus() ) { 
				clearInterval( loop );
				after_share( $element );
			}
		}
		
		var sticky = new Sticky('[data-sticky]', {});
		
		// Validate
		jQuery.validator.addMethod("username", function(value, element) {
		    var username = value.match(/^[a-z0-9\_]{6,40}$/);
		    return username;
		  }, AJAX.msg.invalid_username);

	  	if( $('#signupForm').length > 0 )
	  	{
			$("#signupForm").validate({
			    rules: {
			      nickname: {
			        minlength: 2,
			        maxlength: 100,
			        required: true,
			      },
			      username: {
			        minlength: 6,
			        maxlength: 40,
			        required: true,
			        username: true,
			        remote: {
			          url: AJAX.url + "?action=username_exist",
			          type: "post",
			          data: {
			            username: function() {
			              return $( "#username" ).val();
			            }
			          }
			        },
			      },
			      email: {
			        required: true,
			        email: true,
			        remote: {
			          url: AJAX.url + "?action=email_exist",
			          type: "post",
			          data: {
			            email: function() {
			              return $( "#email" ).val();
			            }
			          }
			        },
			      },
			      password: {
			        minlength: 6,
			        maxlength: 40,
			        required: true
			      },
			      repassword: {
			        minlength: 6,
			        maxlength: 40,
			        required: true,
			        equalTo: password,
			      }
			    },
			    messages: {
			      required: AJAX.msg.required,
			      minlength: AJAX.msg.invalid_minlength,
			      maxlength: AJAX.msg.invalid_maxlength,
			      email: {
			        email: AJAX.msg.invalid_email,
			        remote: AJAX.msg.email_exists
			      },
			      username: {
			        remote: AJAX.msg.username_exists,
			      },
			      repassword: {
			        equalTo: AJAX.msg.pass_do_not_match,
			      }
			    },
			    errorElement: "small",
			    errorClass: "text-danger",
			    errorPlacement: function(error, element) {
			      error.appendTo( element.closest('.form-group') );
			    },
			    highlight: function(element) {
			      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			    },
			    success: function(element) {
			      element.closest('.form-group').removeClass('has-error').addClass('has-success');
			    },
			    submitHandler: function(form) {
			      angular.element(document.getElementById('btn-signup')).scope().signup();
			      return;
			    }
			});
		}
		// Validate - Lost password form
		if( $('#lostPassword').length > 0 )
		{
	  		$("#lostPassword").validate({
			    rules: {
			      email: {
			        required: true,
			        email: true,
			      },
			    },
			    messages: {
			      required: AJAX.msg.required,
			      email: {
			        email: AJAX.msg.invalid_email,
			      },
			    },
			    errorElement: "small",
			    errorClass: "text-danger",
			    errorPlacement: function(error, element) {
			      error.appendTo( element.closest('.form-group') );
			    },
			    highlight: function(element) {
			      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			    },
			    success: function(element) {
			      element.closest('.form-group').removeClass('has-error').addClass('has-success');
			    },
			    submitHandler: function(form) {
			      angular.element(document.getElementById('btn-lostPassword')).scope().lost_password();
			      return;
			    }
		  	});
	  	}
		// Validate - login form
		if( $('#loginForm').length > 0 )
		{
		  	$("#loginForm").validate({
			    rules: {
			      username: {
			        required: true,
			      },
			      password: {
			        required: true,
			      },
			    },
			    messages: {
			      required: AJAX.msg.required,
			    },
			    errorElement: "small",
			    errorClass: "text-danger",
			    errorPlacement: function(error, element) {
			      error.appendTo( element.closest('.form-group') );
			    },
			    highlight: function(element) {
			      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			    },
			    success: function(element) {
			      element.closest('.form-group').removeClass('has-error').addClass('has-success');
			    },
			    submitHandler: function(form) {
			      angular.element(document.getElementById('btn-login')).scope().login();
			      return;
			    }
		  	});
	  	}
		// Validate - change password
		if( $('#changePassword').length > 0 )
		{
		  	$("#changePassword").validate({
			    rules: {
			      oldpassword: {
			        required: true,
			      },
			      password: {
			        required: true,
			        minlength: 6,
			        maxlength: 40,
			      },
			      repassword: {
			        required: true,
			        minlength: 6,
			        maxlength: 40,
			        equalTo: password,
			      },
			    },
			    messages: {
			      required: AJAX.msg.required,
			      minlength: AJAX.msg.invalid_minlength,
			      maxlength: AJAX.msg.invalid_maxlength,
			      repassword: {
			        equalTo: AJAX.msg.pass_do_not_match,
			      }
			    },
			    errorElement: "small",
			    errorClass: "text-danger",
			    errorPlacement: function(error, element) {
			      error.appendTo( element.closest('.form-group') );
			    },
			    highlight: function(element) {
			      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			    },
			    success: function(element) {
			      element.closest('.form-group').removeClass('has-error').addClass('has-success');
			    },
			    submitHandler: function(form) {
			      angular.element(document.getElementById('btn-changepass')).scope().change_password();
			      return;
			    }
			});
		}
		
		if( $('#updateProfile').length > 0 )
		{
			$("#updateProfile").validate({
			    rules: {
			      nickname: {
			        minlength: 2,
			        maxlength: 100,
			        required: true,
			      },
			      username: {
			        minlength: 6,
			        maxlength: 40,
			        required: true,
			        username: true,
			        remote: {
			          url: AJAX.url + "?action=username_exist",
			          type: "post",
			          data: {
			            username: function() {
			              return $( "#username" ).val();
			            },
			            current_username: function() {
			              return $( "#username" ).attr('current-username');
			            }
			          }
			        },
			      },
			      email: {
			        required: true,
			        email: true,
			        remote: {
			          url: AJAX.url + "?action=email_exist",
			          type: "post",
			          data: {
			            email: function() {
			              return $( "#email" ).val();
			            },
			            current_email: function() {
			              return $( "#email" ).attr('current-email');
			            }
			          }
			        },
			      }
			    },
			    messages: {
			      required: AJAX.msg.required,
			      minlength: AJAX.msg.invalid_minlength,
			      maxlength: AJAX.msg.invalid_maxlength,
			      email: {
			        email: AJAX.msg.invalid_email,
			        remote: AJAX.msg.email_exists
			      },
			      username: {
			        remote: AJAX.msg.username_exists,
			      },
			    },
			    errorElement: "small",
			    errorClass: "text-danger",
			    errorPlacement: function(error, element) {
			      error.appendTo( element.closest('.form-group') );
			    },
			    highlight: function(element) {
			      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			    },
			    success: function(element) {
			      element.closest('.form-group').removeClass('has-error').addClass('has-success');
			    },
			    submitHandler: function(form) {
			      angular.element(document.getElementById('btn-update-profile')).scope().update_profile();
			      return;
			    }
			});
		}
		
		$('.btn-delete-buzz').live('click', function(){
            var $self = $(this),
                id = $self.attr('data-id');
                
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this quiz!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(function(willDelete){
                
                if (willDelete) {
                    // $('.bb-ajax-loading').css({display: 'flex'});
                    $.post(AJAX.url, { 'action': 'uvq_delete_quiz', id: id }, function(response) {
                        
                        response = JSON.parse(response);
                        if(typeof response.status != 'undefined') {
                            $.growl({ title: response.title, message: response.message, location: 'br', style: response.status });
                            
                            if(response.status == 'notice') {
                                $self.closest('.item').remove();
                            }
                        }
                        
                        // $('.bb-ajax-loading').css({display: 'none'});
                        
                    });
                }
            });
            
            return;
            
        });
		
	});
})(jQuery);