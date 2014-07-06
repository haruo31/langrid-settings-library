<?php
namespace LangridSettingClient\Configurator;

use \LangridSettingClient\Client as Cli;

abstract class Configurator {
    const CONFIGURATOR_WITHOUT_MONITOR = 'ConfiguratorWithoutMonitor';
    const CONFIGURATOR_WITH_MONITOR = 'ConfiguratorWithMonitor';
    // static const CONFIGURATOR_WITH_FOO = '';

    var $config;
    protected function __construct(GroupConfig $config) {
        $this->config = $config;
    }

    public static function getInstance($type, GroupConfig $config) {
        if ($type === self::CONFIGURATOR_WITH_MONITOR) {
            return new ConfiguratorWithMonitor($config);
        }
        if ($type === self::CONFIGURATOR_WITHOUT_MONITOR) {
            return new ConfiguratorWithoutMonitor($config);
        }
        return NULL;
    }

    public abstract function getAllSetting();
    public abstract function getAvailableSetting();

    public function selectConfig() {
        $conf = $this->getAvailableConfig();
        if (is_array($conf)) {
            return $conf[0];
        }
        return $conf;
    }

    public function getClient($setting = null) {
        if (! $setting) {
            return Cli\ClientFactory::getClient($this->config->getSetting($this->getAvailableSetting()));
        }
        return Cli\ClientFactory::getClient($this->config->getSetting($setting));
    }
}
