<?php
namespace LangridSettingClient\API;

interface Translator {
    public function translate($from, $to, $text, $dictionary);
    public function backTranslate($from, $to, $text, $dictionary);
}
