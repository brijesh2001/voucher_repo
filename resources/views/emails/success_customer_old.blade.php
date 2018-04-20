<!DOCTYPE html>
<html>

<body>
<table style="text-align:center; border:2px solid #ff472e; width:536px; margin:0 auto; background-color:#f1f0f0;">
    <tr>
        <td>
            <table style="width:536px; font-size:20px;">
                <tr>
                    <td style="border-bottom:5px solid #008fd5; padding:20px 0; background-color:#CCC;" ><img src={{url('css/front/images/logo.png')}} /></td>
                </tr>
                <tr>
                    <td>
                        <p>Dear {{$successData['name']}},</p>
                        <p>Thanks for purchasing discounted PTE Exam voucher through<a href="#"> ptevouchercode.com</a></p>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table style=" width:536px; padding-bottom:20px; font-size:20px;">
                <tr style="text-align:left;">
                    <td width="200">Your Voucher Code</td>
                    <td><b>{{$successData['voucher_to_send']}}</b></td>
                </tr>
                <tr style="text-align:left;">
                    <td>Voucher Value</td>
                    <td><b>Rs. {{$successData['amount_paid']}}</b></td>
                </tr>
                <tr style="text-align:left;">
                    <td>Issue Date</td>
                    <td><b>{{$successData['date']}}</b></td>
                </tr>
                <tr style="text-align:left;">
                    <td>Expiry Date</td>
                    <td><b>11 Month from the Date Of Issue</b></td>
                </tr>
                <tr style="text-align:left;">
                    <td>Transaction Id</td>
                    <td><b>{{$successData['payment_id']}}</b></td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table style="width:536px; text-align:left; border-top:5px solid #c1272d; padding-top:15px; padding-bottom:10px; font-size:20px;">
                <tr>
                    <th>How to Book It  ?</th>
                </tr>
                <tr>
                    <td>Go to <a href="#">PTE Website</a></td>
                </tr>
                <tr>
                    <td>Create an Account</td>
                </tr>
                <tr>
                    <td>Book your preferred date and location</td>
                </tr>
                <tr>
                    <td>Enter Voucher code in Payment section</td>
                </tr>
                <tr>
                    <td>Then Finish registration without Payment</td>
                </tr>
                <tr>
                    <td style="font-size: small;"> * Valid Till 11 Months From Date of Issue.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table style="width:536px; background-color:#ccc; border-top:5px solid #6d9d31; font-size:20px;">
                <tr style="text-align:left;">
                    <td><p>Cheers,<br />Pte Voucher<br />Customer Success<br /><a href="#">ptevouchercode.com</a></p></td>
                </tr>
            </table>
        </td>
    </tr>


</table>


</body>
</html>