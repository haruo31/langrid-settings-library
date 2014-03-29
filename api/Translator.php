<?php
namespace LangridSettingClient\API;

interface Translator {
    public function translate($from, $to, $text);
}
