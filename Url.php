<?php
namespace Conradoojr\ThiefLinks;

class Url {
    public $scheme;
    public $host;
    public $port;
    public $user;
    public $pass;
    public $path;
    public $query;
    public $fragment;
    public $full;
    public $domain;

    public function parse($url)
    {
        return parse_url($url);
    }

    public function fill($url)
    {
        $this->full = $url;
        $urlParsed = $this->parse($url);
        foreach ($urlParsed as $key => $value) {
            $this->{$key} = $value;
        }

        $this->domain = $this->scheme . '://' . $this->host;
    }
}
