 <script src="{{JS}}ngStorage.js"></script>
<script src="{{JS}}angular-messages.js"></script>


<script>
var app = angular.module('academia', ['ngMessages']);
app.controller('prepareQuestions', function( $scope, $http) {
   $scope.savedItems = [];
    $scope.savedSeries =  [];
    $scope.total_items = 0;
    $scope.current_topic_sc="";
    $scope.is_paid     = '0';
    @if(isset($record->is_paid))
        $scope.is_paid     = {{$record->is_paid}};
        $scope.is_paid     = $scope.is_paid.toString();
    @endif
    $scope.lastPart = window.location.href.split("/").pop();
    $scope.current_course_sc    = null;
    $scope.current_subject_sc   = null;
    $scope.academic_courses_sc  = [];
    $scope.academic_subjects_sc = [];
    $scope.branchtitle="";
    $scope.main_topic = [];
        $scope.main_topic_count = [];
        @include('common.year_sems_js');
        @include('common.course_js');

    
      

    if($scope.lastPart != 'add'){
        $http({
            method:"GET",
            url:'{{PREFIX}}'+'/get_default_selectors2/'+$scope.lastPart+'/lmsseries',
            dataType:"json",
            headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        })
            .then(function (response) {
                $scope.branch = response.data.course_id.toString(); 
                
                $scope.getCategories($scope.branch);
                $scope.categorii = response.data.id.toString();
                console.log($scope.categorii);
            })
            $http({
            method:"GET",
            url:'{{PREFIX}}'+'/get_series_data/'+$scope.lastPart,
            dataType:"json",
            headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        })
            .then(function (response) {
                
                $scope.branchtitle = response.data.course_title; 
                 
            })
    }

    $scope.getCategories = function (id) {
        $http({
            method:"GET",
            url:'{{PREFIX}}'+'/get_categories/'+id+'/lmscategories',
            dataType:"json",
            headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        })
            .then(function (response) {
                $scope.categories = response.data;
                $scope.categorii   = $scope.categorii;
                
                console.log($scope.categorii,$scope.categories);
                
            })
    }
   
    $scope.initAngData = function(data) {
        
        if(data === undefined)
            return;
        $scope.removeAll();
    
        if(data=='')
        {
            $scope.series   = [];
            return;
        }

        dta = data;
        $scope.savedSeries = dta.contents;
     
        $scope.setItem('saved_series', $scope.savedSeries);
        $scope.setItem('total_items', $scope.total_items);
        
 
    }

    $scope.get_topics = function()
        {

            if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null || $scope.current_subject_sc == null){
                return false;
            }

            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_all_series_topicscount/'+$scope.current_subject_sc+'/'+$scope.current_course_sc+'/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.lastPart,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.main_topic_count = response.data;
                    
                    console.log($scope.main_topic_count );
                })

            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_all_topics/'+$scope.current_subject_sc+'/'+$scope.current_course_sc+'/'+$scope.current_year_sc+'/'+$scope.current_sem_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.academic_topics_sc=[];
                    $scope.academic_topics_sc = response.data;
                    
                    angular.forEach($scope.academic_topics_sc,function(item){
                    //    console.log(item.parent_id);
                        if(item.parent_id == 0){
                            item.topic_name = "- "+item.topic_name;
                            
                            $scope.main_topic.push(item);
                        }
                    });
                    angular.forEach($scope.main_topic,function (item) {
                        item.sub_topics = [];
                        angular.forEach($scope.academic_topics_sc,function(item2){
                            
                           if(item.id == item2.parent_id){
                            
                            var gr=$scope.main_topic_count.filter(function(v){
                            return v.topic_id==item2.id;
                            });  
                            if(gr.length >0)
                            {
                                item2.total = gr[0].total.toString();
                            }
                            else    {
                                item2.total ="0";}
                               item.sub_topics.push(item2);
                               
                           }

                        });
                    });
                    if(response.data.length != 0) {
                        $scope.current_topic_sc ="0";// response.data[0].id.toString();
                        $scope.categoryChanged($scope.current_subject_sc);
                    }
                    if($scope.first_time) {
                        $scope.ifEdit();
                        $scope.first_time = false;
                        return;
                    }
                })
        }

    $scope.getSubjects = function () {
        if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null){
            return false;
        }
        $http({
            method:"GET",
            url:'{{PREFIX}}'+'get_subjects/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_course_sc,
            dataType:"json",
            headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        })
            .then(function (response) {
                $scope.academic_subjects_sc = response.data;
                if(response.data.length != 0) {
                    $scope.current_subject_sc = response.data[0].subject_id.toString();
                    $scope.get_topics();
                }
                else {$scope.getSubjects2();}
                $scope.current_topic_sc ="0";
                $scope.categoryChanged($scope.current_subject_sc);
            })
    }
    
    $scope.getSubjects2 = function () {
        $scope.current_course_sc = $scope.branch;
        $scope.getSubjects();
    }
     $scope.categoryChanged = function(selected_number) {
 
        if(selected_number=='')
            selected_number = $scope.category_id;
        category_id = selected_number;
        if(category_id === undefined)
            return;
         $http({
             method:"GET",
             url:'{{PREFIX}}'+'get_series/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_course_sc+'/'+category_id+'/'+ $scope.current_topic_sc,
             dataType:"json",
             headers:{'Content-Type': 'application/x-www-form-urlencoded'}
         })
             .then(function (response) {
                 console.log(response.data);
                 $scope.categoryItems = [];
                 $scope.categoryItems = response.data.items;
                 $scope.removeDuplicates();

             })

        }

        $scope.removeDuplicates = function(){
           
            if($scope.savedSeries.length<=0 )
                return;

             angular.forEach($scope.savedSeries,function(value,key){
                    
                    res = $scope.findIndexInData($scope.categoryItems, 'id', value.id);
                    if(res >= 0)
                    {
                         $scope.categoryItems.splice(res, 1);
                    }
                    
            });
        }
          
        $scope.addToBag = function(item) {
            
           var record = item; 
            
              res = $scope.findIndexInData($scope.savedSeries, 'id', item.id);
                    if(res == -1) {
                      $scope.savedSeries.push(record); 
                      
                      $scope.removeFromCategoryItems(item);
                    }
                  else 
                    return;

           //Push record to storage
            $scope.setItem('saved_series', $scope.savedSeries);
        }

        $scope.removeFromCategoryItems = function(item) { 
             var index = $scope.categoryItems.indexOf(item);
             $scope.categoryItems.splice(index, 1);     
        }

        $scope.addToCategoryItems = function(item) { 
          
             if($scope.categoryItems.length) {
                
                if($scope.categoryItems[0].subject_id != item.subject_id)
                    return;

                 res = $scope.findIndexInData($scope.savedSeries, 'id', item.id)
                
                    if(res == -1)
                      $scope.categoryItems.push(item);     
                return;
             }
             $scope.current_topic_sc ="0";
             $scope.categoryChanged($scope.category_id);
        }


        $scope.findIndexInData =function (Array, property, action) {
          var result = -1;
          angular.forEach(Array, function(value, index) {
             if(value[property]==action){
                result=index;
             }
          });
          return result;
        }

        /**
         * Set item to local storage with the sent key and value
         * @param {[type]} $key   [localstorage key]
         * @param {[type]} $value [value]
         */
        $scope.setItem = function($key, $value){
            localStorage.setItem($key, JSON.stringify($value));
        }

        /**
         * Get item from local storage with the specified key
         * @param  {[type]} $key [localstorage key]
         * @return {[type]}      [description]
         */
        $scope.getItem = function($key){
            return JSON.parse(localStorage.getItem($key));
        }

        /**
         * Remove question with the sent id
         * @param  {[type]} id [description]
         * @return {[type]}    [description]
         */
         

    $scope.removeItem = function(record){
        
          $scope.savedSeries = $scope.savedSeries.filter(function(element){
            if(element.id != record.id)
              return element;
          });
           
          $scope.setItem('saved_series', $scope.savedSeries);
          $scope.addToCategoryItems(record);
        }

        $scope.removeAll = function(){
            $scope.savedSeries = [];
            $scope.totalQuestions       = 0;
            $scope.setItem('saved_questions', $scope.savedSeries);
            $scope.setItem('total_questions', $scope.totalQuestions);
            $scope.current_topic_sc ="0";
            $scope.categoryChanged($scope.category_id);
            // localStorage.removeItem('saved_questions');
            // localStorage.removeItem('total_marks');
        }

     /**
     * Returns the token by fetching if from from form
     * 
     */
    $scope.getToken = function(){
      return  $('[name="_token"]').val();
    }

  
    

}  );

app.filter('cut', function () {
        return function (value, wordwise, max, tail) {
            if (!value) return '';

            max = parseInt(max, 10);
            if (!max) return value;
            if (value.length <= max) return value;

            value = value.substr(0, max);
            if (wordwise) {
                var lastspace = value.lastIndexOf(' ');
                if (lastspace != -1) {
                  //Also remove . and , so its gives a cleaner result.
                  if (value.charAt(lastspace-1) == '.' || value.charAt(lastspace-1) == ',') {
                    lastspace = lastspace - 1;
                  }
                  value = value.substr(0, lastspace);
                }
            }

            return value + (tail || ' …');
        };
    });

 
</script>