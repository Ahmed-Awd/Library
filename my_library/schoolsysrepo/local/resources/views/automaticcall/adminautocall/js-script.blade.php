<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-animate.min.js"></script>
<script>
var app = angular.module('automaticCall',['ngAnimate']);
app.controller('automaticCallController', function($scope, $interval, $http, $timeout) {
  $scope.requests = [];
  $scope.temp = [];
  $scope.studentInfo = {} ;
  $scope.newRequests = [];
  $scope.today = new Date().toISOString().split("T")[0];
  var current = 0;
  var now = new Date();

  var tick = function() {
    $scope.clock = Date.now();
  }
  tick();
  $interval(tick, 60000);
 var automaticRequest = function () {
   console.log("automaticRequest");
$http({
  method: 'GET',
  url: "{{url('attendanceoperations/autocall/notview')}}"
}).then(function success(response) {
 $scope.newRequests = response.data;
  var length = $scope.newRequests.length - $scope.temp.length;
  if(length>0) {
    if($scope.temp.length == 0) {
      for (var i = $scope.temp.length ; i <$scope.newRequests.length; i++) {
        $scope.temp.push($scope.newRequests[i]);
        $scope.requests.push($scope.newRequests[i]);
      }
      $scope.play();
    } else {
      for (var i = $scope.temp.length ; i <$scope.newRequests.length; i++) {
        $scope.temp.push($scope.newRequests[i]);
        $scope.requests.push($scope.newRequests[i]);
      }
    }
  }
});


 }
 $interval(automaticRequest, 15000);
  $scope.getRequest = function(requests) {
    $scope.requests = requests;
    if($scope.requests.length == 0) {
     $scope.isStudent = true;
   }
    for(var i=0;i<$scope.requests.length;i++) {
      $scope.temp[i] = $scope.requests[i]
    }
    $scope.play();
    }
  $scope.leave = function(request_id, index) {
    var time = $scope.getTime();
    var request = {
          method: 'POST',
          url: "{{url('attendanceoperations/autocall/leave')}}",
          data: {'request_id' : request_id,
                 'leave_time' : time},
      };
      $http(request).then(function success(e) {
            var request =   $scope.requests[index];
            var indexRequest = $scope.temp.findIndex(temprequest => temprequest.student_id === request.student_id);
            $scope.requests.splice(index,1);
            $scope.temp.splice(indexRequest,1);
            if($scope.requests.length == 0) {
             $scope.isStudent = true;
           }
          });

  }


  $scope.play = function() {
     $scope.audio = new Audio();
     if(angular.isDefined($scope.temp[current])) {
      if($scope.temp[current].student_audio==null)
      $scope.temp[current].student_audio="empty.mp3";

       $scope.audio.src = '{{UPLOADS}}/student_names/'+$scope.temp[current].student_audio;
       
       $scope.audio.play();
       $scope.studentInfo =  $scope.temp[current];
       if($scope.temp.length != 1) {
       $scope.temp[current].isCalled = true;
      } else {
       $scope.temp[current].isCalled =  false;
     }
     }
         $scope.audio.onended = function(){
           if($scope.temp.length == 1) {
             request_id = $scope.temp[0].request_id;
           } else {
             $scope.temp[current].isCalled = false;
             request_id = $scope.temp[current].request_id;
           }
           var request = {
                 method: 'POST',
                 url: "{{url('attendanceoperations/autocall/counter')}}",
                 data: {'request_id' : request_id}
             };
           $http(request)
           if($scope.temp.length > 1) {
           $scope.requests.splice(0,1);
           $scope.$apply();
           $scope.requests.push($scope.temp[current]);
           $scope.$apply();
         }
           if(current >= $scope.requests.length-1)
           {
             current = 0;
           } else {
             current++;
           }
           $scope.$apply();
           $scope.play();
      };
    };

  $scope.getTime = function() {
    var minutes = now.getMinutes();
    if (now.getHours()<12) {
      var hours = now.getHours()
    } else {
      var hours = now.getHours()-12;
    }
    var seconds = now.getSeconds();
    return time = hours + ":" + minutes + ":" + seconds ;
  }
})
</script>
