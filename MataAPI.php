<?php

class MataAPI extends CComponent {

    public function init() {
        if (!function_exists('curl_version'))
            throw new CException("Curl is required for " . __CLASS__ . " to function");
    }

    public function get($url, array $params = array(), $authentication = null, $encode = false, $parseJson = true) {
        return $this->callURL("GET", $url, $params, $authentication, $encode, $parseJson);
    }

    public function post($url, array $params = array(), $authentication = null, $encode = false, $parseJson = true) {
        return $this->callURL("POST", $url, $params, $authentication, $encode, $parseJson);
    }

    private function callURL($method, $url, $data = false, $authentication = null, $encode = false, $parseJson = true) {

        $curl = curl_init();
        switch ($method) {
            case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($data, '', '&'));
            break;
            case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
            default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        if ($authentication != null) {
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $authentication);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);

        if($parseJson == false)
            return $response;

        $responseJSON = json_decode($response, false);

        if ($responseJSON == null)
            throw new CHttpException(500, sprintf("Could not perform query to Web Service for %s, \n\rResponse: %s", $url, $response));

        return $responseJSON;
    }

}

?>
