<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Print Table</title>
        <meta charset="UTF-8">
        <meta name=description content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link href="{{CSS}}bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{CSS}}font-awesome.css"/>
        <style>
            body {margin: 20px}
        </style>
    </head>
    <body>
        <table class="table table-bordered table-condensed">
            @foreach($data as $row)
                @if ($row == reset($data)) 
                    <tr>
                        @foreach($row as $key => $value)
                            <th>{!! $key !!}</th>
                        @endforeach
                    </tr>
                @endif
                <tr>
                    @foreach($row as $key => $value)
                        @if (is_string($value) || trim($value)==='' || is_numeric($value))
                            <td>{!! $value !!}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </body>
</html>
