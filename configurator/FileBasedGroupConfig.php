<?php
namespace LangridSettingClient\Configurator;

if (! defined('CONFIG_ROOT')) {
    define('CONFIG_ROOT', dirname(__FILE__) . '/../config');

}

class FileBasedGroupConfig extends GroupConfig {
    var $groupConfig = NULL;

    function getGroupConfig() {
        $groupName = $this->getGroupName();
        if ($this->groupConfig === NULL) {
            $res = file_get_contents(CONFIG_ROOT . "/" . $groupName . ".group.json");
            if ($res !== FALSE) {
                $this->groupConfig = json_decode($res);
            }
        }

        return $this->groupConfig;
    }

    function getSetting($settingName) {
        $res = file_get_contents(CONFIG_ROOT . "/" .
                                 $settingName . ".settings.json");
        if ($res !== FALSE) {
            return json_decode($res);
        }

        return FALSE;
    }

    protected function getTranslationPathSetting($from, $to, $settingName) {
        $result = $this->getSetting($settingName);

        for ($i = 0; $i < count($result->paths); $i ++ ) {
            if ($result->paths[$i]->source_lang == $from &&
                $result->paths[$i]->target_lang == $to) {
                return $result->paths[$i];
            }
        }
        return null;
    }
}
