<?php
namespace LangridSettingClient\Client;

use \LangridSettingClient\Configurator as Conf;

class ClientFactory {

    public static function getClient($setting) {

        if ($setting->type == 'langrid') {
            return new Langrid\LangridClient($setting);
        } else if ($setting->type == 'google') {
            return new Google\GoogleClient($setting);
        }

        throw new Exception\LangridSettingException('Service not found.');
    }
}
