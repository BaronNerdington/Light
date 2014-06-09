<?php
/**
 * Url.php
 *
 * @author Christopher Simon <mail@christopher-simon.de>
 */

namespace Light;


/**
 * Class Url
 *
 * Url parsing and representation class
 *
 * @package Light
 */
class Url
{

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $pass;

    /**
     * @var array
     */
    protected $path = array();

    /**
     * @var string
     */
    protected $query = array();

    /**
     * @var string
     */
    protected $fragment;

    /**
     * @param string $urlString
     */
    public function __construct($urlString = null)
    {
        if ($urlString !== null) {
            $this->hydrateFromUrlString($urlString);
        }
    }

    /**
     * Hydrates this object from a string
     *
     * @param $urlString
     * @throws \InvalidArgumentException
     */
    protected function hydrateFromUrlString($urlString)
    {
        $url = parse_url($urlString);
        if ($url === false) {
            throw new \InvalidArgumentException('malformed url');
        }

        foreach ($url as $urlKey => $urlValue) {
            switch ($urlKey) {
                case 'query':
                    $queryPieces = explode('&', $urlValue);
                    foreach ($queryPieces as $queryPiece) {
                        $queryPair = explode('=', $queryPiece);
                        if (count($queryPair) == 2) {
                            $this->query[$queryPair[0]] = $queryPair[1];
                        }
                    }
                    break;
                case 'path':
                    $this->path = explode('/', $urlValue);
                    break;
                default:
                    $this->{$urlKey} = $urlValue;
            }
        }
    }

    /**
     * @param $fragment
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param $path
     */
    public function setPathArray($path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getPathArray()
    {
        return $this->path;
    }

    /**
     * @param $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param $query
     */
    public function setQueryArray($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQueryArray()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        $queryString = '';
        foreach ($this->getQueryArray() as $queryKey => $queryValue) {
            $queryString .= $queryKey . '=' . urlencode($queryValue) . '&';
        }

        return trim($queryString, '&');
    }

    /**
     * @param $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $paramName
     * @param bool $decode
     * @return null|string
     */
    public function getQueryParam($paramName, $decode = false)
    {
        if (isset($this->query[$paramName]) === false) {
            return null;
        }

        return $decode === true ? urldecode($this->query[$paramName]) : $this->query[$paramName];
    }

    /**
     * @param $key
     * @param $value
     */
    public function addQueryParam($key, $value)
    {
        $this->query[$key] = $value;
    }


    /**
     * @param $index
     * @return null
     */
    public function getPathElement($index)
    {
        if (isset($this->path[$index]) === false) {
            return null;
        }

        return $this->path[$index];
    }

    /**
     * @param int $from
     * @param int $to
     *
     * @return string
     */
    public function getPath($from = null, $to = null)
    {
        $pathArray = $this->getPathArray();

        if ($from !== null || $to !== null) {
            $newPathArray = array();
            for ($i = (int)$from; $i < (int)$to; $i++) {
                $newPathArray[] = $pathArray[$i];
            }

            $pathArray = $newPathArray;
        }

        return implode('/', $pathArray);
    }

    /**
     * Adds element to path
     *
     * @param $element
     * @param int $position
     */
    public function addPathElement($element, $position = null)
    {
        if ($position !== null) {
            $newPath = array();

            foreach ($this->getPathArray() as $pathPosition => $pathElement) {
                if ($pathPosition === $position) {
                    $newPath[] = $element;
                }

                $newPath[] = $pathElement;
            }

            $this->path = $newPath;
        } else {
            $this->path[] = $element;
        }
    }

    /**
     * Returns url as string
     *
     * @return string
     */
    public function getString()
    {
        $urlString = '';

        if ($this->getScheme() !== null) {
            $urlString .= $this->getScheme() . ':';
        }

        $schemeSet = false;

        if ($this->getUser() !== null) {
            $urlString .= '//' . $this->getUser();
            $schemeSet = true;
        }

        if ($this->getPass() !== null) {
            $urlString .= ':' . $this->getPass();
        }


        if ($this->getHost() !== null) {
            if ($this->getUser() !== null) {
                $urlString .= '@';
            }

            if ($schemeSet === false) {
                $urlString .= '//';
                $schemeSet = true;
            }

            $urlString .= $this->getHost();
        }

        if ($this->getPort() !== null) {
            $urlString .= ':' . $this->getPort();
        }

        if (count($this->getPath()) > 0) {
            $urlString .= '/';
            $urlString .= $this->getPath();
        }

        if (count($this->getQuery()) > 0) {
            $urlString .= '?';
            $urlString .= $this->getQueryAsString();
        }

        if ($this->getFragment() === null) {
            $urlString .= '#' . $this->getFragment();
        }

        return $urlString;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getString();
    }
} 