<?php

class Eventbrite
{

    const VERSION = '0.9.0';

    protected $baseUrl = 'https://www.eventbrite.com/json/';

    protected $appKey;
    protected $userKey;

    protected $user;
    protected $password;

    // Set default caching properties.
    protected $cacheDir = '/tmp';
    protected $cacheTimeout = 86400;

    public function __construct($args) {
        if (isset($args['app_key'])) {
            $this->appKey = $args['app_key'];
        }

        if (isset($args['user_key'])) {
            $this->userKey = $args['user_key'];
        }

        if (isset($args['user'])) {
            $this->user = $args['user'];
        }

        if (isset($args['password'])) {
            $this->password = $args['password'];
        }
    }

    public function cache($args) {
        if (isset($args['dir'])) {
            $this->cacheDir = $args['dir'];
        }

        if (isset($args['timeout'])) {
            if ($args['timeout']) {
                $this->cacheTimeout = $args['timeout'];
            } else {
                $this->cacheDir = '';
                $this->cacheTimeout = 0;
            }
        }
    }

    protected function request($url, $file) {
        $json = '';

        if (!empty($file)) {
            if (file_exists($file) && is_readable($file) && (time() - filemtime($file) < $this->cacheTimeout)) {
                $json = file_get_contents($file);
            } else {
                $json = file_get_contents($url);
                file_put_contents($file, $json);
            }
        } else {
            $json = file_get_contents($url);
        }

        return $json;
    }

    public function __call($method, $args) {
        $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $method));

        // Prefer user key over email and password.
        if (isset($this->userKey)) {
            $url = "{$this->baseUrl}{$method}?user_key={$this->userKey}&app_key={$this->appKey}";
        } elseif (isset($this->user) && isset($this->password)) {
            $url = "{$this->baseUrl}{$method}?user={$this->user}&password={$this->password}&app_key={$this->appKey}";
        } else {
            $url = "{$this->baseUrl}{$method}?app_key={$this->appKey}";
        }

        foreach ($args[0] as $key => $value) {
            $url .= "&{$key}={$value}";
        }

        $hash = md5($url);

        // Only cache read-only stuff.
        list ($entity, $action) = explode('_', $method);
        $file = '';
        switch ($action) {
            case 'get':
            case 'list':
            case 'search':
                // Check whether caching is disabled.
                if ($this->cacheTimeout) {
                    $file = "{$this->cacheDir}/eventbrite-{$hash}";
                }
                break;
        }

        return json_decode($this->request($url, $file), TRUE);
    }

}

?>