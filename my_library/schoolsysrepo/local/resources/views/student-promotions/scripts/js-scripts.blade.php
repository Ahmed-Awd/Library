@include('common.angular-factory')
<script>

    app.controller('TabController', function ($scope, $http, httpPreConfig, $location) {
        @include('common.year_sems_js');
        @include('common.course_js');
            $scope.tab = 1;
        $scope.users = [];
        $scope.exam_list = [];
        $scope.to_years = [];
        $scope.to_selected_year = '';
        $scope.to_semisters = [];
        $scope.to_selected_semister = '';
        $scope.to_course = [];
        $scope.to_course_id = '';
        $scope.to_selected_course_id = '';
        $scope.to_total_years = 0;
        $scope.to_total_semisters = 0;
        $scope.current_user = {};
        $scope.current_user_remarks = '';
        $scope.to_academic_year = {{default_year()}};
        $scope.to_academic_year = $scope.to_academic_year.toString();

        $scope.getSubjects = function () {
            return false;
        }


        $scope.get_categories = function () {
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_school_categories',
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.categorties = response.data;
                    if(response.data.length != 0){
                        $scope.current_category   = response.data[0].id.toString();
                        $scope.get_courses();
                    }
                })
        }
        $scope.get_categories();

        $scope.get_courses = function () {
//            console.log($scope.to_academic_year)
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_cat_year_courses/'+$scope.current_category+'/'+$scope.to_academic_year,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    console.log(response.data);
                    $scope.p_courses = response.data;

                })
        }

        $scope.get_sub_courses = function (id) {
//            console.log($scope.to_academic_year)
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_sub_courses/'+id,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.to_courses = response.data;

                })
        }

        $scope.doCall = function () {
            $scope.year_selected = true;

            academic_id = $scope.current_year_sc;
            parent_course_id = $scope.current_course_sc;
            course_id = $scope.current_sub_course;
   
            year = $scope.current_year_sc;
            semister = $scope.current_sem_sc;

            route = '{{URL_GET_STUDENTS}}';
            data = {
                _method: 'post',
                '_token': httpPreConfig.getToken(),
                'academic_id': academic_id,
                'parent_course_id': parent_course_id,
                'course_id': course_id,
                'year': year,
                'semister': semister,
            };

            httpPreConfig.webServiceCallPost(route, data).then(function (result) {
                users = [];
                angular.forEach(result.data, function (value, key) {
                    users.push(value);
                })
                $scope.result_data = users;
                console.log($scope.result_data);

            });


            if ($scope.isHavingSemisters) {
                route = '{{URL_MASTERSETTINGS_COURSE_SUBJECTS_GET_COURSE_SEMISTERS}}';

                data = {
                    _method: 'post',
                    '_token': httpPreConfig.getToken(),
                    'course_id': course_id,
                    'year': $scope.selected_year
                };
                httpPreConfig.webServiceCallPost(route, data).then(function (result) {
                    result = result.data;
                    $scope.isHavingSemisters = result.total_semisters;


                    semisters = [];
                    for (i = 1; i <= result.total_semisters; i++) {
                        semisters[i] = i;
                    }
                    $scope.semisters = semisters;

                });
            }
            $scope.showToCourses = true;
            $scope.showToNewParent = true;


        }

        $scope.prepareToCourses = function (to_parent) {
            academic_id = $scope.to_academic_year;
            parent_course_id = to_parent;

            route = '{{URL_ACADEMICS_COURSES_GET_CHILD_COURSES}}';

            data = {
                _method: 'post',
                '_token': httpPreConfig.getToken(),
                'academic_id': academic_id,
                'parent_course_id': parent_course_id
            };
            httpPreConfig.webServiceCallPost(route, data).then(function (result) {
                $scope.to_course.selected_course_parent_id = parent_course_id;
                list = [];
                angular.forEach(result.data, function (value, key) {
                    objData = {id: value.course.id, course_title: value.course.course_title};
                    list.push(value.course);
                });

                $scope.to_courses = list;

            });

        }

        $scope.prepareToYears = function (course_id) {
            /*$scope.showToNewParent = false;
            $scope.showToCourses=true;*/
            index = httpPreConfig.findIndexInData($scope.courses, 'id', course_id);
            total_years = $scope.courses[index].course_dueration;
            $scope.selected_to_course_id = $scope.courses[index].id;
            $scope.to_total_years = total_years;

            if (total_years == 1) {
                $scope.to_selected_semister = 0;
                $scope.to_selected_year = 1;
            }


            $scope.to_years = {"year": "Select", "values": ['Select']};
            for (i = 1; i <= total_years; i++) {
                $scope.to_years.values.push(i);
            }

            $scope.to_current_year = 'select';

        }
        $scope.changeToCourse = function () {
            /*$scope.showToNewParent = true;
            $scope.showToCourses=false;*/

        }
        $scope.toYearChanged = function (year_number) {

            $scope.to_year_selected = true;

            academic_id = $scope.to_selected_academic_id;
            parent_course_id = $scope.to_selected_course_parent_id;
            course_id = $scope.to_course.course_id;
            $scope.to_selected_year = year_number;

            angular.forEach($scope.courses_object, function (course, key) {
                if (course.course.id == course_id) {
                    angular.forEach(course.semister, function (semister, no) {
                        if (semister.year == $scope.to_selected_year) {
                            if (semister.total_semisters > 0) {
                                semisters = [];

                                $scope.to_semisters = {"current_semister": "Select", "values": ['Select']};
                                for (i = 1; i <= semister.total_semisters; i++) {
                                    $scope.to_semisters.values.push(i);
                                }
                                $scope.to_current_semister = 'select';
                                $scope.to_total_semisters = semister.total_semisters;
                                $scope.to_have_semisters = true;

                            }
                            else {
                                $scope.to_total_semisters = 0;
                                $scope.to_semisters = [];
                                $scope.to_have_semisters = false;
                            }

                        }
                    });
                }

            });

        }
        /**
         * This method will shows the confirmation popup for user course completion
         * @param  {[type]} user    [description]
         * @param  {[type]} remarks [description]
         * @return {[type]}         [description]
         */
        $scope.showConfirmation = function (user, remarks) {
            $scope.current_user_remarks = '';
            $scope.current_user = user;
            $scope.current_user_remarks = remarks;
            $('#transfer_student_summary').modal('show');
        }

        $scope.printDiv = function () {

            var divToPrint = document.getElementById('DivIdToPrint');

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function () {
                newWin.close();
            }, 10);

        }
        $scope.promptAll=function()
        {
            $('[id*=selctionAction]').val('promoted');
        }
        $scope.stopAll=function()
        {
            $('[id*=selctionAction]').val('detained');
        }
        $scope.nothingAll=function()
        {
            $('[id*=selctionAction]').val('nothing');
        }

    });

</script>