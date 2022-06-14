@include('layouts.scripts.popup')
<html dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">
<body>
<div id="appender">

</div>

<script>
    console.log(window.data);
</script>
<script src="{{JS}}jquery-1.12.1.min.js"></script>
<script>
    $("#appender").append("<h3>{{getPhrase('creator_ip')}} : "+window.data.created_by_ip+" </h3>");
    $("#appender").append("<h3>{{getPhrase('updater_ip')}} : "+window.data.updated_by_ip+" </h3>");
    $("#appender").append("<h3>{{getPhrase('creator_username')}} : "+window.data.created_by_user_name+" </h3>");
    $("#appender").append("<h3>{{getPhrase('updater_username')}} : "+window.data.updated_by_user_name+" </h3>");
    $("#appender").append("<h3>{{getPhrase('created_at')}} : "+window.data.created_at+" </h3>");
    $("#appender").append("<h3>{{getPhrase('updated_at')}} : "+window.data.updated_at+" </h3>");
</script>

</body>
</html>