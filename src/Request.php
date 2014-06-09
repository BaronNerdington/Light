<?php
/**
 * Request.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * (c) Christopher Simon
 *
 * @author Christopher Simon <mail@christopher-simon.de>
 */

namespace Light;


/**
 * Class Request
 *
 * Represents a server request. Wraps php superglobals
 *
 * @package Light
 */
class Request
{
    /**
     *
     */
    const METHOD_GET = 0;
    /**
     *
     */
    const METHOD_POST = 1;

    /**
     * @var int
     */
    protected $method;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @param Url $url The request URL
     * @param string $method Request method
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(Url $url, $method)
    {
        $this->url = $url;
        switch (strtolower($method)) {
            case 'post':
                $this->method = self::METHOD_POST;
                break;
            case 'get':
                $this->method = self::METHOD_GET;
                break;
            default:
                throw new \InvalidArgumentException('Not a valid method');

        }
    }

    /**
     * @param $name
     * @param $filter
     * @return mixed|null
     */
    public function getParameter($name, $filter)
    {
        $value = '';

        if ($this->method === self::METHOD_GET && isset($_GET[$name]) === true) {
            $value = $_GET[$name];
        } elseif ($this->method === self::METHOD_POST && isset($_POST[$name]) === true) {
            $value = $_POST[$name];
        } else {
            return null;
        }

        return filter_var($value, $filter);
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }


} 