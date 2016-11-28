<?php

namespace SanTran\MobiCard;
use SanTran\MobiCard\Result;
use SanTran\MobiCard\Config;

class MobiCard
{
    function CardPay($pin_card, $card_serial, $type_card, $_order_id, $client_fullname, $client_mobile, $client_email)
    {
        $params = array(
            'func' => Config::$_FUNCTION,
            'version' => Config::$_VERSION,
            'merchant_id' => config('mobicard.MERCHANT_ID'),
            'merchant_account' => config('mobicard.EMAIL_RECEIVE_MONEY'),
            'merchant_password' => MD5(config('mobicard.MERCHANT_ID') . '|' . config('mobicard.MERCHANT_PASSWORD')),
            'pin_card' => $pin_card,
            'card_serial' => $card_serial,
            'type_card' => $type_card,
            'ref_code' => $_order_id,
            'client_fullname' => $client_fullname,
            'client_email' => $client_email,
            'client_mobile' => $client_mobile,
        );
        $post_field = '';
        foreach ($params as $key => $value) {
            if ($post_field != '')
                $post_field .= '&';
            $post_field .= $key . "=" . $value;
        }

        $api_url = Config::$NGANLUONG_URL_CARD_POST;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        $kq = new Result();

        if ($result != '' && $status == 200) {
            $arr_result = explode("|", $result);
            if (count($arr_result) == 13) {
                $kq->error_code = $arr_result[0];
                $kq->merchant_id = $arr_result[1];
                $kq->merchant_account = $arr_result[2];
                $kq->pin_card = $arr_result[3];
                $kq->card_serial = $arr_result[4];
                $kq->type_card = $arr_result[5];
                $kq->order_id = $arr_result[6];
                $kq->client_fullname = $arr_result[7];
                $kq->client_email = $arr_result[8];
                $kq->client_mobile = $arr_result[9];
                $kq->card_amount = $arr_result[10];
                $kq->amount = $arr_result[11];
                $kq->transaction_id = $arr_result[12];

                if ($kq->error_code == '00') {
                    $kq->error_message = "Nạp thẻ thành công, mệnh giá thẻ = " . $kq->card_amount;
                } else {
                    $kq->error_message = $this->GetErrorMessage($kq->error_code);
                }
            }
        } else {
            $kq->error_message = $error;
        }

        return $kq;
    }

    function GetErrorMessage($error_code)
    {
        $arrCode = array(
            '00' => 'Giao dịch thành công',
            '99' => 'Lỗi, tuy nhiên lỗi chưa được định nghĩa hoặc chưa xác định được nguyên nhân',
            '01' => 'Lỗi, địa chỉ IP truy cập API của NgânLượng.vn bị từ chối',
            '02' => 'Lỗi, tham số gửi từ merchant tới NgânLượng.vn chưa chính xác (thường sai tên tham số hoặc thiếu tham số)',
            '03' => 'Lỗi, Mã merchant không tồn tại hoặc merchant đang bị khóa kết nối tới NgânLượng.vn',
            '04' => 'Lỗi, Mã checksum không chính xác (lỗi này thường xảy ra khi mật khẩu giao tiếp giữa merchant và NgânLượng.vn không chính xác, hoặc cách sắp xếp các tham số trong biến params không đúng)',
            '05' => 'Tài khoản nhận tiền nạp của merchant không tồn tại',
            '06' => 'Tài khoản nhận tiền nạp của merchant đang bị khóa hoặc bị phong tỏa, không thể thực hiện được giao dịch nạp tiền',
            '07' => 'Thẻ đã được sử dụng ',
            '08' => 'Thẻ bị khóa',
            '09' => 'Thẻ hết hạn sử dụng',
            '10' => 'Thẻ chưa được kích hoạt hoặc không tồn tại',
            '11' => 'Mã thẻ sai định dạng',
            '12' => 'Sai số serial của thẻ',
            '13' => 'Mã thẻ và số serial không khớp',
            '14' => 'Thẻ không tồn tại',
            '15' => 'Thẻ không sử dụng được',
            '16' => 'Số lần thử (nhập sai liên tiếp) của thẻ vượt quá giới hạn cho phép',
            '17' => 'Hệ thống Telco bị lỗi hoặc quá tải, thẻ chưa bị trừ',
            '18' => 'Hệ thống Telco bị lỗi hoặc quá tải, thẻ có thể bị trừ, cần phối hợp với NgânLượng.vn để tra soát',
            '19' => 'Kết nối từ NgânLượng.vn tới hệ thống Telco bị lỗi, thẻ chưa bị trừ (thường do lỗi kết nối giữa NgânLượng.vn với Telco, ví dụ sai tham số kết nối, mà không liên quan đến merchant)',
            '20' => 'Kết nối tới telco thành công, thẻ bị trừ nhưng chưa cộng tiền trên NgânLượng.vn');

        return $arrCode[$error_code];
    }

    function GetErrorMessageV2($error_code)
    {
        $arrCode = array(
            '00' => 'Thành công',
            '01' => 'Lỗi chưa xác minh',
            '05' => 'Mã thẻ nạp không đúng hoặc đã được sử dụng',
            '06' => 'Lỗi kết nối với hệ thống xác thực thẻ',
            '07' => 'Tài khoản nhận tiền nạp không tồn tại',
            '08' => 'Tài khoản truy cập hệ thống nạp thẻ tạm thời bị khóa',
            '09' => 'Khách hàng đang nạp thẻ bị khóa (do nhập sai mã thẻ liên tiếp)',
            '10' => 'Không nạp được tiền vào tài khoản NgânLượng.vn',
            '11' => 'Hệ thống NgânLượng.vn không sinh được phiếu thu',
            '12' => 'Phiếu thu tại NgânLượng.vn không cập nhật được trạng thái Đã thu tiền',
            '13' => 'Không chuyển tiền được vào tài khoản NgânLượng.vn của người nhận',
        );
        return $arrCode[$error_code];
    }

}
