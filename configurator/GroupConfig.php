<?php
namespace LangridSettingClient\Configurator;

abstract class GroupConfig {
    var $groupName;

    public abstract function getGroupConfig();
    abstract function getSetting($settingName);
    abstract protected function getTranslationPathSetting($from, $to, $setting);

    public function __construct($groupName) {
        $this->groupName = $groupName;
    }

    public function getGroupName() {
        return $this->groupName;
    }

    public function getAllSetting() {
        return $this->getGroupConfig()->settings;
    }

    public function getAvailableSetting($specificName = null) {
        $srv = 0;
        $settings = $this->getAllSetting();

        if ($specificName != null) {
            $srv = array_search($specificName, $settings);
        }

        return $this->getSetting($groupConf->settings[$srv]);
    }
}
