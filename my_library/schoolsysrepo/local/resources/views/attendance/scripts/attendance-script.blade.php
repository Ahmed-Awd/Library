<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>

<script>
var app = angular.module('academia', ['ngMessages']);

app.controller('attendanceController', function($scope, $http) {


    $scope.getSubjects = function () {
        return false;
    }

    $scope.initAngData = function(data) {
      $scope.total = data;
      $scope.present = data;
      $scope.absent = 0;
      $scope.leave = 0;
      $scope.updateCount();
    }


     $scope.printIt = function(){
            dta = $('#printable_data').html();
            $('#html_data').val(dta);
            $('#wes').empty();
            $('#wes').append("<input type='hidden' name='select_option' value='print' />");
            $('#htmlform').submit();
        }
    $scope.updateCount = function () {


      var RadiobtnValues    =$("div #myForm").find('input:checked');
      present = 0;
      absent = 0;
      leave = 0;

    RadiobtnValues.each(function(index,elem){
        value = elem.value;
        if(value=='P')
        {
          present++;

        }
        else if(value=='L')
        {
          leave++;

        }
        else if(value=='A')
        {
          absent++;
        }

    });


        $scope.all_here = function(){
            console.log("all here");
            $(".all_here").prop("checked", true);
            $scope.absent = 0;
            $scope.leave = 0;
            $scope.present = $scope.total;
        }
        $scope.all_absent = function(){
            console.log("all not here");
            $(".all_absent").prop("checked", true);
            $scope.absent = $scope.total;
            $scope.present = 0;
            $scope.leave = 0;
        }
        $scope.all_left = function(){
            console.log("all gone");
            $(".all_left").prop("checked", true);
            $scope.leave = $scope.total;
            $scope.present = 0;
            $scope.absent = 0;
        }
        $scope.cancel_all = function(){
            console.log("all cancel");
            $(".all_here").prop("checked", false);
            $(".all_absent").prop("checked", false);
            $(".all_left").prop("checked", false);

            $scope.leave = 0;
            $scope.present = 0;
            $scope.absent = 0;
        }

      $scope.present = present;
      $scope.absent = absent;
      $scope.leave = leave;

    }
 });
</script>
