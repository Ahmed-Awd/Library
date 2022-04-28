<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback</title>
</head>
<body>
<div style="max-width: 600px;padding: 35px;margin: auto; text-align: left;border: 5px solid #8ac054;">
    <img src="{{asset('images/logo.png')}}" style="margin: auto;">
    <hr style="height: 1px; margin: 1rem 0; background-color: #f2f1f1; border: 0;">
    <div style="background: #ffffff;">
        <div style="align-items: center;display: flex;">

            <div style="margin-left: 10px;">
                <h3 style="margin-top: 0; margin-bottom: 5px;font-size: 1.1rem;">{{$record->name}}</h3>
                <p style="font-size:13px; color: #a3a2a8; margin: 0;">{{$record->created_at}}</p>
            </div>
        </div>
        <p>{{$record->message}}</p>
        <hr style="height: 1px; margin: 1rem 0; background-color: #f2f1f1; border: 0;">
        <div style="align-items: center;display: flex;">

            <div style="margin-left: 10px;">
                <h3 style="margin-top: 0; margin-bottom: 5px;font-size: 1.1rem;">admin (<span style="background: #8ac054b3;">{{ config('app.name') }}</span>)</h3>
            </div>
        </div>
        <h3 style="margin-bottom: 0;">{{$record->replay_subject}}</h3>
        <p>{{$record->replay_message}}</p>
    </div>
</div>
</body>
</html>
