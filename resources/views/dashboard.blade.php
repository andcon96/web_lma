@extends('layout.layout')

@section('content')
<style type="text/css">
  html,
  body {
    overflow-x: hidden;
    overflow-y: auto;
    -ms-overflow-style: none;
    scrollbar-width: none;
  }

  .fa-sync:hover {
    color: red !important;
    cursor: pointer;
  }

  .a {
    text-align: center;
    font-size: 15px !important;
    word-spacing: 80px;
    text-indent: 20px;
    font-style: normal;
    /* font-weight: bold; */

  }

  div.b {
    text-align: center;
    font-size: 18px !important;
    word-spacing: 83px;
    text-indent: 38px;
    height: 6px;
    /*lebar canvas ke bawah*/
    font-weight: bold;
    /* vertical-align: text-bottom;*/
    align-items: flex-end;
  }


  /*Number of Purchase Order*/
  div.c {
    text-align: center;
    font-size: 18px !important;
    word-spacing: 110px;
    text-indent: 10px;
    height: 20px;
    /*lebar canvas ke bawah*/
    /*    color: green;*/
    /* font-style: normal;*/
    font-weight: bold;
    /*font-family: "Times New Roman", Times, serif;*/
  }

  div.d {
    text-align: center;
    font-size: 10px !important;
    word-spacing: 55px;
    text-indent: 35px;
    color: green;
    font-style: normal;

  }

  div.d1 {
    text-align: center;
    font-size: 10px !important;
    word-spacing: 40px;
    text-indent: 35px;
    color: green;
    font-style: normal;
    /*font-family: "Times New Roman", Times, serif;*/
  }

  div.d2 {
    text-align: center;
    font-size: 10px !important;
    word-spacing: 80px;
    text-indent: 10px;
    font-weight: bold;
    height: 2px;

  }

  div.d3 {
    text-align: center;
    font-size: 12px;
    height: 27px;
    font-weight: bold;

  }

  div.a1 {
    text-align: center;
    font-size: 12px !important;
    color: green;
    /* font-style: normal;*/
    font-weight: bold;
    /*font-family: "Times New Roman", Times, serif;*/
  }

  .a2 {
    text-align: center;
    font-size: 10px;
    font-weight: bold;
  }

  /*======lebar canvas Purchase Order Approval Status===========*/



  .fa-refresh:hover {
    color: red !important;
    cursor: pointer;
  }

  .satu {
    font-size: 25px !IMPORTANT;
    color: black !IMPORTANT;
    background-color: #F0E68C;
    text-align: center !IMPORTANT;
  }

  .empat {
    font-size: 30px;
    color: darkblue;
  }


  .dua {
    font-size: 25px !IMPORTANT;
    color: black !IMPORTANT;
    background-color: #8a837b;
    text-align: center !IMPORTANT;

  }

  .manu {
    font-size: 30px;
    color: white !IMPORTANT;
    background-color: brown;
  }

  .space {
    padding-bottom: 7%;
  }

  .notification-container {
    padding-left: 0;
    padding-right: 0;
  }

  .notification {
    overflow-y: scroll;
    -ms-overflow-style: none;
    scrollbar-width: none;
    height: 581px;
    display: block;
  }

  .notification::-webkit-scrollbar {
    display: none;
  }

  .po-approval {
    padding-left: 0;
    padding-right: 0;
  }

  .item-expire {
    padding-left: 0;
    padding-right: 0;
  }

  .chart-wrapper {
    height: 103px;
  }

  .chart-wrapper-item {
    height: 101px;
  }

  .item-container {
    /* height: 621px; */
  }

  .po-card {
    height: 109px;
  }

  .card-info {
    text-align: center;
  }

  .content-header {
    padding-top: 2px;
    padding-bottom: 5px;
  }

  .first-title-container {
    height: 50px;
  }

  .title-container {
    height: 50px;
  }

  .header-text {
    font-size: 14px;
  }

  .po-info {
    padding-left: 10px;
    padding-right: 10px;
  }

  .purchased-item-info {
    padding-left: 10px;
    padding-right: 10px;
  }

  .manufactured-item-info {
    padding-left: 10px;
    padding-right: 10px;
  }

  .item-circle {
    height: 10px;
    width: 18px;
  }

  .day-circle {
    font-size: 12px;
    font-weight: bold;
  }

  .chart-container {
    height: 101px;
  }

  .purchased-item-card {
    height: 109px;
  }

  .manufactured-item-card {
    height: 109px;
  }

  .chart-container-2 {
    height: 100px;
  }

  .chart-container-3 {
    height: 141px;
  }

  .item-expire-container {
    padding-left: 0;
    padding-right: 0;
  }

  .past-due-container {
    padding-left: 0;
    padding-right: 0;
  }

  .past-due-po {
    padding-left: 0;
    padding-right: 0;
  }

  .chart-bar-container {
    height: 200px;
  }

  .card-text {
    font-size: 14px;
  }

  @media screen and (max-width: 1145px) {
    .header-text {
      font-size: 12px;
    }

    .chart-container {
      height: 104px;
    }

    .chart-container-2 {
      height: 104px;
    }

    .chart-wrapper-item {
      height: 104px;
    }
  }

  @media screen and (max-width: 1030px) {
    .chart-container-2 {
      height: 90px;
    }
  }

  @media screen and (max-width: 1024px) {
    .po-approval-container {
      padding-left: 0;
      padding-right: 0;
    }

    .notification-wrapper {
      padding-right: 0;
      padding-left: 0;
    }

    .notification {
      height: auto;
    }

    .chart-wrapper {
      height: auto;
    }

    .item-container {
      height: auto;
    }

    .content-header {
      padding: 0;
    }

    .messageNotif {
      font-size: 12px;
    }

    .header-text {
      font-size: 12px;
    }

    .user-icon {
      margin-top: 8px;
    }

    .role {
      font-size: 12px;
    }

    .a2 {
      text-align: center;
      font-size: 8px;
      font-weight: bold;
    }

    .d3 {
      font-size: 10px;
    }

    .title-container {
      padding-top: 2%;
      text-decoration: underline;
    }

    .title-text {
      font-size: 12px;
    }

    .mark-as-read-all {
      font-size: 10px;
    }

    .data-message {
      font-size: 10px;
    }

    .nbr {
      font-size: 10px;
    }

    .note {
      font-size: 10px;
    }

    .po-header {
      padding: 6px;
    }

    .po-card {
      height: 90px;
    }

    .item-expire-card {
      padding-left: 0;
      padding-right: 0;
      padding-bottom: 0;
    }

    .chart-wrapper-item {
      height: 87px;
    }

    .no-activity {
      padding: 9px;
    }

    .purchase-item-number {
      font-size: 12px;
      font-weight: bold;
    }

    .purchased-item-card {
      height: 90px;
    }

    .manufactured-item-card {
      height: 90px;
    }

    .item-circle {
      height: 8px;
      width: 10px;
    }

    .day-circle {
      font-size: 8px;
      font-weight: bold;
    }

    .chart-container {
      height: 66px;
    }

    .chart-container-2 {
      height: 69px;
    }

    .chart-container-3 {
      height: 103px;
    }

    .item-expire {
      padding-left: 10px;
    }
  }

  @media screen and (max-width: 993px) {
    .header-text {
      font-size: 10px !important;
    }

    .chart-container-2 {
      height: 75px;
    }

    .chart-container-3 {
      height: 107px;
    }
  }

  @media screen and (max-width: 992px) {
    .space {
      margin-bottom: 18%;
    }
  }

  @media screen and (min-width: 992px) {
    .chart-pie {
      height: 150px;
      padding-bottom: 0px;
      padding-top: 10px;
    }

    .po-approval {
      padding-left: 0;
      padding-right: 0;
    }

    /* .card-header{
      height: 10px !important;
    } */

    /* .card-body{
      padding: 5px !important;
    } */
  }

  @media screen and (max-width: 769px) {
    .purchased-item-card {
      height: 91px;
    }

    .item-expire {
      padding-left: 10px;
    }
  }

  @media screen and (max-width: 768px) {

    .po-info {
      padding-left: 0;
    }

    .purchased-item-info {
      padding-left: 0;
    }

    .manufactured-item-info {
      padding-left: 0;
    }
  }

  @media screen and (max-device-width: 767px) {
    .first-title-container {
      display: none !important;
    }

    .po-info {
      padding-right: 0
    }

    .purchased-item-info {
      padding-right: 0;
    }

    .manufactured-item-info {
      padding-right: 0;
    }

    .title-container {
      height: 73px;
    }

    .item-expire {
      padding-left: 0;
    }
  }

  @media screen and (max-width: 400px) {
    .messageNotif {
      white-space: nowrap;
    }

    .role {
      font-size: 10px;
    }
  }
</style>
<div class="row">
  <div class="col-md-2 po-info">
    <div class="first-title-container">
      <div class="card-info"></div>
    </div>
    <div class="card border-left-info shadow po-card" style="max-width: 100%; width: 100%;">
      <div class="card-header align-items-center justify-content-between po-header" style="background-color: #E0FFFF">
        <h6 class="m-0 font-weight-bold" style="text-align: center;">
          <a class="header-text" style="color:black;" href="{{route('listUnApprovedPOBySupplier')}}">Unconfirmed PO</a>
        </h6>
      </div>
      <div class="card-body align-items-center justify-content-center d-flex">
        <div class="a1" style="justify-items: center;">
          <h6 class="purchase-item-number" style="color:black;"> {{$poUnconfirmedBySupp}} </h4>
        </div>
      </div>
    </div>

    <div class="card border-left-info shadow po-card" style="max-width: 100%;">
      <div class="card-header align-items-center justify-content-between po-header" style="background-color: #E0FFFF">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          <a style="color:black;" href="{{route('listPODueIn7')}}">PO Due in 7 Days</a>
        </h6>
      </div>
      <div class="card-body align-items-center justify-content-center d-flex">
        <div class="a1" style="justify-items: center;">
          <h6 class="purchase-item-number" style="color:black;">
            {{$poDueIn7Days}}
            </h4>
        </div>
      </div>
    </div>

    <div class="card border-left-info shadow po-card" style="max-width: 100%;">
      <div class="card-header align-items-center justify-content-between po-header" style="background-color: #E0FFFF">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          <a style="color:black;" href="{{route('listOpenPO')}}">Open PO</a>
        </h6>
      </div>
      <div class="card-body align-items-center justify-content-center d-flex">
        <div class="a1" style="justify-items: center;">
          <h6 class="purchase-item-number" style="color:black;">
            {{$openPO}}
            </h4>
        </div>
      </div>
    </div>

    <div class="card border-left-info shadow po-card" style="max-width: 100%;">
      <div class="card-header align-items-center justify-content-between po-header" style="background-color: #E0FFFF">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          <a class="purchase-item-number" style="color:black;" href="{{route('listOpenRFQ')}}">Open RFQ</a>
        </h6>
      </div>
      <div class="card-body align-items-center justify-content-center d-flex">
        <div class="a1" style="justify-items: center;">
          <h6 class="purchase-item-number" style="color:black;">
            {{$openRFQ}}
            </h4>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-2 purchased-item-info">
    <div class="title-container">
      <div class="card-info justify-content-center">
        <a class="title-text" href="{{route('listPurchasedItemNoActivity')}}">Purchased items<br> with no activity for: </a>
      </div>
    </div>
    <div class="card border shadow purchased-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #F0E68C">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          30 Days
        </h6>
      </div>

      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $purItemNoAct30->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($purItemNoAct30->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>

    <div class="card border shadow purchased-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #F0E68C">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          90 Days
        </h6>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number  flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $purItemNoAct90->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($purItemNoAct90->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>

    <div class="card border shadow purchased-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #F0E68C">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          180 Days
        </h6>
      </div>

      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $purItemNoAct180->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($purItemNoAct180->sum('tr_hist_amount') ,2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>

    <div class="card border shadow purchased-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #F0E68C">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          365 Days
        </h6>
      </div>

      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $purItemNoAct365->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black">{{ number_format($purItemNoAct365->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-2 manufactured-item-info">
    <div class="title-container">
      <div class="card-info justify-content-center">
        <a class="title-text" href="{{route('listManufacturedItemNoActivity')}}">Manufactured items <br> with no activity for: </a>
      </div>
    </div>
    <div class="card border shadow manufactured-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #FFE4C4">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          30 Days
        </h6>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $manItemNoAct30->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($manItemNoAct30->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>

    <div class="card border shadow manufactured-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #FFE4C4">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          90 Days
        </h6>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $manItemNoAct90->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($manItemNoAct90->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>

    <div class="card border shadow manufactured-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #FFE4C4">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          180 Days
        </h6>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{$manItemNoAct180->count()}}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($manItemNoAct180->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>

    <div class="card border shadow manufactured-item-card">
      <div class="card-header align-items-center justify-content-between no-activity" style="background-color: #FFE4C4">
        <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
          365 Days
        </h6>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ $manItemNoAct365->count() }}</h6>
          </center>
        </div>
      </div>
      <div class="card-body" style="padding: 5px;">
        <div class="purchase-item-number flex-row align-items-center justify-content-between">
          <center>
            <h6 class="card-text" style="color: black;">{{ number_format($manItemNoAct365->sum('tr_hist_amount'), 2) }}
            </h6>
          </center>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 po-approval">
    <!-- column for PO Approval Status & Safety Stock -->
    <div class="first-title-container">
      <div class="card-info"></div>
    </div>
    <!-- PO Approval Status Pie chart -->
    <div class="col-md-12 po-approval-container">
      <div style="width: 100%;">
        <div class="card border-left-info shadow">
          <div class="card-header align-items-center justify-content-between">
            <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">PO Approval Status
              <i class="fas fa-sync fa-sm fa-fw text-gray-400" style="float: right;" onclick="chart1()"></i>
            </h6>
          </div>
          <div class="card-body p-1">
            <div class="no-gutters align-items-center">
              <div class="d3 header-text m-0">
                Total Unapproved:
                {{$unApprPO}}
              </div>
              <div class="chart-container">
                <canvas width="500" height="400" class="chartjs-render-monitor" id="myChart1"
                  style="position: relative; width: 254px; display: block;"></canvas>
              </div>
              <div class="a2">
                <svg height="18" width="20">
                  <circle cx="8" cy="8" r="4" fill="green" />
                </svg> Approved PO :
                {{$approvedPO}}
                <br>
                <svg height="18" width="20">
                  <circle cx="8" cy="8" r="4" fill="yellow" />
                </svg> Unapproved PO < 3 Days : {{$unApprPOLess3}} <br>
                  <svg height="18" width="20">
                    <circle cx="8" cy="8" r="4" fill="red" />
                  </svg> Unapproved PO > 7 Days :
                  {{$unApprPOMore7}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- PO Approval Status -->

    <!-- Safety Stock Donut Chart -->
    <div class="col-md-12 po-approval-container">
      <div style="width: 100%;">
        <div class="card border-left-info shadow">
          <div class="card-header align-items-center justify-content-between">
            <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
              Almost Safety Stock :
              {{$safetyStock}} Items
              Below Safety Stock :
              {{$sentEmailStock}} Items
            </h6>
          </div>
          <div class="card-body pb-1">
            <div class="no-gutters align-items-center">
              <div class="chart-container-2">
                <canvas width="500" height="400" class="chartjs-render-monitor" id="mySafetyStock"
                  style="width: 274px; display: block;"></canvas>
              </div>
              <div class="a2">
                <br>
                <svg height="18" width="20">
                  <circle cx="8" cy="8" r="4" fill="yellow" />
                </svg> Almost at Safety Stock
                <br>
                <svg height="18" width="20">
                  <circle cx="8" cy="8" r="4" fill="red" />
                </svg> Below Safety Stock
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- Safety Stock Donut Chart -->
  </div> <!-- Column -->
  <div class="col-md-3 item-expire">
    <div class="first-title-container"></div>
    <div class="col-md-12 item-expire-container">
      <div class="item-container" style="width: 100%;">
        <div class="card border-left-info shadow" style="width: 100%; height: 100%;">
          <div class="card-header align-items-center justify-content-between">
            <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">
              Item Expire in :
            </h6>
          </div>
          <div class="card-body item-expire-card">
            <div class="no-gutters align-items-center chart-wrapper-item">
              <canvas width="274" height="200" class="chartjs-render-monitor" id="myexpitm"
                style="display: block;"></canvas>
            </div>
            <center>
              <svg class="item-circle">
                <circle cx="4" cy="4" r="4" stroke="" stroke-width="" fill="red" />
              </svg>
              <span class="day-circle">
                0 days :
                {{$itemExpire}} &nbsp;&nbsp;
              </span>
              <svg class="item-circle">
                <circle cx="4" cy="4" r="4" stroke="" stroke-width="" fill="green" />
              </svg>
              <span class="day-circle">
                30 days :
                {{$itemExpire30}} <br>
              </span>
              <svg class="item-circle">
                <circle cx="4" cy="4" r="4" stroke="" stroke-width="" fill="black" />
              </svg>
              <span class="day-circle">
                90 days :
                {{$itemExpire90}} &nbsp;&nbsp;
              </span>
              <svg class="item-circle">
                <circle cx="4" cy="4" r="4" stroke="" stroke-width="" fill="orange" />
              </svg>
              <span class="day-circle">
                180 days :
                {{$itemExpire180}} <br>
              </span>
            </center>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 past-due-po">
      <div style="width: 100%;">
        <div class="card border-left-info shadow">
          <div class="card-header">
            <h6 class="header-text m-0 font-weight-bold" style="text-align: center;">Past Due Purchase Order
              <i class="fas fa-sync fa-sm fa-fw text-gray-400" style="float: right;" onclick="chart2()"></i>
            </h6>
          </div>
          <div class="card-body p-1">
            <div class="no-gutters align-items-center">
              <div class="d3 header-text m-0 ">Total Past Due PO:
                {{$totalPastDuePO}}
              </div>
              <div>
                <div class="chart-container-3">
                  <canvas width="274" height="200" class="chartjs-render-monitor" id="myChart2"
                    style="width: auto; display: block;"></canvas>
                </div>
                {{-- <div class="b">
                <i style="color:black; font-size: 12px;">
                  {{$totalPastDuePO7}}
                </i>
                
                <i style="color:black font-size: 12px;">
                  {{$totalPastDuePOLess30}}
                </i>
                
                <i style="color:black font-size: 12px;">
                  {{$totalPastDuePOMore30}}
                </i>
                </div> --}}
                <p> </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
  function chart1(){
        jQuery.ajax({
            type : "get",
            url : "{{URL::to("refExpItem") }}",
            success:function(data){
                var exp1 = data['expitem1'][0]['total'];
                var exp2 = data['expitem2'][0]['total'];
                var exp3 = data['expitem3'][0]['total'];
                var exp4 = data['expitem4'][0]['total'];

                myExpItem.data.datasets[0].data[0] = exp1;
                myExpItem.data.datasets[0].data[1] = exp2;
                myExpItem.data.datasets[0].data[2] = exp3;
                myExpItem.data.datasets[0].data[3] = exp4;
                myExpItem.update();
            }
        });
    }

    function poClickEvent(event, array)
    {
        if(array[0])
        {
            let element = this.getElementAtEvent(event);
            if (element.length > 0) {
                //var series= element[0]._model.datasetLabel;
                //var label = element[0]._model.label;
                //var value = this.data.datasets[element[0]._datasetIndex].data[element[0]._index];

                var clickedDatasetIndex = element[0]._datasetIndex;
                var clickedElementindex = element[0]._index;
                var label = myChart1.data.labels[clickedElementindex];
                var value = myChart1.data.datasets[clickedDatasetIndex].data[clickedElementindex];     
                // alert("Clicked: " + label + " - " + value);
                // window.location = "/pastduepo"; 
                //console.log()

                //alert(label);

                if (label == 'Approved PO')
                {
                  // alert('123');
                 window.location = "/dashboard-approvedPO"; 
                }

                // if (label == 'Unapproved PO')
                // {
                //   // alert('test');
                //  window.location = "/openpo"; 
                // }

                if (label == 'Unapproved > 3 Days')
                {
                  // alert('9999999');
                 window.location = "/dashboard-unapprovedPO3"; 
                }

                if (label == 'Unapproved > 7 Days')
                {
                  // alert('9999999');
                 window.location = "/dashboard-unapprovedPO7"; 
                }

            }
        }
    }

    var ctx = document.getElementById("myChart1").getContext('2d');
    
    var data = 
    {
        datasets: 
        [{
            data: 
            [
            '{{$approvedPO}}', 
            '{{$unApprPOLess3}}',
            '{{$unApprPOMore7}}'
            ],
            backgroundColor: ['#006400','#ffff00','#ff0000','#ff0000'],
        }],
        
          labels: ['Approved PO', 'Unapproved > 3 Days', 'Unapproved > 7 Days'],

    };
    var myChart1 = new Chart(ctx, {
        type: 'pie',
        data: data,
      options: {
        responsive: true,
        onClick: poClickEvent,
        maintainAspectRatio: false,
        tooltips: {
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: true,
          caretPadding: 10,
        },
      scales: {
        xAxes: [{
          scaleLabel: {
            display: false,
            labelString: [' > 3 Days']
          },
          time: {
            unit: 'month'
          },
          gridLines: {
            display: true,
            drawBorder: true
          },
          ticks: {
            maxTicksLimit: 6,
            fontSize : 0 // menyembunyikan label yang berkaitan degan horizontal tau xAxes
          },
          maxBarThickness: 50, // lebar bar vertical
        }],
        
      },
        legend: {
          display: false,
          fontStyle: 'normal',
          fontFamily: 'Arial',
          align: 'center',
          fontSize: 5,
          position: 'top',
          fullWidth: true,
          boxWidth: 40,
        },
        title: { display: false, text: 'Fruit in stock'},
        cutoutPercentage: 1, // lebar bar Pie
        hover: {
          onHover: function(e) {
              var point = this.getElementAtEvent(e);
              if (point.length) e.target.style.cursor = 'pointer';
              else e.target.style.cursor = 'default';
          }
        }
      }
    });
    function noexpitm(event, array){
        if(array[0]){
            let element = this.getElementAtEvent(event);
            if (element.length > 0) {
                //var series= element[0]._model.datasetLabel;
                //var label = element[0]._model.label;
                //var value = this.data.datasets[element[0]._datasetIndex].data[element[0]._index];
                window.location = "/expiredinv";

                //console.log()
            }
        }
    }
    
    function belowStockClickEvent(event, array){
        if(array[0]){
            let element = this.getElementAtEvent(event);
            if (element.length > 0) {
                //var series= element[0]._model.datasetLabel;
                //var label = element[0]._model.label;
                //var value = this.data.datasets[element[0]._datasetIndex].data[element[0]._index];
                window.location = "/safetystock";
                //console.log()
            }
        }
    }

    var ctx = document.getElementById("mySafetyStock");
    var myExpItem = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["1 Month", "2 Months"],
        datasets: [{
          data: ['{{$safetyStock}}', '{{$sentEmailStock}}'],
          backgroundColor: ['red', 'yellow'],
          hoverBackgroundColor: ['#17a673', '#2e59d9'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        responsive: true,
        enable3D: true,
        onClick: belowStockClickEvent,
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: false
        },
        cutoutPercentage: 60,
      },
    });
    
  var ctx = document.getElementById("myexpitm");
    var myMachineDown = new Chart(ctx, {
      type: 'bar',
	  	  
      data: {	  
        labels: ["0 day", "30 days", "90 days", "180 days"],       
		    datasets: [{
          label: "Total",
          backgroundColor: ["red","green","black","orange"],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
		  
          data: [
            '{{$itemExpire}}',
            '{{$itemExpire30}}',
            '{{$itemExpire90}}',
            '{{$itemExpire180}}',
            ]
          }],
      },
       options: {
        responsive: true,
        enable3D: true,
        onClick: noexpitm,
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: false
        },
        scales: {
            yAxes: [{
                display: true,
                ticks: {
                    beginAtZero: true   // minimum value will be 0.
                }
            }]
        },
        cutoutPercentage: 60,
      },
    });

    function poClickEvent2(event, array)
    {
        if(array[0])
        {
            let element = this.getElementAtEvent(event);
            if (element.length > 0) {

                var clickedDatasetIndex = element[0]._datasetIndex;
                var clickedElementindex = element[0]._index;
                var label = myChart2.data.labels[clickedElementindex];
                var value = myChart2.data.datasets[clickedDatasetIndex].data[clickedElementindex];     

                if (label == '1-7 Days')
                {
                  // alert('123');
                 window.location = "/dashboard-pastduepo"; 
                }

                if (label == '8-30 Days')
                {
                  // alert('test');
                 window.location = "/dashboard-pastduepo2"; 
                }

                if (label == '> 30 Days')
                {
                  // alert('9999999');
                 window.location = "/dashboard-pastduepo3"; 
                }
                              
            }
        }
    }

    var ctx = document.getElementById("myChart2").getContext('2d');
    var data = 
    {
        datasets: 
        [{
            data: 
            [
            '{{$totalPastDuePO7}}',
            '{{$totalPastDuePOLess30}}',
            '{{$totalPastDuePOMore30}}'
            ],
            backgroundColor: ['#006400','#ffff00','#ff0000','#ff0000'],
            // hoverBackgroundColor: "#2e59d9",
            // borderColor: "#4e73df",
        }],
        
        labels: ['1-7 Days','8-30 Days', '> 30 Days'],
        // fontColor: 'black'
    };
    var myChart2 = new Chart(ctx, {
        type: 'bar',
        data: data,
      options: {
        responsive: true,
        onClick: poClickEvent2,
        maintainAspectRatio: false,
        tooltips: {
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: true,
          caretPadding: 10,
        },
      scales: {
        xAxes: [{
          time: {
            unit: 'month'
          },
          gridLines: {
            display: true,
            drawBorder: true
          },
          ticks: {
            maxTicksLimit: 6,
            fontSize: 8,
            fontColor: 'black',
            fontStyle: 'bold'
          },
          maxBarThickness: 40, // lebar bar
        }],
        yAxes: [{
          ticks: {
            min: 0,
            // max:,
            fontColor:"#000000",
            maxTicksLimit: 40,
            padding: 10,
          },
          gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [5],
            zeroLineBorderDash: [2]
          }
        }],
      },
        legend: {
          display: false
        },
        cutoutPercentage: 60,
        hover: {
          onHover: function(e) {
              var point = this.getElementAtEvent(e);
              if (point.length) e.target.style.cursor = 'pointer';
              else e.target.style.cursor = 'default';
          }
        }
      }
    });
</script>
@endsection