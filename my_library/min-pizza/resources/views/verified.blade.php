<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>verification message</title>
</head>
<body>
<div style="max-width: 600px;padding: 35px;margin: auto; text-align: center;background: #f8f8f8;">
    <img src="{{asset('images/logo.png')}}" style="margin: auto;">
    <div style="padding: 35px 25px; background: #ffffff; margin-top: 20px;">
        <p><span style="font-weight: bold;">Welcome, </span> {{$user->name}}</p>
        <p>{{ __('messages.auth.verified') }}</p>
    </div>
</div>
</body>
</html>
