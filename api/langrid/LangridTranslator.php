<?php
namespace LangridSettingClient\API\Langrid;

use \LangridSettingClient\API as API;

require_once dirname(__FILE__) . '/../../lib/dol-monitor-service/lib/langrid-php-library/MultiLanguageStudio.php';
require_once dirname(__FILE__) . '/../../lib/dol-monitor-service/lib/langrid-php-library/utils/BindingNodeUtil.php';

class LangridTranslator implements API\Translator {
    var $setting;
    var $uri;
    var $user;
    var $pass;
    var $proxyHost;
    var $proxyPort;
    var $dict;

    protected function getPath($from, $to) {
        foreach ($this->setting->paths as $p) {
            if ($p->sourceLang === $from &&
                $p->targetLang === $to) {
                return $p;
            }
        }
        return null;
    }

    protected function getUri($serviceId) {
        $setting = $this->setting;
        if ($serviceId) {
            return str_replace('{service_id}', $serviceId, $this->uri);
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

    protected function writeAccessInfo($client, $binds) {
        $client->setUserId($this->user);
        $client->setPassword($this->pass);
        if ($this->proxyHost) {
            $client->setProxy($this->proxyHost, $this->proxyPort);
        }
        foreach ($binds as $bind) {
            $client->addBindings($bind);
        }
    }

    protected function createBindingNode($path) {
        $binds = \BindingNodeUtil::createBindingNode($path->optionalBinds);
        if (! is_array($binds)) {
            throw new \Exception("optionalBinds is must be array.");
        }
        return $binds;
    }

    public function translate($from, $to, $text, $dict = array()) {
        $compositeService = $this->setting->compositeServices->translation;
        $uri = $this->getUri($compositeService->serviceId);
        $path = $this->getPath($from, $to);

        if ($path == null) {
            throw new \Exception("Path ${from} -> ${to} is not defined in configuration.");
        }

        $binds = $this->createBindingNode($path);
        $client = \ClientFactory::createTranslationWithTemporalDictionaryClient($uri);
        foreach ($compositeService->translationBinds as $name) {
            $binds[] = new \BindingNode($name, $path->serviceId);
        }
        $this->writeAccessInfo($client, $binds);

        return $client->translate(\Language::get($from), \Language::get($to), $text, $dict, \Language::get($to));
    }

    public function backTranslate($from, $to, $text, $dict = array()) {
        $compositeService = $this->setting->compositeServices->backTranslation;
        $uri = $this->getUri($compositeService->serviceId);


        $path = $this->getPath($from, $to);

        if ($path == null) {
            throw new \Exception("Path ${from} -> ${to} is not defined in configuration.");
        }

        $binds = $this->createBindingNode($path);
        $client = \ClientFactory::createBackTranslationWithTemporalDictionaryClient($uri);
        foreach ($compositeService->translationBinds as $name) {
            $binds[] = new \BindingNode($name, $path->serviceId);
        }
        $this->writeAccessInfo($client, $binds);

        return $client->backTranslate(\Language::get($from), \Language::get($to), $text, $dict, \Language::get($to));
    }
}
