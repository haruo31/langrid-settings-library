<?php
namespace LangridSettingClient\Google;

class GoogleClient implements LangridSettingClient\Client{
    var $setting;

    public function __construct($setting) {
        $this->setting = $setting;
    }

    public function getTranslator() {
        return new GoogleTranslator($this->setting);
    }
}
