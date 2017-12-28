# Laravel 5 MobiCard
Tích hợp thanh toán thẻ cào qua Ngân Lượng cho website Laravel 5.2 tở lên.
======================

[![Total Downloads](https://img.shields.io/packagist/dt/santran/mobicard.svg)](https://packagist.org/packages/santran/mobicard)
[![Paypal Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](http://paypal.me/MrSanTran)

-----
**Install with composer**. 

Install (Laravel)
-----------------
Install via composer
```
composer require santran/mobicard:dev-master
```

Add Service Provider to `config/app.php` in `providers` section
```php
SanTran\MobiCard\MobiCardServiceProvider::class,
```

Add Facade to `config/app.php` in `aliases` section:
```php 
'MobiCard' => SanTran\MobiCard\MobiCardFacade::class,
```

Publish config file, open console and enter bellow command:
```php
php artisan vendor:publish
```
Config file 'mobicard.php' will be copy to config/smartlogs.php, you can change any config on that file for MobiCard
'MERCHANT_ID' => "36680",
'MERCHANT_PASSWORD' => "matkhauketnoi",
'EMAIL_RECEIVE_MONEY' => "demo@nganluong.vn"

How to use ?
Open your Controller.
Add this line on above file, remember after 'namespace ...' keywork:
```php
use MobiCard;
```
on function
```php
$serial = $request->get('serial', "");
$pin = $request->get('pin', "");
$type = $request->get('select_method', "");

$arytype = array(92 => 'VMS', 93 => 'VNP', 107 => 'VIETTEL', 120 => 'GATE');
//Tiến hành kết nối thanh toán Thẻ cào.
$coin1 = rand(10, 999);
$coin2 = rand(0, 999);
$coin3 = rand(0, 999);
$coin4 = rand(0, 999);
$ref_code = $coin4 + $coin3 * 1000 + $coin2 * 1000000 + $coin1 * 100000000;

$rs = MobiCard::CardPay($pin, $serial, $type, $ref_code, "", "", "");

if ($rs->error_code == '00') {
    // Cập nhật data tại đây
    echo '<script>alert("Bạn đã nạp thành công ' . $rs->card_amount . ' vào trong tài khoản.");</script>'; //$total_results;
} else {
    echo '<script>alert("Lỗi :' . $rs->error_message . '");</script>';
}
```
on your views.
```html
<table align="center">
    <tr>
        <td colspan="3">
            <table>
                <tr>
                    <td style="padding-left:0px;padding-top:5px" align="right" ><label for="92"><img  src="includes/images/mobifone.jpg" /></label> </td>
                    <td style="padding-left:10px;padding-top:5px"><label for="93"><img  src="includes/images/vinaphone.jpg" /></label></td>
                    <td style="padding-top:5px;padding-left:5px" align="left"><label for="107"><img  src="includes/images/viettel.jpg" width="110" height="35" /></label></td>
                    <td style="padding-top:5px;padding-left:5px" align="left"> <label for="120"><img width="100" height="35" src="includes/images/gate.jpg"></label></td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom:0px;">
                        <input type="radio" name="select_method" checked="true" value="VMS" id="92"  />
                    </td>
                    <td align="center" style="padding-bottom:0px;padding-left:5px">
                        <input type="radio"  name="select_method" value="VNP" id="93" />
                    </td>
                    <td align="center" style="padding-bottom:0px;padding-right:0px">
                        <input type="radio"  name="select_method" value="VIETTEL" id="107" />
                    </td>

                    <td align="center" style="padding-bottom:0px;padding-right:0px">
                        <input type="radio" id="120" value="GATE" name="select_method">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right" style="padding-bottom:10px">Số Seri :</td>
        <td colspan="2"><input type="text" id="serial" name="serial" style="height:25px;width:200px" /></td>
    </tr>
    <tr>
        <td align="right">Mã số thẻ : </td>
        <td colspan="2">
            <input type="text" id="pin" name="pin" style="height:25px;width:200px" />

        </td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="padding-bottom:10px;padding-right:10px">
            <input type="submit" id="ttNganluong" name="NLNapThe" value="Nạp Thẻ"  /> 
        </td>
    </tr>	
</table>
``` 
                                
Any Q/A, Please contact to me.
Email: laptrinhvien2013@gmail.com
