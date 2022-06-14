<script src="{{JS}}angular.js"></script>

<script src="{{JS}}plugins/dragdrop/ngDraggable.js"></script>


<script >
    var app = angular.module('academia', ['ngDraggable']);
</script>

@include('common.angular-factory',array('load_module'=> FALSE))

<script>

    app.controller('transferDataController', function($scope, $http, $timeout, httpPreConfig) {

        $scope.from_year_selected = false;
        $scope.false_year_selected = false;


        $scope.default_classes_needed = '{{getSetting('default_sessions_needed','time_table')}}';

        $scope.target_items = [];


            search='';
        $scope.from_year = function() {
          $scope.from_year_selected = true;
        }
        $scope.to_year = function() {
          $scope.to_year_selected = true;
        }
        $scope.transferData = function() {
          console.log($scope.from_year_selected,$scope.to_year_selected)
          if($scope.from_year_selected == true && $scope.to_year_selected == true && $scope.from_academic_year != $scope.to_academic_year ) {
          route = '{{URL_MASTERSETTINGS_TRANSFER_ACADEMIC_COURSE}}';
          data= {
              _method     : 'post',
              '_token'    : httpPreConfig.getToken(),
              'from_year'      : $scope.from_academic_year,
              'to_year': $scope.to_academic_year,
          };


          httpPreConfig.webServiceCallPost(route, data).then(function(result){
                swal("Done!", '{{getPhrase('academic_course_transferred_successfully')}}', "success");
                $scope.transferCourseSubject();

          }, function(error){
            swal({
                    title: "Error!",
                    text: "{{getPhrase('academic_course_transfer_proccess_is_failed')}}",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "{{getPhrase('try_again')}}",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                      $scope.transferData();
                    }
                  });
        });

      } else {
        swal("Error!", '{{getPhrase('cannot_transfer_to_same_year')}}', "error");
      }

        }
        $scope.transferCourseSubject = function() {
          route = '{{URL_MASTERSETTINGS_TRANSFER_COURSE_SUBJECT}}';
          data= {
              _method     : 'post',
              '_token'    : httpPreConfig.getToken(),
              'from_year'      : $scope.from_academic_year,
              'to_year': $scope.to_academic_year,
          };


          httpPreConfig.webServiceCallPost(route, data).then(function(result){
                swal("Done!", '{{getPhrase('course_subjects_transferred_successfully')}}', "success");
                $scope.transferTopics();

          }, function(error){
              swal({
                      title: "Error!",
                      text: "{{getPhrase('course_subjects_transfer_proccess_is_failed')}}",
                      type: "error",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "{{getPhrase('try_again')}}",
                      cancelButtonText: "No, cancel!",
                      closeOnConfirm: true,
                      closeOnCancel: true
                  },
                  function(isConfirm) {
                      if (isConfirm) {
                        $scope.transferCourseSubject();
                      }
                    });
          });
        }

        $scope.transferTopics = function() {
          route = '{{URL_MASTERSETTINGS_TRANSFER_TOPICS}}';
          data= {
              _method     : 'post',
              '_token'    : httpPreConfig.getToken(),
              'from_year'      : $scope.from_academic_year,
              'to_year': $scope.to_academic_year,
          };


          httpPreConfig.webServiceCallPost(route, data).then(function(result){
                swal("Done!", '{{getPhrase('topics_transferred_successfully')}}', "success");
                $scope.transferQuestionBank();

          }, function(error){
              swal({
                      title: "Error!",
                      text: "{{getPhrase('topics_transfer_proccess_is_failed')}}",
                      type: "error",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "{{getPhrase('try_again')}}",
                      cancelButtonText: "No, cancel!",
                      closeOnConfirm: true,
                      closeOnCancel: true
                  },
                  function(isConfirm) {
                      if (isConfirm) {
                        $scope.transferTopics();
                      }
                    });
          });
          }

        $scope.transferQuestionBank = function() {
          route = '{{URL_MASTERSETTINGS_TRANSFER_QUESTIONBANK}}';
          data= {
              _method     : 'post',
              '_token'    : httpPreConfig.getToken(),
              'from_year'      : $scope.from_academic_year,
              'to_year': $scope.to_academic_year,
          };


          httpPreConfig.webServiceCallPost(route, data).then(function(result){
                swal("Done!", '{{getPhrase('Question_Bank_transferred_successfully')}}', "success");
                $scope.transferLmsContents();

          }, function(error){
              swal({
                      title: "Error!",
                      text: "{{getPhrase('Question_bank_transfer_proccess_is_failed')}}",
                      type: "error",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "{{getPhrase('try_again')}}",
                      cancelButtonText: "No, cancel!",
                      closeOnConfirm: true,
                      closeOnCancel: true
                  },
                  function(isConfirm) {
                      if (isConfirm) {
                        $scope.transferQuestionBank();
                      }
                    });


          });
        }

        $scope.transferLmsContents = function() {
          route = '{{URL_MASTERSETTINGS_TRANSFER_LMSCONTENTS}}';
          data= {
              _method     : 'post',
              '_token'    : httpPreConfig.getToken(),
              'from_year'      : $scope.from_academic_year,
              'to_year': $scope.to_academic_year,
          };


          httpPreConfig.webServiceCallPost(route, data).then(function(result){
                swal("Done!", '{{getPhrase('lms_content_transferred_successfully')}}', "success");
                swal("Done!", '{{getPhrase('The_transfer_done_successfuly')}}', "success");
          }, function(error){
              swal({
                      title: "Error!",
                      text: "{{getPhrase('lms_content_transfer_proccess_is_failed')}}",
                      type: "error",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "{{getPhrase('try_again')}}",
                      cancelButtonText: "No, cancel!",
                      closeOnConfirm: true,
                      closeOnCancel: true
                  },
                  function(isConfirm) {
                      if (isConfirm) {
                        $scope.transferLmsContents();
                      }
                    });


          });
        }

    });
</script>
