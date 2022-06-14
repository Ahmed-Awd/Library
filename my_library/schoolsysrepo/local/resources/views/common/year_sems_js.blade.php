$scope.current_year_sc = {{default_year()}};
$scope.current_year_sc = $scope.current_year_sc.toString();
$scope.getYears = function () {
$http({
method:"GET",
url:'{{PREFIX}}'+'get_years',
dataType:"json",
headers:{'Content-Type': 'application/x-www-form-urlencoded'}
})
.then(function (response) {
$scope.academic_years_sc = response.data;
$scope.current_year_sc      = {{default_year()}};
$scope.current_year_sc      = $scope.current_year_sc.toString();
$scope.get_sems();
//$scope.getCourses();
})
}
$scope.getYears();

$scope.get_sems = function () {
$http({
method:"GET",
url:'{{PREFIX}}'+'get_year_sems/'+ $scope.current_year_sc,
dataType:"json",
headers:{'Content-Type': 'application/x-www-form-urlencoded'}
})
.then(function (response) {
$scope.academic_sems_sc = response.data;
$http({
method:"GET",
url:'{{PREFIX}}'+'get_defualt_sem/'+ $scope.current_year_sc,
dataType:"json",
headers:{'Content-Type': 'application/x-www-form-urlencoded'}
})
.then(function (response) {
$scope.current_sem_sc = response.data.toString();
$scope.getCourses();
})
})
}