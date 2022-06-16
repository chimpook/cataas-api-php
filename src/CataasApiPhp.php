<?php

namespace CataasApiPhp;

/**
 * Cat as a service API wrapper for PHP
 */
class CataasApiPhp
{
    protected const DEFAULT_CATAAS_URL = "https://cataas.com";
    protected string $cataas_url;
    protected string $cataas_path = "";
    protected string $mode = 'cat';
    protected array $commands = [];
    protected array $parameters = [];

    /**
     * It is possible to use an alternative service URL
     */
    public function __construct(string $cataas_url = self::DEFAULT_CATAAS_URL)
    {
        $this->cataas_url = $cataas_url;
    }

    public static function factory(string $cataas_url = self::DEFAULT_CATAAS_URL)
    {
        return new static($cataas_url);
    }

    public function tag(string $tag): CataasApiPhp
    {
        $this->commands['tag'] = $tag;
        return $this;
    }

    public function gif(): CataasApiPhp
    {
        $this->commands['gif'] = true;
        return $this;
    }

    public function api(): CataasApiPhp
    {
        $this->mode = 'api';
        return $this;
    }

    public function says(string $text): CataasApiPhp
    {
        $this->commands['says'] = $text;
        return $this;
    }

    public function cats(): CataasApiPhp
    {
        $this->commands['cats'] = true;
        return $this;
    }

    /**
     * What 'tags' is - depends on the way we use it.
     * With 'cats' - it is a tags filter, like this: /api/cats?tags=cute,eyes
     * Without 'cats' - it is a command to get the whole list of all possible tags, like this: /api/tags
     */
    public function tags(string $tags = null): CataasApiPhp
    {
        if (!empty($this->commands['cats'])) {
            $this->parameters['tags'] = $tags;    
        } else {
            $this->commands['tags'] = true;
        }
        
        return $this;
    }

    public function size(int $size): CataasApiPhp
    {
        $this->parameters['size'] = $size;
        return $this;
    }

    public function color(string $color): CataasApiPhp
    {
        $this->parameters['color'] = $color;
        return $this;
    }

    /**
     * The cataas.com provide those types: 
     * original (or) | square (sq) | medium (md) | small (sm)
     */
    public function type(string $type): CataasApiPhp
    {
        $this->parameters['type'] = $type;
        return $this;
    }

    /**
     * The cataas.com built-in filters are:
     * blur | mono | sepia | negative | paint | pixel
     * May be usage of the locally installed imagemagick would be a better idea.
     * There is no need to load cataas.com with heavy tasks, 
     * because it is not so powerful and hangs from time to time
     */
    public function filter(string $filter): CataasApiPhp
    {
        $this->parameters['filter'] = $filter;
        return $this;
    }

    public function width(int $width): CataasApiPhp
    {
        $this->parameters['width'] = $width;
        return $this;
    }

    public function height(int $height): CataasApiPhp
    {
        $this->parameters['height'] = $height;
        return $this;
    }

    public function json(): CataasApiPhp
    {
        $this->parameters['json'] = 'true';
        return $this;
    }

    public function html(): CataasApiPhp
    {
        $this->parameters['html'] = 'true';
        return $this;
    }

    public function skip(int $number = 0): CataasApiPhp
    {
        $this->parameters['skip'] = $number;
        return $this;
    }

    public function limit(int $number): CataasApiPhp
    {
        $this->parameters['limit'] = $number;
        return $this;
    }

    protected function build_cataas_path()
    {
        $this->cataas_path = DIRECTORY_SEPARATOR . $this->mode;
        $this->cataas_path .= !empty($this->commands['tags']) ? '/tags' : '';
        $this->cataas_path .= !empty($this->commands['cats']) ? '/cats' : '';
        $this->cataas_path .= !empty($this->commands['tag']) ? '/' . $this->commands['tag'] : '';
        $this->cataas_path .= !empty($this->commands['gif']) ? '/gif' : '';
        $this->cataas_path .= !empty($this->commands['says']) ? '/says/' . $this->commands['says'] : '';
        if (empty($this->parameters)) {
            return;
        }
        $this->cataas_path .= '?';
        $parameters = [];
        foreach ($this->parameters as $key => $value) {
            $parameters[] = $key . '=' . $value;
        }
        $this->cataas_path .= implode('&', $parameters);
    }

    public function getUrl()
    {
        $this->build_cataas_path();
        return $this->cataas_url . $this->cataas_path;
    }

    /**
     * Retrieves a cat from cataas service and saves it as a file or just lets it out 
     * 
     * @param string $file_path The path of the file for saving a cat to
     * @throws \Exception If something wrong with a file or with the cataas service
     * @return mixed
     */
    public function get(string $file_path = null)
    {
        $url = $this->getUrl();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        if (empty($file_path)) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
                echo $data;
                return strlen($data);
            });
        } else {
            $fp = fopen($file_path, 'wb');
            if (empty($fp)) {
                throw new \Exception('There is no place for a cat.. Cannot create the file : ' . $error_msg);
            }
            curl_setopt($ch, CURLOPT_FILE, $fp);
        }

        curl_exec($ch);
        $error_msg = curl_errno($ch) ?? null;
        curl_close($ch);

        if (!empty($fp)) {
            fclose($fp);
        }

        if (!empty($error_msg)) {
            throw new \Exception('There is no cat in this dark room! Cannot load the file from the cataas service : ' . $error_msg);
        }
    }

}
