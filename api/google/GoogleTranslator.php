<?php
namespace LangridSettingClient\API\Google;

use LangridSettingClient\API as API;

class GoogleTranslator implements API\Translator {
    var $setting;
    var $response;

    public function __construct($setting) {
        $this->setting = $setting;
    }

    public function translate($from, $to, $text, $dictionary = array()) {

        $res = json_decode($this->sendRequest($from, $to, $text), true);
        if (@$res['error']) {
            throw new API\Exception\ApiException($res['error']['message']);
        }

        return @$res['data']['translations']['translatedText'];
    }

    protected function sendRequest($from, $to, $text) {
        // see https://developers.google.com/translate/v2/using_rest
        $s = curl_init();

        $this->initResponseData();
        curl_setopt_array($s,
            array(
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_URL => $this->setting->url,
                CURLOPT_POST => true,
                CURLOPT_WRITEFUNCTION => array($this, 'curlResponseReader'),
                CURLOPT_HTTPHEADER => array('X-HTTP-Method-Override: GET'),
                CURLOPT_POSTFIELDS => $this->buildParameters(
                        array(
                            'key' => $this->setting->apiKey,
                            'source' => $from,
                            'target' => $to,
                            'q' => $text))));

        $res = curl_exec($s);

        if ($res == TRUE) {
            return $this->getResponseData();
        }

        throw new GoogleTranslationException('Network error.');
    }

    private function curlResponseReader($s, $data) {
        $this->response .= $data;
        return strlen($data);
    }

    private function initResponseData() {
        $this->response = null;
    }

    private function getResponseData() {
        return $this->response;
    }

    protected function buildParameters($params) {
        $r = array();

        foreach($params as $k => $v) {
            $r[] = rawurlencode($k) . '=' . rawurlencode($v);
        }

        return implode('&', $r);
    }

    public function backTranslate($from, $to, $text, $dictionary = array()) {
        $res = new stdObject();

        $res->intermediate = $this->translate($from, $to, $text);
        $res->target = $this->translate($to, $from, $res->intermediate);

        return $res;
    }
}
