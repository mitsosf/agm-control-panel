<head>
    <title>{{$user->name." ".$user->surname}} - AGM Thessaloniki Proof of Payment</title>
</head>
<body>
<style>
    * {
        box-sizing: border-box;
    }

    /* Create two unequal columns that floats next to each other */
    .column {
        float: left;
        padding: 10px;
    }

    .left {
        width: 60%;
    }

    .right {
        width: 40%;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    table, th, td {
        border: 1px solid #2E3192;
        border-collapse: collapse;
    }
</style>

<div class="row" style="margin-top: 2%;background: #2E3192">
    <img src="{{asset('images/ESN-white.png')}}" height="50px" alt="" style="float: right;margin-right: 20px; margin-top: 1%;margin-bottom: 1%">
</div>

<div class="row">
    <div class="column left">
        <img src="{{asset('images/logo.png')}}" height="250px" alt="">
    </div>
    <div class="column right" style="margin-right: 3%">
        <h3 style="color: #2E3192"><u>Payment by:</u></h3>
        <p>Name: <b>{{$user->name}}</b></p>
        <p>Surname: <b>{{$user->surname}}</b></p>
        <p>Email: <b>{{$user->email}}</b></p>
        <p>ESN Section: <b>{{$user->section}}</b></p>
        <p>ESN Country: <b>{{$user->esn_country}}</b></p>
        <p>Date/Time: <b>{{\Carbon\Carbon::now()}}</b></p>
    </div>
</div>
<div class="row" style="margin-top: 5%">
    <h2 style="color: #2E3192;text-align: center"><u>PAYMENT CONFIRMATION</u></h2>
</div>
<div class="row">
    <table class="table" border="1" width="100%">
        <thead>
        <tr style="background-color: #2E3192;border:0px;color: white">
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        <tr style="border-color: #2E3192">
            <td style="border-color: #2E3192;text-align: center"><h4>Participant’s Fee AGM Thessaloniki 2019</h4>
            </td>
            <td style="text-align: center">1</td>
            <td style="text-align: center">220</td>
        </tr>
        <tr>
            <td></td>
            <td><h3 style="text-align: center">Total:</h3></td>
            <td><h3 style="text-align: center">220</h3></td>
        </tr>
        </tbody>
    </table>
</div>
</body>