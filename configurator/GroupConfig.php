<?php
namespace LangridSettingClient\Configurator;

abstract class GroupConfig {
    var $groupName;

    abstract protected function getGroupConfig($groupName);
    abstract protected function getSetting($settingName);
    abstract protected function getTranslationPathSetting($from, $to, $setting);

    public function __construct($groupName) {
        $this->groupName = $groupName;
    }

    public function getAvailableSetting($specificName = null) {
        $groupConf = $this->getGroupConfig($this->groupName);

        // TODO: insert dead service detection behavior

        $srv = 0;
        if ($specificName != null) {
            $srv = array_search($specificName, $groupConf->settings);
        }

        return $this->getSetting($groupConf->settings[$srv]);
    }
}
