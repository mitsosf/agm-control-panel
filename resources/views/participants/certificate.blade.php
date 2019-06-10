<head>
    <title>{{$user->name." ".$user->surname}} - AGM Thessaloniki Certificate of attendance</title>
</head>
<body>
    <style>
        /* Create two unequal columns that floats next to each other */
        .column {
            float: left;
            padding: 10px;
        }

        .left {
            width: 70%;
        }

        .right {
            width: 30%;
        }

        /* Clear floats after the columns */
        .row:after {
            display: table;
            clear: both;
        }

    </style>

    <div class="row" style="background: #00AEEF; margin-top: -4.8%;margin-left: -6.5%;margin-right: -6.2%;">
        <img src="{{asset('images/ESN-white.png')}}" height="50px" alt="" style="float: right;margin-bottom: 1%;margin-top: 1%;margin-right: 1%">
        <img src="{{asset('images/logo-white.png')}}" height="55px" alt="" style="float: left;margin-bottom: 1%;margin-top: 1%;margin-left: 1%">
    </div>

    <div class="row" style="margin-top: 5%">
        <div class="column left"></div>
        <div class="column right">
            <span style="font-size: smaller;">Thessaloniki, 22-04-2019</span>
        </div>
    </div>

    <div class="row" style="margin-top: 3%;text-align: center">
      <h2 style="color: #00AEEF">Certificate of attendance</h2>
    </div>

    <div class="row" style="text-align: left">
        <p>I hereby confirm the participation of <b>{{$user->name}} {{$user->surname}}</b> in the Annual General
            Meeting of Erasmus Student Network – AGM Thessaloniki 2019 – that took place from
            18th until 22nd of April 2019.</p>
        <p style="margin-top: 3%">Erasmus Student Network (ESN) is one of the biggest interdisciplinary student
            associations in Europe, founded in 1989 to support and develop student exchanges,
            especially within the framework of the Erasmus programme, which forms an important
            part of the European Union’s Lifelong Learning Programme LLP.</p>
        <p style="margin-top: 3%">The Annual General Meeting is our general assembly and all official sections attended in order to
            make decisions about strategic issues of the network, the
            international budget for 2019, the Action Plan for 2019/2020, and who will be on the
            International Board in 2019/2020.</p>
        <p style="margin-top: 3%">Yours sincerely,</p>
        <p>On behalf of Erasmus Student Network Greece,<br>
            Antonis Platis,<br>
            Head of the Organisation Committee</p>
        <img style="margin-top: 3%" src="{{asset('images/signature.png')}}" alt="signature" height="180px">
        <p style="font-size: smaller; margin-top: 5%;">Web: <a href="https://agmthessaloniki.org/">https://agmthessaloniki.org/</a><br>
            Email: <a href="mailto:information@agmthessaloniki.org">information@agmthessaloniki.org</a></p>
    </div>


</body>