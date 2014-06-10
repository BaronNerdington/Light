<?php
/**
 * Autoloader.php
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
 * Class Autoloader
 * @package Light
 */
class Autoloader
{

    /**
     *
     */
    const NAMESPACE_SEPARATOR = '\\';

    /**
     * @var array
     */
    protected $namespaces = array();


    /**
     * @param array $namespaces
     */
    public function __construct(array $namespaces = array())
    {
        $this->namespaces = $namespaces;
    }

    /**
     * @param $namespace
     */
    public function addNamespace($namespace)
    {
        if (in_array($namespace, $this->namespaces) === false) {
            $this->namespaces[] = $namespace;
        }
    }

    /**
     * @param $className
     * @return bool
     */
    protected function isValidClass($className)
    {
        foreach ($this->namespaces as $namespace) {
            $searchSpace = $namespace . self::NAMESPACE_SEPARATOR;
            if (strpos($className, $searchSpace) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $className
     * @return bool
     */
    public function loadClass($className)
    {
        if ($this->isValidClass($className) === false) {
            return false;
        }

        $filePath = stream_resolve_include_path(
            implode(DIRECTORY_SEPARATOR, explode(self::NAMESPACE_SEPARATOR, $className)) . '.php'
        );

        if ($filePath !== false) {
            return require $filePath;
        }

        return false;

    }

    /**
     *
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
} 
