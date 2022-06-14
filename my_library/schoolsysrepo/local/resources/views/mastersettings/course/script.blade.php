<script>
    app.controller('course_add_edit', function($scope, $http) {
        $scope.categorties = [];
        $scope.current_category = null;
        $scope.courses = [];
        $scope.first_time = true;
        $scope.graduated_course=false;
        $scope.show_graduated_course=true;
        $scope.lastPart = window.location.href.split("/").pop();

        $scope.ifEdit = function () {
            if($scope.lastPart != 'add'){
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'get_course_data/'+$scope.lastPart,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        
                        $scope.current_category    = response.data.category_id.toString();
                        console.log(response.data);
                        if(response.data.graduated_course=="1")
                        $scope.graduated_course    = true;
                        $scope.get_courses();
                      
                        if(response.data.parent_id == 0){
                            $scope.current_course      = '0';
                            $scope.show_graduated_course=true;
                           
                        }else{
                            $scope.current_course      = response.data.parent_id.toString();
                            $scope.show_graduated_course=false;
                        }

                    })
            }
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
        $scope.show_graduate = function (cource) {
            if(cource==='0')
            $scope.show_graduated_course=true;
            else
            $scope.show_graduated_course=false;
        }

        $scope.get_categories();

        $scope.get_courses = function () {
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_category_courses/'+$scope.current_category,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.courses = response.data;
//                    if(response.data.length != 0){
//                        $scope.current_course   = response.data[0].id.toString();
//                    }
                    if($scope.first_time){
                        $scope.ifEdit();
                        $scope.first_time = false;
                    }

                })
        }
    });
</script>