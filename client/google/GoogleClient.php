<?php
namespace LangridSettingClient\Client\Google;

use \LangridSettingClient\API\Google as API;

class GoogleClient {
    var $setting;

    public function __construct($setting) {
        $this->setting = $setting;
    }

    public function getTranslator() {
        return new API\GoogleTranslator($this->setting);
    }
}
