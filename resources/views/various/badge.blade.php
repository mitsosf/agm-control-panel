{{--
<html>
<head>
    <style>
        body, html{
            padding: 0;
            margin: 0;
            height: 1280px;
            width: 902px;
        }
    </style>
</head>
<body background="{{asset('images/badge.jpg')}}">

</body>
</html>--}}
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html{
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            text-align: center;
        }

        img {
            position: relative;
            max-width: 99.9%;
            height: 99.9%;
            z-index: -1;

        }

        .centered {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
<div class="container">
    <img src='{{asset('images/badge.jpg')}}'>
    <div class="centered">Centered</div>
</div>
</body>
</html>
