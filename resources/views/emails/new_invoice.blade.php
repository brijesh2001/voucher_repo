<html>
<head>
    <meta charset="utf-8">

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            font-size: 16px;
            line-height: 24px;
            border: 1px solid #eee;
        }

        .invoice-box table {
            width: 100%;
        }
        .gray {
            background-color:gray;
        }
        table.table-fix { table-layout:fixed; }
        table.table-fix td { overflow: hidden; }
        table.table-fix td { word-wrap: break-word; }
    </style>
</head>

<body>
<div class="invoice-box">
    <table class="table-fix" cellpadding="0" cellspacing="0">
        <caption style = "font-size: 22px;font-weight:bold;padding: 10px">TAX INVOICE</caption>
        <thead>
        <tr>
            <td style="text-align:left;">
                <img src="{{url('css/front/img/pteeduservices.jpg')}}" style="width:100%; max-width:300px;">
            </td>
            <td></td>
            <td style="text-align:left;">
                <span style="font-weight:bold"> PTE EDU Service </span><br>
                306, Vishala Supreme, <br>
                Opp. Torrent Power,<br>
                S.P. Ring Road,<br>
                Nikol - 382350 Ahmedabad Gujarat <br>

                <span style="font-weight:bold">GSTIN : <span>

            </td>
        </tr>
        </thead>

        <tr>
            <td style="border:1px solid; padding: 4px;margin-right:10px;width:46%">
                To,<br>
                {{$name}},<br>
                {{$state_name}},<br>
                Phone: {{$mobile}}<br>
                Email: {{$email}}<br>
                GSTIN : {{$gstn or ''}}
            </td>
            <td style="width:5%"></td>
            <td style="border:1px solid; padding: 4px;width:46%">
                Invoice No: {{$invoice_number}}<br>
                Date: {{$created_at}}<br>
            </td>
        </tr>
        <tr>
            <table style="width: 100%; margin-top: 10px; font-size: 0.8em;border-collapse: collapse;" border="1px">
                <tr align="center">
                    <th style="padding:2.5px; width: 55%;border:none;background-color:gray;border-bottom:1px solid">DESCRIPTION</th>
                    <th style="padding:2.5px;border:none;background-color:gray;border-bottom:1px solid">HSN/SAC</th>
                    <th style="padding:2.5px;border:none;background-color:gray;border-bottom:1px solid" >Rate per Item</th>
                    <th style="padding:2.5px;border:none;background-color:gray;border-bottom:1px solid" >AMOUNT</th>
                </tr>
                <tr>
                    <td style="border:none;border-right:1px solid;padding:2.5px;">
                        {!!$voucher_code!!}
                    </td>
                    <td style="text-align:center; padding:10px;border:none;border-right:1px solid">9992</td>
                    <td style="text-align:center;padding:10px;border:none;border-right:1px solid;">{{number_format($rate_before_gst,2)}}</td>
                    <td style="text-align:center;padding:10px;border:none;border-bottom:1px solid">{{number_format($rate_before_gst,2)}}</td>
                </tr>

                <tr>
                    <td style="border:none;border-right:1px solid">Total:</td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="text-align:center;padding:10px;border:none">{{number_format($rate_before_gst,2)}}</td>
                </tr>
                <tr>
                    <td style="border:none;border-right:1px solid;padding:2.5px;">IGST:</td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="text-align:center;padding:10px;border:none">{{$igst}}</td>
                </tr>
                <tr>
                    <td style="border:none;border-right:1px solid;padding:2.5px;">CGST:</td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="text-align:center;padding:10px;border:none">{{$cgst}}</td>
                </tr>
                <tr>
                    <td style="border:none;border-right:1px solid;padding:2.5px;">SGST:</td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="border:none;border-right:1px solid"></td>
                    <td style="text-align:center;padding:10px;border:none">{{$sgst}}</td>
                </tr>


                <tr align="center" class="gray">
                    <th style="padding:2.5px; background-color:gray; width: 55%;border:none;border-top:1px solid">Amount (in words): {{$word_amount}}</th>
                    <th style="padding:2.5px;border:none;background-color:gray;border-top:1px solid"></th>
                    <th style="padding:2.5px;border:none;background-color:gray;border-top:1px solid" ></th>
                    <th style="padding:2.5px;border:none;background-color:gray;border-top:1px solid" >{{$amount_paid}}</th>
                </tr>
            </table>

        </tr>
        <tr>
           <td>
               <table style="margin-top:10px">
                   <thead>
                   <tr>
                       <td style="text-align:left;">
                       </td>
                       <td style="text-align:right;">
                           For PTE EDU Services<br>
                           <img src="{{url('css/front/img/signature.png')}}" style="width:100%; max-width:300px;"><br>
                           Signature of Authorized person
                       </td>
                   </tr>
                   </thead>
               </table>
           </td>
        </tr>


    </table>
</div>
</body>
</html>