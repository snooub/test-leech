<?php

class Curl {
	function getSingle($link)
	{
		// Tạo mới một cURL
		$ch = curl_init();

		// Cấu hình cho cURL
		curl_setopt($ch, CURLOPT_URL, $link); // Chỉ định địa chỉ lấy dữ liệu
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36'); // Giả tên trình duyệt $_SERVER['HTTP_USER_AGENT']
		curl_setopt($ch, CURLOPT_HEADER, 0); // Không kèm header của HTTP Reponse trong nội
		curl_setopt($ch, CURLOPT_TIMEOUT, 600); // Định timeout khi curl
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Trả kết quả về ở hàm curl_exec
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Không xác nhận chứng chì ssl

		// Thực thi cURL
		$result = curl_exec($ch);

		// Ngắt cURL, giải phóng
		curl_close($ch);

		return $result;

	}

	function getMulti($links){
		$mh = curl_multi_init();
		foreach($links as $k => $link) {
			$ch[$k] = curl_init();
			curl_setopt($ch[$k], CURLOPT_URL, $link);
			curl_setopt($ch[$k], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36');
			curl_setopt($ch[$k], CURLOPT_HEADER, 0);
			curl_setopt($ch[$k], CURLOPT_TIMEOUT, 0);
			curl_setopt($ch[$k], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch[$k], CURLOPT_SSL_VERIFYPEER, 0);
			curl_multi_add_handle($mh, $ch[$k]);
		}
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while($running > 0);
		foreach($links as $k => $link) {
			$result[$k] = curl_multi_getcontent($ch[$k]);
			curl_multi_remove_handle($mh, $ch[$k]);
		}
		curl_multi_close($mh);
		return join('', $result);

	}

}
