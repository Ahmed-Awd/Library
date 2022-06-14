@include('common.angular-factory')

<script>

    app.controller('MarksController', function ($scope, $http, httpPreConfig, $location) {

        $scope.tab = 1;

        $scope.users = [];
        $scope.exam_list = [];
        $scope.selected_user = null;
        $scope.certificates_issued = [];
        $scope.form_show = false;
        $scope.quiz_categories = [];
        $scope.subjects = [];
        $scope.students = [];
        $scope.course_title = '';
        $scope.default='select';

        $scope.getClasses=function(){

            $http({
                     method:"GET",
                     url:'{{PREFIX}}'+'supervisor/teacher-courses/'+window.location.href.split("/").pop(),
                     dataType:"json",
                     headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                 })
                     .then(function (response) {
                      
                         $scope.academic_courses_sc = response.data;
                         $scope.getCourses();
                       
                     })
        }
        $scope.getStudentMarks112 = function () {
            $('#classNumber').val($('#classes').val())
            route = '{{url("supervisor/staff/students-marks/".$slug->slug)}}';
            data = {
                _method: 'post',
                '_token': httpPreConfig.getToken(),
                'course_id':$scope.default
            };

            httpPreConfig.webServiceCallPost(route, data).then(function (result) {
                result = result.data;
                $scope.result_data = result;
                $scope.subjects = result.subjects;
                $scope.students = result.students;
                $scope.course_title = result.course_title;
            });
        }

        $scope.printIt = function () {
            dta = $('#printable_data').html();
            $('#html_data').val(dta);
            $('#htmlform').submit();
        }
        $scope.getCourses = function () {

               
                $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_sub_courses/'+$scope.current_course_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                .then(function (response) {
                $scope.subcourses = response.data;
                if(response.data.length != 0){
                //$scope.current_sub_course   = response.data[0].id.toString();
                $scope.current_sub_course   = "0";
                }
                })
        }
        $scope.getClasses();
    });


</script>