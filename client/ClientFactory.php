<?php
namespace LangridSettingClient\Client;

use \LangridSettingClient\Configurator as Conf;

class ClientFactory {
    var $config;

    public function __construct(Conf\GroupConfig $conf) {
        $this->config = $conf;
    }

    public function getClient($name = 'default') {
        $setting = $this->config->getAvailableSetting($name);

        if ($setting->type == 'langrid') {
            return new Langrid\LangridClient($setting);
        } else if ($setting->type == 'google') {
            return new Google\GoogleClient($setting);
        }

        throw new Exception\LangridSettingException($e);
    }
}
