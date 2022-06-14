 
@include('common.angular-factory',array('load_module'=> FALSE))
<script>
 app.controller('TabController', function ($scope, $http, $rootScope,httpPreConfig,$location)
  {

      @include('common.year_sems_js');
      $scope.get_branches = function () {
          $http({
              method:"GET",
              url:'{{PREFIX}}'+'get_branches',
              dataType:"json",
              headers:{'Content-Type': 'application/x-www-form-urlencoded'}
          })
              .then(function (response) {
                  $scope.branchs = response.data;
                  if($scope.branchs.length != 0){
                      $scope.current_branch = $scope.branchs[0].id.toString();
                  }
                  $scope.getYears();
              })
      }
      $scope.get_branches();




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
          $http({
              method:"GET",
              url:'{{PREFIX}}'+'get_cat_year_courses/'+$scope.current_category+'/'+$scope.current_year_sc,
              dataType:"json",
              headers:{'Content-Type': 'application/x-www-form-urlencoded'}
          })
              .then(function (response) {
                  $scope.academic_courses_sc = response.data;
                  if(response.data.length != 0){
                      $scope.current_course_sc   = response.data[0].course_id.toString();
                      $scope.get_sub_courses($scope.current_course_sc);
                  }

              })
      }

      $scope.get_sub_courses = function (id) {
          $http({
              method:"GET",
              url:'{{PREFIX}}'+'get_sub_courses/'+id,
              dataType:"json",
              headers:{'Content-Type': 'application/x-www-form-urlencoded'}
          })
              .then(function (response) {
                  $scope.subcourses = response.data;
                  if(response.data.length != 0){
                      $scope.current_sub_course   = response.data[0].id.toString();
                  }
              })
      }

      $scope.getCountries = function () {
          $http({
              method:"GET",
              url:'{{PREFIX}}'+'/get_countries',
              dataType:"json",
              headers:{'Content-Type': 'application/x-www-form-urlencoded'}
          })
              .then(function (response) {
                  $scope.countires = response.data;
                  console.log($scope.countires);
                  if(response.data.length != 0){
                      $scope.current_nationality   = response.data[0].country_code.toString();
                  }
              })
      }
      $scope.getCountries();


      $scope.tab             = 1;
 
     $scope.exam_list      = []; 
 
    $scope.doCall = function(){
      
      academic_id          = $scope.selected_academic_id;
      parent_course_id     = $scope.selected_course_parent_id;
      course_id            = $scope.selected_course_id;

      year                 = $scope.selected_year;
      semister             = $scope.selected_semister;

       route   = '{{URL_USERS_IMPORT_EXCEL_INFORMATION}}';  
        data    = {   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'academic_id': academic_id, 
                  'parent_course_id': parent_course_id,
                  'course_id': course_id,
                  'year': year,
                  'semister': semister,
               };

      httpPreConfig.webServiceCallPost(route, data).then(function(result){
        result = result.data;
          $scope.result_data = result;
          

      });
 
    }

 
   
});
 
  
</script>