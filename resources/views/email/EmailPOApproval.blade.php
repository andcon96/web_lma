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

        table, tr, td {
          margin: 0 auto;
          border: 1px solid black;
        }

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
      <table>
        <tbody>
          <tr>
            <td style="width: 15%; background-color: #aaa;" >
              PO No.
            </td>
            <td style="width: 20%;">
              {{$note1}}
            </td>
          </tr>
          <tr>
            <td style="width: 15%; background-color: #aaa;" >
              Supplier
            </td>
            <td style="width: 20%;">
              {{$note4}}
            </td>
          </tr>
          <tr>
            <td style="width: 15%; background-color: #aaa;" >
              Posting Date
            </td>
            <td style="width: 20%;">
              {{$note5}}
            </td>
          </tr>
          <tr>
            <td style="width: 15%; background-color: #aaa;">
              Invoice No.
            </td>
            <td style="width: 20%;">
              {{$note2}}
            </td>
          </tr>
          <tr>
            <td style="width: 15%; background-color: #aaa;">
              Amount
            </td>
            <td style="width: 20%;">
              {{number_format($note3,2)}}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="text-align: center;">
      <p>Approve or Reject this Request using the buttons below.  emakk </p>
      <a href="{{'http://45.196.31.99:22002/api/apiapprovalinvoice/yes/'.$param1.'/'.$param2.'/'.$param3.'/'.$param4.'/'.$param5 }}" class="button">Approve</a>
      <a href="{{'http://45.196.31.99:22002/api/apiapprovalinvoice/no/'.$param1.'/'.$param2.'/'.$param3.'/'.$param4.'/'.$param5 }}" class="button2">Reject</a>
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