<?php
namespace LangridSettingClient\Configurator;

class ConfiguratorWithoutMonitor extends Configurator {

    public function getAllSetting() {
        return $this->config->getAllSetting();
    }

    public function getAvailableSetting() {
        $names = $this->getAllSetting();
        return $names[0];
    }
}
