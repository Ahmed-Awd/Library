
@include('common.angular-factory')

<script>
    app.controller('TabController', function ($scope, $http, httpPreConfig,$location)
    {
        @if(isset($admin))
                @include('common.js-script-year-selection',array('user_slug'=>$user->slug,'user_id'=>$user->id))
           @else
                {{--@include('common.js-script-year-selection',array('user_slug'=>$user->slug))--}}
                @endif

            $scope.exam_categories = [];
        $scope.exam_list       = [];
        $scope.show_div = false;


        $scope.doCall     = function () {

            $scope.year_selected   = false;
            $scope.resetResult();

            @if(isset($admin))
            {
                $scope.show_div = false;
                academic_id      = $scope.selected_academic_id;
                parent_course_id = $scope.selected_course_parent_id;
                course_id        = $scope.selected_course_id;
                year             = $scope.selected_year;
                semister         = $scope.selected_semister;
                user_id          = '{{ $user->id }}';
            }
            @else
            {
                academic_id          = '{{default_year()}}';
                parent_course_id     = '{{prepareStudentSessionRecord($user_slug)->student->course_parent_id}}';
                course_id            = '{{prepareStudentSessionRecord($user_slug)->student->course_id}}';
                year                 = '{{prepareStudentSessionRecord($user_slug)->student->current_year}}';
                semister                 = '{{default_year()}}';
                user_id          = '{{$user->id}}';
            }
            @endif

            route   = '{{URL_STUDENT_RESULTS_GET_EXAM_CATEGORIES}}';
            data    = {   _method: 'post',
                '_token':httpPreConfig.getToken(),
                'academic_id': academic_id,
                'parent_course_id': parent_course_id,
                'course_id': course_id,
                'year': year,
                'semister': semister,
                'user_id': user_id,
            };

            httpPreConfig.webServiceCallPost(route, data).then(function(result){
                result = result.data;
                console.log(result);
                categories = [];
                angular.forEach(result, function(value, key) {
                    categories.push(value);
                })

                $scope.exam_categories = categories;
                if($scope.exam_categories.length >0 )
                    $scope.show_div = true;
            });
        }
        $scope.setTab   = function(category_id){

                @if(isset($admin))
            {

                academic_id      = $scope.selected_academic_id;
                parent_course_id = $scope.selected_course_parent_id;
                course_id        = $scope.selected_course_id;
                year             = $scope.selected_year;
                semister         = $scope.selected_semister;
                user_id          = '{{$user->id}}';
            }
                @else
            {
                academic_id          = '{{default_year()}}';
                parent_course_id     = '{{prepareStudentSessionRecord($user_slug)->student->course_parent_id}}';
                course_id            = '{{prepareStudentSessionRecord($user_slug)->student->course_id}}';
                year                 = '{{prepareStudentSessionRecord($user_slug)->student->current_year}}';
                semister                 = '{{default_year()}}';
                user_id          = '{{$user->id}}';
            }
            @endif
            $scope.tab      = category_id;

            route            = '{{URL_STUDENT_RESULTS_GET_EXAMS}}';
            data           = {   _method: 'post',
                '_token':httpPreConfig.getToken(),
                'academic_id': academic_id,
                'parent_course_id': parent_course_id,
                'course_id': course_id,
                'year': year,
                'semister': semister,
                'category_id': category_id,
                'user_id': user_id,
            };

            httpPreConfig.webServiceCallPost(route, data).then(function(result){
                result = result.data;
                console.log(result);
                examList = [];
                angular.forEach(result, function(value, key) {
                    examList.push(value);
                })

                $scope.exam_list = examList;


            });

        };


        $scope.isSet         = function(tabNum){
            return $scope.tab === tabNum;
        };

        $scope.resetResult = function()
        {
            $scope.show_div = false;
            $scope.exam_list =[];
        }
        $scope.doCall();
    });




</script>