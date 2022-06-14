<script src="{{JS}}angular.js"></script>
<script src="{{JS}}ng-file-upload.js"></script>
<script>
    var app = angular.module('academia', ['ngFileUpload']);
    app.controller('student_papers', function ($scope, $timeout,Upload, $http) {

        $scope.items = [];

        $scope.add_filled = function () {
            $scope.item = {
                'id'    : $scope.current_id+1,
                'name'  : '',
                'type'  : 'document',
                'file'  : '',
                'error' : false,
            }
            $scope.items.push($scope.item);
            $scope.current_id+=1;
        }

        $scope.delete_item = function (one) {
            var index = $scope.items.indexOf(one);
            $scope.items.splice(index, 1);
        }
        
        $scope.get_recoreds = function () {
            $http({
                method:"get",
                url:'{{PREFIX}}student/papers/getData/{{$user->slug}}',
                dataType:"json",
                headers:{'Content-Type': undefined}
            }).then(function (response) {
                $scope.items = response.data.records;
                angular.forEach($scope.items,function (thing) {
                    if(thing.type == 'video' || thing.type == 'audio'){
                        thing.video = '{{IMAGE_PATH_UPLOAD_STUDENT_PAPERS}}'+thing.file;
                    }
                })
                if($scope.items.length != 0){
                    $scope.current_id = $scope.items[$scope.items.length-1].id + 1;
                }else{ $scope.current_id = 1}
                $("#appy").show();
            })
        }
        $scope.get_recoreds();

        $scope.file_upload = function($files,citem) {
            var file = $files[0];
            type = file.type.substring(0, file.type.indexOf('/'));
            $('.'+citem.id).show();
            Upload.upload({
                url: '{{PREFIX}}student/papers/upload',
                dataType:"json",
                file: file,
                method:"POST",
                headers: {'Content-Type': undefined }
            }).progress(function (e)
            {
                var progress = (e.loaded / e.total) * 100;
                $("#"+citem.id).css('width',progress+'%');
            }).then(function (response, status, headers, config) {
                if(response.data.state == 'failed'){
                    citem.error = true;
                }else{
                    citem.file = response.data.file;
                    citem.type = type;
                    if(citem.type == 'video' || citem.type == 'audio'){
                        citem.video = '{{IMAGE_PATH_UPLOAD_STUDENT_PAPERS}}'+citem.file;
                    }
                }
                $('.'+citem.id).hide();
                $('.filer').val('');
            },function (resp) {
                $('.'+citem.id).hide();
                $('.filer').val('');
                citem.error = true;
                console.log(resp.data.file[0]);

            });
        };
            
    });
</script>