<?php
namespace LangridSettingClient\Configurator;

require_once dirname(__FILE__) . '/../lib/dol-monitor-service/helper/autoload.php';

class ConfiguratorWithMonitor extends Configurator {

    public function getAllSetting() {
        return $this->config->getAllSetting();
    }

    public function getAvailableSetting() {
        $names = $this->getAllSetting();

        foreach ($names as $name) {
            $res = \ServiceTestController::isOK($name);
            if ($res) {
                return $name;
            }
        }
        return $names[count($names) - 1];
    }
}
