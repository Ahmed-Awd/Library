<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}ng-file-upload.js"></script>
<script src="{{JS}}angular-toastr.min.js"></script>
<script src="{{JS}}angular-toastr.tpls.min.js"></script>
<script>
    var app = angular.module('academia', ['ngMessages', 'ngFileUpload', 'toastr']);

    app.controller('feedbackCtrl', function ($scope, $http, Upload, toastr) {
        $scope.bupload = true;
        $scope.file_name = [];
        $scope.upload_file = function ($files) {
            $scope.bupload = false;
            var file = $files;
            extn = [];
            for(i=0;i<file.length;i++){
                extn[i] =  "."+file[i].name.split(".").pop();
            }
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'available_FeedBack_extn',
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.av_extn = response.data.split(',');
                    flag = true;
                    for(i=0;i<extn.length;i++){
                        if(!$scope.av_extn.includes(extn[i])){
                            flag = false;
                        }
                    }
                    if(flag == true){
                        $('#progressbar').show();
                        $scope.valid = "ok";
                        Upload.upload({
                            url: '{{PREFIX}}feedback/upload',
                            dataType: "json",
                            file: file,
                            method: "POST",
                            headers: {'Content-Type': undefined}
                        }).progress(function (e) {
                            var progress = (e.loaded / e.total) * 100;
                            $("#progressbar_2").css('width', progress + '%');
                        }).then(function (response, status, headers, config) {
                            console.log(response.data);
                            if (response.data.state == "failed") {
                                toastr.error(response.data.desc, 'Error');
                            }
                            if (response.data.state == "success") {

                                toastr.success(response.data.desc);
                                $scope.bupload = true;
                                $scope.uploaded_file = response.data.file;
                            }
                            //$scope.file_show = "{{ EXAM_UPLOADS}}" + response.data.file;
                            angular.forEach(response.data.files, function(value, key) {
                                $scope.file_name.push(value)
                            });
                            $('#progressbar').hide();
                            $('#upload1').val('');
                        });
                    }else{
                        $scope.valid = "no";
                        $scope.upload1 = "";
                        $scope.massage = "invalid file type";
                    }
                })
        }

        $scope.deleteFile = function () {

            angular.forEach($scope.file_name, function (value, key) {
                $http({
                    method: "delete",
                    url: '{{PREFIX}}feedback/file-delete/' + value,
                    dataType: "json",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        $scope.file_name = false;
                    })
            });
        }

    });

</script>