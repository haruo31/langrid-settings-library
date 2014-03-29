<?php
namespace LangridSettingClient\API\Langrid;

require dirname(__FILE__) . '/../../lib/dol-monitor-service/lib/langrid-php-library/MultiLanguageStudio.php';

class LangridTranslator implements \LangridSettingClient\API\Translator {
    var $setting;
    var $uri;
    var $user;
    var $pass;
    var $proxyHost;
    var $proxyPort;

    protected function findServiceId($tree) {
        $q = [$tree];
        for ($i = 0; $i < count($q); $i++) {
            $a = $q[$i];
            foreach ($a as $k => $v) {
                if (is_array($v) || is_object($v)) {
                    $q[] = $v;
                    continue;
                }
                if ($k === 'service_id')  {
                    return $v;
                }
            }
        }
        return null;
    }

    protected function getUri($from, $to) {
        $setting = $this->setting;
        $serviceId = null;
        for ($i = 0; $i < count($setting->paths); $i++ ) {
            if ($setting->paths[$i]->source_lang == $from &&
                $setting->paths[$i]->target_lang == $to) {
                return str_replace('{service_id}', $this->findServiceId($setting->paths[$i]), $this->uri);
            }
        }
        return null;
    }

    public function __construct($setting) {
        $this->setting = $setting;
        $this->uri = isset($setting->uri) ? $setting->uri : null;
        $this->user = isset($setting->user) ? $setting->user : null;
        $this->pass = isset($setting->password) ? $setting->password : null;
        $this->proxyHost = isset($setting->proxyHost) ? $setting->proxyHost : null;
        $this->proxyPort = isset($setting->proxyPort) ? $setting->proxyPort : null;
    }

    protected function writeAccessInfo($client) {
        $client->setUserId($this->user);
        $client->setPassword($this->pass);
        if ($this->proxyHost) {
            $client->setProxy($this->proxyHost, $this->proxyPort);
        }
    }

    public function translate($from, $to, $text) {
        $uri = $this->getUri($from, $to);

        $client = \ClientFactory::createTranslationClient($uri);
        $this->writeAccessInfo($client);
        return $client->translate(\Language::get($from), \Language::get($to), $text);
    }
}
