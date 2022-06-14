<script src="{{JS}}angular.js"></script>
<script src="{{JS}}plugins/dragdrop/ngDraggable.js"></script>

<script >
    var app = angular.module('academia', ['ngDraggable']);
</script>

@include('common.angular-factory',array('load_module'=> FALSE))
<script>

    app.controller('staffAssign', function ($scope, $timeout, httpPreConfig) {

        $scope.staff = [];
        $scope.allocated_staff = [];
        $scope.student = [];
        $scope.allocated_student = [];

        search='';
        /**
         * This method intilize the default data to allocated courses and available courses
         * @param  {[type]} data [description]
         * @return {[type]}      [description]
         */
        $scope.ingAngData = function(data) {
            if(data.staff)
            {
              angular.forEach(data.staff,function(value,key){
                  $scope.staff.push(value);
              });
              angular.forEach(data.allocated_staff,function(value,key){
                  $scope.allocated_staff.push(value);
              });
          } else if(data.student) {
            angular.forEach(data.student,function(value,key){
                $scope.student.push(value);
            });
            angular.forEach(data.allocated_student,function(value,key){
                $scope.allocated_student.push(value);
            });

          }
        }

        /**
         * This event will be generated when the drag has stopped.
         * Current we are not using this event, if required we can use it
         * @param  {[type]} data [description]
         * @param  {[type]} evt  [description]
         * @return {[type]}      [description]
         */
        $scope.onDragComplete=function(data,evt){
            /**
             * Write the necessory code for it
             */
        }

        /**
         * This event is triggered when an item is dropped on droppable div
         * @param  {[type]} data [description]
         * @param  {[type]} evt  [description]
         * @return {[type]}      [description]
         */
        $scope.onDropComplete=function(data, evt, key){
          if(key == 'staff') {
            res = httpPreConfig.findIndexInData($scope.allocated_staff, 'id', data.id);
            if(res==-1){
              $scope.allocated_staff.push(data);
              $('#allocated_staff-'+data.id).addClass('animated {{ANIMATION_ADD}}');
            }
            else
                alertify.error('{{getPhrase('already_item_available')}}');
          } else if(key == 'student') {
            console.log("here")
            res = httpPreConfig.findIndexInData($scope.allocated_student, 'id', data.id);
            if(res==-1){
              $scope.allocated_student.push(data);
              $('#allocated_student-'+data.id).addClass('animated {{ANIMATION_ADD}}');
            }
            else
            alertify.error('{{getPhrase('already_item_available')}}');
          }
        }



        /**
         * This method removes specific item passed by it
         * @param  {[type]} item [description]
         * @return {[type]}      [description]
         */
        $scope.removeItem = function(item, Id, key) {
            httpPreConfig.showConfirmation().then(function(result){
                if(result==1){
                    if(key == 'staff') {
                      route = '{{URL_MASTERSETTINGS_STAFF_SUPERVISOR_STATUS}}';
                      data= {   _method: 'post',
                          '_token':httpPreConfig.getToken(),
                          'supervisorId': Id,
                          'staffId': item.id,
                      };
                  } else if (key == 'student') {
                    route = '{{URL_MASTERSETTINGS_STUDENT_SECONDARY_PARENT_STATUS}}';
                    data= {   _method: 'post',
                        '_token':httpPreConfig.getToken(),
                        'secondaryParentId': Id,
                        'studentId': item.id,
                    };
                  }
                    httpPreConfig.webServiceCallPost(route, data).then(function(result){
                        result = result.data;
                        if(result==0) {
                          if (key == 'staff')
                          {
                            $('#allocated_staff-'+item.id).addClass('animated {{ANIMATION_REMOVE}}');

                            $timeout( function(){
                                var index = $scope.allocated_staff.indexOf(item);
                                index = httpPreConfig.findIndexInData($scope.allocated_staff, 'id', item.id);
                                $scope.allocated_staff.splice(index, 1);
                                alertify.success('{{getPhrase('item_removed_successfully')}}');
                            }, 500);
                        } else if(key == 'student') {
                          $('#allocated_student-'+item.id).addClass('animated {{ANIMATION_REMOVE}}');

                          $timeout( function(){
                              var index = $scope.allocated_student.indexOf(item);
                              index = httpPreConfig.findIndexInData($scope.allocated_student, 'id', item.id);
                              $scope.allocated_student.splice(index, 1);
                              alertify.success('{{getPhrase('item_removed_successfully')}}');
                          }, 500);
                        }
                      }
                        else{
                            alertify.error('{{getPhrase('cannot_remove_this_item_as_it_is_in_use')}}');
                        }
                    });


                }


            });


        }

        /**
         * This method is used to filter the items on specific condition
         * @param  {[type]} item   [description]
         * @param  {[type]} source [description]
         * @return {[type]}        [description]
         */
        $scope.filterItems = function(item, source){
            newList = [];
            source.forEach(function(value,key){
                if(value.id != item.id)
                    newList.push(value);
            });
            return newList;
        }

    })
</script>
