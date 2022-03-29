<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Sending Email</title>
    <style>
        * {
          box-sizing: border-box;
        }

        body {
          font-family: Arial;
          padding: 10px;
          background: #f1f1f1;
        }

        /* Header/Blog Title */
        .header {
          padding: 10px;
          text-align: center;
          background: white;
        }

        .header h1 {
          font-size: 34px;
        }

        /* Create two unequal columns that floats next to each other */
        /* Left column */
        .leftcolumn {   
          float: left;
          width: 100%;
          padding-left: 10%;
          padding-right: 10%;
        }

        /* Right column */
        .rightcolumn {
          float: left;
          width: 25%;
          background-color: #f1f1f1;
          padding-left: 20px;
        }

        /* Fake image */
        .fakeimg {
          background-color: #aaa;
          width: 100%;
          padding: 20px;
        }

        /* Add a card effect for articles */
        .card {
          background-color: white;
          padding: 20px;
          margin-top: 20px;
        }

        /* Clear floats after the columns */
        .row:after {
          content: "";
          display: table;
          clear: both;
        }

        /* Footer */
        .footer {
          padding: 10px;
          text-align: center;
          background: #ddd;
          margin-top: 20px;
        }

        .button {
          background-color: #E4D00A ;
          border: none;
          color: white;
          padding: 16px 32px;
          text-align: center;
          font-size: 14px;
          margin: 4px 2px;
          opacity: 0.6;
          transition: 0.3s;
          display: inline-block;
          text-decoration: none;
          cursor: pointer;
        }

        .button2 {
          background-color: #EE4B2B ;
          border: none;
          color: white;
          padding: 16px 32px;
          text-align: center;
          font-size: 14px;
          margin: 4px 2px;
          opacity: 0.6;
          transition: 0.3s;
          display: inline-block;
          text-decoration: none;
          cursor: pointer;
        }

        .button:hover {opacity: .8}

        .button2:hover {opacity: .8}

        /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 800px) {
          .leftcolumn, .rightcolumn {   
            width: 100%;
            padding: 0;
          }

          .header{
            font-size: 14px;
          }

          .header h1{
            font-size: 30px;
          }

        }
    </style>
</head>
<body class="">


<div class="header" style="text-align: center;">
  <h1>{{$pesan}}</h1>
</div>

<div class="row">
  <div class="leftcolumn">
    <div class="card" style="text-align:center;">
      <h2>Nomor Purchase Order</h2>
      <h4>{{$note1}}</h4>
      <h2>Nomor Invoice</h2>
      <h4>{{$note2}}</h4>
      <h2>Invoice Amount</h2>
      <h4>{{$note3}}</h4>
    </div>
    <div style="text-align: center;">
      <p>Approve PO {{$note1}} dengan Invoice {{$note2}} ? </p>
      <a href="{{url('/api/apiapprovalinvoice/yes/'.$param1.'/'.$param2)}}" class="button">Yes</a>
      <a href="{{url('/api/apiapprovalinvoice/no/'.$param1.'/'.$param2)}}" class="button2">No</a>
    </div>
  
      <!-- URL ganti ketika dipasang -->
    </div>
  </div>
</div>

<div class="footer" style="text-align: center;">
  <h5>@PT Intelegensia Mustaka Indonesia - 2021</h5>
</div>

</body>
</html>