<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
@include('Navigation.app')
<style>
    html,
    body {
        margin: 0;
        padding: 0;
        overflow: scroll;
    }

    h1 {
        font-family: helvetica, arial, sans-serif;
        width: 100%;
        font-size: 40px;
        font-weight: bold;
        color: white;
        text-align: left;
        left: 0;
        right: 0;
        margin: 0;
        background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
        background-size: cover;
        background-repeat: no-repeat;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    @media (min-width: 2700px) and (max-width: 3000px) {
        #datatable {
            transform: scale(1.16);
            margin-top: 85px !important;
            margin-left: 200px !important;
            width: 2440px !important;
        }

        table.dataTable th {
            font-size: 23px !important;
        }

        table.dataTable td {
            font-size: 25px !important;
        }

        .footer-wrap {
            margin-top: 110px !important;
            padding: 20px !important;
            font-weight: 100 !important;
            font-size: 27px !important;
        }

        .h4 {
            font-size: 34px !important;
            margin-top: -20px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 58px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 2500px) and (max-width: 2700px) {
        #datatable {
            transform: scale(1.1);
            margin-top: 55px !important;
            margin-left: 115px !important;
            width: 2290px !important;
        }

        table.dataTable th {
            font-size: 21px !important;
        }

        table.dataTable td {
            font-size: 23px !important;
        }

        .footer-wrap {
            margin-top: 75px !important;
            padding: 18px !important;
            font-weight: 100 !important;
            font-size: 24px !important;
        }

        .h4 {
            font-size: 30px !important;
            margin-top: -20px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 52px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 2100px) and (max-width: 2500px) {
        #datatable {
            transform: scale(1.09);
            margin-top: 50px !important;
            margin-left: 100px !important;
            width: 2160px !important;
        }

        table.dataTable th {
            font-size: 20px !important;
        }

        table.dataTable td {
            font-size: 22px !important;
        }

        .footer-wrap {
            margin-top: 45px !important;
            padding: 16px !important;
            font-weight: 100 !important;
            font-size: 24px !important;
        }

        .h4 {
            font-size: 30px !important;
            margin-top: -20px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 49px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 2100px) and (max-width: 2200px) {
        #datatable {
            transform: scale(1.05);
            margin-top: 25px !important;
            margin-left: 50px !important;
            width: 2000px !important;
        }

        table.dataTable th {
            font-size: 20px !important;
        }

        table.dataTable td {
            font-size: 20px !important;
        }

        .footer-wrap {
            margin-top: 25px !important;
            padding: 16px !important;
            font-weight: 100 !important;
            font-size: 20px !important;
        }

        .h4 {
            font-size: 30px !important;
            margin-top: -20px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 44px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 1800px) and (max-width: 2000px) {
        .footer-wrap {
            margin-top: 28px !important;
            padding: 16px !important;
            font-weight: 100 !important;
            font-size: 18px !important;
        }

        .h4 {
            font-size: 24px !important;
            /* margin-top: -20px !important; */
        }
    }

    @media (max-width: 1800px) {
        #datatable {
            transform: scale(0.9);
            margin-top: -35px !important;
            margin-left: -93px !important;
            width: 1890px !important;
        }

        .footer-wrap {
            margin-top: -8px !important;
            padding: 16px !important;
            font-weight: 100 !important;
            font-size: 16px !important;
        }

        .h4 {
            font-size: 22px !important;
            margin-top: -10px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 36px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 1600px) {
        #datatable {
            transform: scale(0.8);
            margin-top: -75px !important;
            margin-left: -185px !important;
            width: 1870px !important;
        }

        .footer-wrap {
            margin-top: -50px !important;
            padding: 12px !important;
            font-weight: 100 !important;
            font-size: 14px !important;
        }

        .h4 {
            font-size: 25px !important;
            margin-top: -18px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 31px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 1400px) and (max-width: 1500px) {
        #datatable {
            transform: scale(0.68);
            margin-top: -120px !important;
            margin-left: -320px !important;
            width: 1980px !important;
        }

        .footer-wrap {
            margin-top: -115px !important;
            padding: 12px !important;
            font-weight: 100 !important;
            font-size: 12px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 26px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 1300px) and (max-width: 1400px) {
        #datatable {
            transform: scale(0.69);
            margin-top: -120px !important;
            margin-left: -300px !important;
            width: 1960px !important;
        }

        .footer-wrap {
            margin-top: -115px !important;
            padding: 12px !important;
            font-weight: 100 !important;
            font-size: 12px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 26px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 1300px) {
        #datatable {
            transform: scale(0.67);
            margin-top: -120px !important;
            margin-left: -300px !important;
            width: 1845px !important;
        }

        .footer-wrap {
            margin-top: -115px !important;
            padding: 12px !important;
            font-weight: 100 !important;
            font-size: 12px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 26px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 1200px) {
        #datatable {
            transform: scale(0.56);
            margin-top: -160px !important;
            margin-left: -415px !important;
            width: 1890px !important;
        }

        .footer-wrap {
            margin-top: -152px !important;
            padding: 12px !important;
            font-weight: 100 !important;
            font-size: 10px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 22px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (min-width: 1180px) and (max-width: 1200px) {
        #datatable {
            transform: scale(0.48);
            margin-top: -230px !important;
            margin-left: -620px !important;
            width: 2360px !important;
        }

        table.dataTable th {
            font-size: 24px !important;
        }

        table.dataTable td {
            font-size: 24px !important;
        }

        .footer-wrap {
            margin-top: -40px !important;
            padding: 10px !important;
            font-weight: 100 !important;
            font-size: 13px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 1024px) {
        #datatable {
            transform: scale(0.44);
            margin-top: -290px !important;
            margin-left: -660px !important;
            width: 2300px !important;
        }

        table.dataTable th {
            font-size: 26px !important;
        }

        table.dataTable td {
            font-size: 28px !important;
        }

        .footer-wrap {
            margin-top: -180px !important;
            padding: 10px !important;
            font-weight: 100 !important;
            font-size: 13px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 992px) {
        #datatable {
            transform: scale(0.4);
            margin-top: -270px !important;
            margin-left: -690px !important;
            width: 2300px !important;
        }

        table.dataTable th {
            font-size: 22px !important;
        }

        table.dataTable td {
            font-size: 22px !important;
        }

        .footer-wrap {
            margin-top: -265px !important;
            padding: 10px !important;
            font-weight: 100 !important;
            font-size: 9px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 19px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 915px) {
        #datatable {
            transform: scale(0.25);
            margin-top: -540px !important;
            margin-left: -1330px !important;
            width: 3530px !important;
        }

        table.dataTable th {
            font-size: 40px !important;
        }

        table.dataTable td {
            font-size: 36px !important;
        }

        .footer-wrap {
            margin-top: -553px !important;
            padding: 4px !important;
            font-weight: 100 !important;
            font-size: 6px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-align: left;
            margin-top: -10px !important;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 844px) {
        #datatable {
            transform: scale(0.22);
            margin-top: -565px !important;
            margin-left: -1400px !important;
            width: 3600px !important;
        }

        table.dataTable th {
            font-size: 40px !important;
        }

        table.dataTable td {
            font-size: 36px !important;
        }

        .footer-wrap {
            margin-top: -560px !important;
            padding: 6px !important;
            font-weight: 100 !important;
            font-size: 7px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 820px) {
        #datatable {
            transform: scale(0.2);
            margin-top: -570px !important;
            margin-left: -1390px !important;
            width: 3500px !important;
        }

        table.dataTable th {
            font-size: 40px !important;
        }

        table.dataTable td {
            font-size: 36px !important;
        }

        .footer-wrap {
            margin-top: -575px !important;
            padding: 6px !important;
            font-weight: 100 !important;
            font-size: 7px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 15px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 800px) {
        #datatable {
            transform: scale(0.21);
            margin-top: -570px !important;
            margin-left: -1390px !important;
            width: 3500px !important;
        }

        table.dataTable th {
            font-size: 40px !important;
        }

        table.dataTable td {
            font-size: 36px !important;
        }

        .footer-wrap {
            margin-top: -575px !important;
            padding: 6px !important;
            font-weight: 100 !important;
            font-size: 7px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 15px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 740px) {
        #datatable {
            transform: scale(0.18);
            margin-top: -715px !important;
            margin-left: -1650px !important;
            width: 4000px !important;
        }

        table.dataTable th {
            font-size: 44px !important;
        }

        table.dataTable td {
            font-size: 44px !important;
        }

        .footer-wrap {
            margin-top: -720px !important;
            padding: 2px !important;
            font-weight: 100 !important;
            font-size: 7px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 717px) {
        #datatable {
            transform: scale(0.19);
            margin-top: -720px !important;
            margin-left: -1470px !important;
            width: 3600px !important;
        }

        table.dataTable th {
            font-size: 44px !important;
        }

        table.dataTable td {
            font-size: 44px !important;
        }

        .footer-wrap {
            margin-top: -650px !important;
            padding: 6px !important;
            font-weight: 100 !important;
            font-size: 7px !important;
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 15px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 653px) {
        #datatable {
            transform: scale(0.15);
            margin-top: -710px !important;
            margin-left: -1650px !important;
            width: 3900px !important;
        }

        table.dataTable th {
            font-size: 42px !important;
        }

        table.dataTable td {
            font-size: 42px !important;
        }

        .footer-wrap {
            margin-top: -693px !important;
            padding: 3px !important;
            font-weight: 100 !important;
            font-size: 5px !important;
        }

        h1 {
            margin-top: -15px !important;
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 512px) {
        #datatable {
            transform: scale(0.14);
            margin-top: -720px !important;
            margin-left: -1560px !important;
            width: 3590px !important;
        }

        table.dataTable th {
            font-size: 42px !important;
        }

        table.dataTable td {
            font-size: 42px !important;
        }

        .footer-wrap {
            margin-top: -290px !important;
            padding: 3px !important;
            font-weight: 100 !important;
            font-size: 10px !important;
        }

        h1 {
            margin-top: -15px !important;
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    @media (max-width: 480px) {
        #datatable {
            transform: scale(0.12);
            margin-top: -735px !important;
            margin-left: -1710px !important;
            width: 3850px !important;
        }

        table.dataTable th {
            font-size: 42px !important;
        }

        table.dataTable td {
            font-size: 42px !important;
        }

        .footer-wrap {
            margin-top: -740px !important;
            padding: 0px !important;
            font-weight: 100 !important;
            font-size: 4px !important;
        }

        h1 {
            margin-top: -15px !important;
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    table.dataTable thead {
        background-color: #d3d3d3;
    }

    table.dataTable th {
        font-size: 18px;
    }

    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 1px 1px;
    }

    table.dataTable td {
        font-size: 18px;
    }

    .table-striped tbody tr.highlight {
        background-color: #a1b7e5 !important;
    }

    .text-right {
        text-align: right;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background: #ffffff;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">HOURLY EFFICIENCY MONITOR ON {{ Carbon\Carbon::now()->format('d/m/Y') }}
            ({{ Carbon\Carbon::now()->format('l') }})</h4>

        <div style="padding-left: 20px; padding-right: 20px;">
            <div>
                <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align: center;">TIME</th>
                            <th style="text-align: center;">HOURLY TARGET (TARGET)</th>
                            <th style="text-align: center;">DAILY TARGET (PACK)</th>
                            <th style="text-align: center;">HOURLY TOTAL (ACTUAL)</th>
                            <th style="text-align: center;">TODAY'S TOTAL (PACK)</th>
                            <th style="text-align: center;">ACHIEVEMENT (PACK)</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('Navigation.footer')

    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            function widthResizer() {
                var width = window.innerWidth
                console.log(width)
            }

            // widthResizer();

            function getCurrentHour() {
                var currentdate = new Date();
                var currentHour = currentdate.getHours();
                var formattedHour = currentHour.toString().padStart(2, '0') + ':00:00';
                return formattedHour;
            }

            function initializeDataTable() {
                return $('#datatable').DataTable({
                    ajax: {
                        type: 'GET',
                        url: '/productionHourlyDashAjax/',
                    },
                    columns: [{
                            data: 'TIME',
                            className: 'text-right'
                        },
                        {
                            data: 'HOURLY TARGET (TARGET)',
                            className: 'text-right'
                        },
                        {
                            data: 'DAILY TARGET (PACK)',
                            className: 'text-right'
                        },
                        {
                            data: 'HOURLY TOTAL (ACTUAL)',
                            className: 'text-right'
                        },
                        {
                            data: 'TODAY\'S TOTAL (PACK)',
                            className: 'text-right'
                        },
                        {
                            data: 'ACHIEVEMENT (PACK)',
                            className: 'text-right'
                        }
                    ],
                    paging: false,
                    info: false,
                    searching: false,
                    lengthChange: false,
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 1, 2, 3, 4, 5]
                    }],
                    rowCallback: function(row, data) {
                        var currentHour = getCurrentHour();
                        console.log('rowCallback - currentHour:',
                        currentHour); // Log current hour in rowCallback
                        var highlightClass = data['TIME'] === currentHour ? 'highlight' : '';
                        if (highlightClass) {
                            $(row).addClass(highlightClass);
                        }
                    },
                    createdRow: function(row, data) {
                        var currentHour = getCurrentHour();
                        console.log('createdRow - currentHour:',
                        currentHour); // Log current hour in createdRow
                        var achievement = data['ACHIEVEMENT (PACK)'];

                        if (data['TIME'] === currentHour) {
                            if (parseFloat(achievement) < 0) {
                                $('td', row).eq(5).css({
                                    'background-color': 'red',
                                    'color': 'white',
                                    'font-weight': '800'
                                });
                            } else {
                                $('td', row).eq(5).css({
                                    'color': 'red',
                                });
                            }
                        }
                    }
                });
            }

            // console.log('getCurrentHour()', "{{ $currentHour }}");
            var table = initializeDataTable();


        });

        // $.ajax({
        //     type: 'GET',
        //     url: '/productionHourlyDash/',
        //     success: function(response) {
        //         console.log('response', response);
        //     },
        //     error: function(xhr, status, error) {
        //         console.error(error);
        //     }
        // });
    </script>
    </body>

    </html>
