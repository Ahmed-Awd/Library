 @if(!isset($isLoaded))
   <script src="{{JS}}angular.js"></script>
    <script src="{{JS}}angular-messages.js"></script>
@endif

 <script src="{{JS}}satellizer.min.js"></script>
    <script >

    @if(!isset($isLoaded))
    	 app = angular.module('academia', ['ngMessages','satellizer']);
    @endif


<?php
        $social = App\Settings::getSocialKeys();
 ?>


        app.directive('stringToNumber', function() {
          return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
              ngModel.$parsers.push(function(value) {
                return '' + value;
              });
              ngModel.$formatters.push(function(value) {
                return parseFloat(value);
              });
            }
          };
        });
    	app.directive('input', function ($parse) {
		  return {
		    restrict: 'E',
		    multiElement: true,
		    require: '?ngModel',
		    link: function (scope, element, attrs) {
			 e = element[0];
 
            if (attrs.ngModel && attrs.value) {
		        $parse(attrs.ngModel).assign(scope, attrs.value);
		      }
		    }
		  };
		});

    	app.directive('textarea', function ($parse) {
		  return {
		    restrict: 'E',
		    multiElement: true,
		    require: '?ngModel',
		    link: function (scope, element, attrs) {
		    	e = element[0];
			 // console.log(element[0].value);
		      if (attrs.ngModel && e.value) {
		        $parse(attrs.ngModel).assign(scope, e.value);
		      }
		    }
		  };
		});
    	app.directive('select', function ($parse) {
		  return {
		    restrict: 'E',
		    multiElement: true,
		    require: '?ngModel',
		    link: function (scope, element, attrs) {
		    	e = element[0];
		    	selectedValue = e.options[e.selectedIndex].value;
		    	// console.log(selectedValue);
		      if (attrs.ngModel && selectedValue) {
		        $parse(attrs.ngModel).assign(scope, selectedValue);
		      }
		    }
		  };
		});

/**
 * PASSWORD AND CONFIRM PASSWORD FIELDS VALIDATION DIRECTIVE
 * @return {[type]} [description]
 */
		var compareTo = function() {
    return {
        require: "ngModel",
        scope: {
            otherModelValue: "=compareTo"
        },
        link: function(scope, element, attributes, ngModel) {

            ngModel.$validators.compareTo = function(modelValue) {
                return modelValue == scope.otherModelValue;
            };

            scope.$watch("otherModelValue", function() {
                ngModel.$validate();
            	});
        	}
    	};
	};

app.directive("compareTo", compareTo);

/**
 * FILE VALIDATION DIRECTIVES BEFORE UPLOAD
 */

var validImage = function($rootScope) {
	var validFormats = ['jpg', 'png', 'jpeg'];
 return {
        require: "ngModel",
        scope: {
            otherModelValue: "=validImage"
        },
        link: function(scope, element, attributes, ngModel) {
        	$rootScope.isImageValid = 'true';
        	
            ngModel.$validators.validImage = function(modelValue) {

               element.on('change', function () {
               	
               	
                 file_size = this.files[0].size;
                
                 valid_size = true;
                
                   var value = element.val(),
                       ext = value.substring(value.lastIndexOf('.') + 1).toLowerCase();   
                    
                     $rootScope.isImageValid = validFormats.indexOf(ext) !== -1;
                     $rootScope.$apply();
                    
                    if(file_size > 2000000){
                    	 
                 		return false;
                 	}
                 	else{
                 		 return validFormats.indexOf(ext) !== -1 ;
                 	}
                });
            };
          
        	}
    	};
	};
app.directive("validImage", validImage);

    app.config(function ($authProvider, $httpProvider,$locationProvider){
        $authProvider.loginUrl = 'http://localhost/cast/api/login';
        $authProvider.signupUrl = 'http://localhost/cast/api/signup';
        // google
        {{--$authProvider.google({--}}
            {{--url: '{{PREFIX}}auth/google',--}}
            {{--clientId: '{{$social['google_client_id']->value}}',--}}
            {{--redirectUri: "{{$social['google_redirect_url']->value}}",--}}
            {{--scope:['profile','email'],--}}
        {{--});--}}
         $authProvider.google({
            url: '{{PREFIX}}auth/google',
            clientId: '{{$social['google_client_id']->value}}',
            redirectUri: "{{$social['google_redirect_url']->value}}",
            scope:['profile','email'],
        });

        //facebook
        {{--$authProvider.facebook({--}}
            {{--clientId: '{{$social['facebook_client_id']->value}}',--}}
            {{--url: '{{PREFIX}}auth/facebook',--}}
            {{--redirectUri: "{{$social['facebook_redirect_url']->value}}",--}}
            {{--scope: ['email'],--}}
        {{--});--}}

                $authProvider.facebook({
            clientId: '{{$social['facebook_client_id']->value}}',
            url: '{{PREFIX}}auth/facebook',
            redirectUri: "{{$social['facebook_redirect_url']->value}}",
            scope: ['email'],
        });

    });


    app.controller('login',['$scope','$http','$rootScope','$auth','$location',function($scope,$http,$rootScope,$auth,$location) {

        $("#warn_him").css('display','block');
		$scope.authenticate = function(provider) {
		    console.log(provider);

			$auth.authenticate(provider).then(function(response) {
				if(response.data.state == 'success'){
                    location.reload();
                }
                else{

                    $scope.warning = response.data.message;
                }
			});
		};
    }]);

    </script>