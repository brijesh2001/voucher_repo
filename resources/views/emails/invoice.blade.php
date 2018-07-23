<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>
<style>
    body{
        font-family: Open Sans, Helvetica, Arial, sans-serif;
        height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;
    }
    hr{
        color: brown;
        background: brown;
        height:1px;
    }
</style>
<body style="background-color:#cccccc;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="480" style="max-width:700px;background:white;padding-right: 10px;">
                <tr>
                    <td align="center">
                        <table align="center" border="0" width="100%">
                            <tr>
                                <td style="color: brown;font-size: 18px;font-weight: bold;">PTE EDU SERVICES</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 11px;font-weight: bold;">
                            <tr>
                                <td class="spacer" height="10" style="font-size: 10px; line-height: 10px; margin: 0; padding: 0; height:10px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>GSTIN</td>
                                <td>24DQNPPD9163D1ZH</td>
                                <td>Invoice Date</td>
                                <td>{{$created_at}}</td>
                            </tr>
                            <tr>
                                <td class="spacer" height="10" style="font-size: 10px; line-height: 10px; margin: 0; padding: 0; height:10px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Invoice No.</td>
                                <td>{{$invoice_number}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="spacer" height="30" style="font-size: 30px; line-height: 30px; margin: 0; padding: 0; height:30px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" border="0"  width="100%">
                            <tr>
                                <td style="width:42%">&nbsp;<hr/>&nbsp;</td>
                                <td style="vertical-align:middle; text-align: center;color: brown;font-size: 18px;">TAX INVOICE</td>
                                <td style="width:41%">&nbsp;<hr/>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="spacer" height="30" style="font-size: 30px; line-height: 30px; margin: 0; padding: 0; height:30px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" border="0" width="100%" style="font-size: 11px;font-weight: bold;">
                            <tr>
                                <td>{{$name}}</td>
                                <td>Billing Address</td>
                                <td>Shipping Address</td>
                            </tr>
                            <tr>
                                <td style="line-height: 18px;">{{$email}}<br>
                                    {{$mobile}}
                                </td>
                                <td style="line-height: 18px;">{{$state_name}},<br>
                                </td>
                                <td style="line-height: 18px;">{{$state_name}}<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="spacer" height="30" style="font-size: 30px; line-height: 30px; margin: 0; padding: 0; height:30px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" border="0" width="100%" style="font-size: 11px;font-weight: bold;">
                            <tr>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">Sr No.</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">Date</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">Item</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">HSN/SAC</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">Taxable Value</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">IGST</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">CGST</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">SGST</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-bottom: 2px solid #cccccc;">Total</td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;">1</td>
                                <td style="padding: 15px;">{{$created_at}}</td>
                                <td style="padding: 15px;">{!!$voucher_code!!}</td>
                                <td style="padding: 15px;">8532</td>
                                <td style="padding: 15px;">{{number_format($rate_before_gst,2)}}</td>
                                <td style="padding: 15px;">{{$igst}}</td>
                                <td style="padding: 15px;">{{$cgst}}</td>
                                <td style="padding: 15px;">{{$sgst}}</td>
                                <td style="padding: 15px;">{{$amount_paid}}</td>
                            </tr>

                            <tr>
                                <td style="padding:0px 15px 15px;"></td>
                                <td style="padding:0px 15px 15px;"></td>
                                <td style="padding:0px 15px 15px;"></td>
                                <td style="padding:0px 15px 15px;">(S)</td>
                                <td style="padding:0px 15px 15px;"></td>
                                <td style="padding:0px 15px 15px;">@if($igst > 0)@18.00%@else 0.00 @endif</td>
                                <td style="padding:0px 15px 15px;">@if($cgst > 0)@9.00%@else 0.00 @endif</td>
                                <td style="padding:0px 15px 15px;">@if($sgst > 0)@9.00%@else 0.00 @endif</td>
                                <td style="padding:0px 15px 15px;">{{$amount_paid}}</td>
                            </tr>
                            <tr>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;"></td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;"></td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;"></td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;"></td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;">{{number_format($rate_before_gst,2)}}</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;">{{$igst}}</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;">{{$cgst}}</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;">{{$sgst}}</td>
                                <td bgcolor="#eeeeee" style="padding: 15px;border-top: 2px solid #cccccc;">{{$amount_paid}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="spacer" height="30" style="font-size: 30px; line-height: 30px; margin: 0; padding: 0; height:30px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="right" border="0" width="100%" style="font-size: 12px;font-weight: bold;text-align: right;font-size: 10px;">
                            <tr>
                                <td>
                                    <table align="left" border="0" width="40%">
                                        <tr>
                                            <td style="padding: 3px 0;">&nbsp;</td>
                                        </tr>
                                    </table>

                                    <table align="left" border="0" width="25%">
                                        <tr>
                                            <td style="padding: 3px 0;">Taxable Amount</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 3px 0;">Total Tax*</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 3px 0;">Invoice Total</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 3px 0;">Invoice Total (In words)</td>
                                        </tr>
                                    </table>
                                    <table align="right" border="0" width="35%">
                                        <tr>
                                            <td style="padding: 3px 0;">{{number_format($rate_before_gst,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 3px 0;">@if($igst > 0){{$igst}}@else {{$cgst*2}}@endif</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 3px 0;">{{$amount_paid}}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 3px 0;">{{$word_amount}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="spacer" height="80" style="font-size: 80px; line-height: 80px; margin: 0; padding: 0; height:80px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom: 80px;">
                        <table align="left" border="0"  width="49%">
                            <tr>
                                <td style="font-size: 10px;font-weight: bold;">
                                    We declare that this invoice shows the actual price of the<br>
                                    services rendered and that all particulars are true and<br>
                                    correct.<br>
                                    Wire transfer Details: YES BANK<br>
                                    Current Account<br>
                                    A/c #: 099763300000184<br>
                                    IFSC: YESB0000007<br>
                                </td>
                            </tr>
                            <tr>
                                <td class="spacer" height="30" style="font-size: 30px; line-height: 30px; margin: 0; padding: 0; height:30px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="color: brown;font-weight: bold;font-size: 14px;">Thank you for your business</td>
                            </tr>
                        </table>

                        <table align="right" border="0"width="49%">
                            <tr>
                                <td class="spacer" height="90" style="font-size: 90px; line-height: 90px; margin: 0; padding: 0; height:90px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid black;">

                                </td>
                            </tr>

                            <tr>
                                <td align="center" style="font-size: 10px;font-weight: bold;padding: 5px 0 0 0;">Digitally Generated No Signature Required</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="spacer" height="70" style="font-size: 100px; line-height: 70px; margin: 0; padding: 0; height:180px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" style="">
                        <table border="0" width="100%">
                            <tr>
                                <td height="2" align="center" style="height:2px;line-height:1px;font-size:1px;background-color:brown;" bgcolor="brown"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td align="center">
                                    <p style="text-align: center;font-size: 10px;">PTE EDU SERVICES<br>
                                        Shahi Kutir Bunglows,27 B/H Vrundavan Party Plot, Nr. Suryam Flora, Nikol, Ahmedabad <br> Email: pteeduservices@gmail.com. Phone : +91 97250 53310</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>