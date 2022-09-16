{{-- @component('mail::message')

    @component('mail::table')
    | NAME             | Email               |
    | ---------------- | -------------------:|
    | {{ $user_name }} |  {{ $user_email }}  |
    @endcomponent
    <br>
    @component('mail::table')
    | Contact Number                 | Country             |
    | ------------------------------ | -------------------:|
    | +{{ $dial_code . $user_phone }} | {{ $country_name }} |
    @endcomponent
    <br>
    @component('mail::table')
    | Message                 |
    | ----------------------: |
    | {{ $user_requirement }} |
    @endcomponent

Thanks,<br>
CREDIFANA
    
@endcomponent

@component('mail::footer')
    @ 2022 Credifana. All right reserved.
@endcomponent --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Inquiry</title>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=PT+Sans&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'PT Sans', sans-serif;
        margin: 0;
        background-color: grey;
    }

    .template {
        max-width: 600px;
        margin: auto;
    }

    .template .table {
        border-collapse: collapse;
        width: 100%;
        background-color: white;
        border-radius: 20px;
        overflow: hidden;
    }

    .template .table thead {
        border-bottom: 6px solid #f0f0f0;
    }

    .template .table .mail-img {
        height: 120px;
        background-color: #748eff;
    }

    .template .table .mail-img img {
        transform: translateY(19px);
    }

    .template .table .mail-heading h1 {
        color: #003380;
        font-size: 38px;
        line-height: 51px;
        font-weight: normal;
        text-transform: uppercase;
        margin-bottom: 0;
    }

    .template .table .sub-heading h3 {
        font-size: 16px;
        color: #003380;
        font-weight: normal;
        line-height: 19px;
        margin-top: 5px;
        margin-bottom: 0;
    }

    .template .table .date-time {
        padding-bottom: 5px;
    }

    .template .table .page-location {
        padding-bottom: 25px;
    }

    .template .table .date-time h6 , .template .table .page-location h6{
        display: inline-block;
        text-align: center;
        font-size: 14px;
        color: #003380;
        font-weight: normal;
        line-height: 19px;
        margin: 0;
    }

    .template .table tbody .dummy {
        width: 30px;
    }

    .template .table tbody .dummy-row td {
        padding: 8px;
    }

    .template .table .separator {
        height: 50px;
    }

    @media (max-width:767px) {
        .template .table .separator {
            height: 25px;
        }
    }

    .template .table tbody th.body-header h2 {
        color: #003380;
        text-align: left;
        font-size: 20px;
        font-weight: bold;
        line-height: 27px;
        text-transform: uppercase;
    }

    .template .table tbody .head th {
        text-align: left;
        font-size: 12px;
        line-height: 16px;
        color: #444444;
        text-transform: uppercase;
    }

    .template .table tbody .content td {
        text-align: left;
        color: #444444;
        font-size: 13px;
        font-weight: normal;
        line-height: 21px;
        width: 50%;
    }

    .template .table tfoot {
        height: 90px;
        background-color: #adadad;
    }

    .template .table tfoot .footer {
        padding: 0px 30px;
    }

    .template .table tfoot .footer img {
        vertical-align: middle;
    }

    .template .table tfoot .footer span {
        font-size: 16px;
        font-weight: normal;
        line-height: 21px;
        display: inline-block;
        color: #f2f7ff;
    }


    .template .table tbody tr.contain td:last-child,
    .template .table tbody tr.contain th:last-child {
        padding-right: 30px;
    }

    @media (max-width: 500px) {
        .template .table .mail-img {
            height: 65px;
        }

        .template .table .mail-heading h1 {
            font-size: 22px;
            margin: 12px 0px 4px;
            line-height: normal;
        }

        .template .table .date-time {
            padding-bottom: 18px
        }

        .template .table tbody th.body-header h2 {
            font-size: 14px;
            margin: 0px;
        }
    }
    </style>

</head>

<body>
    <div class="template">
        <table class="table" border="0">
            <thead>
                <tr>
                    <th class="mail-img" colspan="2">
                        <img src="{{ asset('images/mail-box.png') }}" alt="Mail" style="height:100%">
                    </th>
                </tr>
                <tr>
                    <th class="mail-heading" colspan="2">
                        <h1>Contact Enquiry</h1>
                    </th>
                </tr>
                <tr>
                    <th class="date-time" colspan="2">
                        <h6><?php echo date('jS F Y  h:i A') ?></h6>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td colspan="2">
                        <div style="padding-left:15px; padding-right:15px;">
                            <table style="width:100%">
                                <tr class="contain">
                                    <th class="body-header" colspan="2">
                                        <h2>
                                            Personal Information
                                        </h2>
                                    </th>
                                </tr>
                                <tr class="head contain">
                                    <th>NAME</th>
                                    <th>Email</th>
                                </tr>
                                <tr class="content contain">
                                    <td><?php echo $user_name ?? 'NaN'; ?></td>
                                    <td style="word-break: break-all;"><?php echo $user_email ?? 'NaN'; ?></td>
                                </tr>
                                <tr class="dummy-row">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="head contain">
                                    <th>Contact Number</th>
                                    <th>Country</th>
                                </tr>
                                <tr class="content contain">
                                    <td><?php echo $user_phone ? '+'.$dial_code.' '.$user_phone : 'NaN'; ?> </td>
                                    <td><?php echo $country_name ?? 'NaN' ?></td>
                                </tr>
                                <tr class="dummy-row">
                                    <td colspan="2"></td>
                                </tr>

                                <tr class="head contain">
                                    <th>Message</th>
                                </tr>
                                <tr class="content contain">
                                    <td colspan="2">
                                        <?php echo $user_requirement ?? "NaN"; ?>
                                    </td>
                                </tr>

                                <tr class="dummy-row">
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

            </tbody>

            <tfoot>
                <tr>
                    <td class="footer">
                        <img src="{{ asset('images/logo.png') }}" alt="Credifana logo" width="30" height="30" />
                        <span style="color:#2c2c2c;font-weight:bold;margin-top:8px;font-size:16px;">Credifana@2021</span>
                    </td>
                    <td style="font-size:15px; text-align:left">
                        <address>
                            CREDIFANA
                        </address>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>