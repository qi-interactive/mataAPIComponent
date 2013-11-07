<?php
class MataAPI extends CComponent {

	public function init() {
		if (!function_exists('curl_version')) 
			throw new CException("Curl is required for " . __CLASS__ . " to function");
	}


	public function get($url, array $params = array()) {
		return $this->callURL("GET", $url, $params);
	}

	public function post($url, array $params = array()) {
		return $this->callURL("POST", $url, $params);
	}

	private function callURL($method, $url, $data = false) {
		$curl = curl_init();
		switch ($method) {
			case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);

			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
			case "PUT":
			curl_setopt($curl, CURLOPT_PUT, 1);
			break;
			default:
			if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// if ($authenticate) {
		// 	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// 	curl_setopt($curl, CURLOPT_USERPWD, $userCredentials);
		// }

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		return json_decode(curl_exec($curl));
	}
}

?>