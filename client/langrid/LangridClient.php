<?php
namespace LangridSettingClient\Client\Langrid;

use \LangridSettingClient\API\Langrid as L;

class LangridClient {
    var $setting;

    public function __construct($setting) {
        $this->setting = $setting;
    }

    public function getTranslator() {
        return new L\LangridTranslator($this->setting);
    }
}
