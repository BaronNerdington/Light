<?php
/**
 * Controller.php
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
 * Class Controller
 * @package Light
 */
abstract class Controller
{
    /**
     * @var array
     */
    protected $paths = array();

    /**
     * @var string
     */
    protected $appBasePath;

    /**
     * @param array $paths
     * @param string $appBasePath
     */
    public function __construct(array $paths, $appBasePath = '')
    {
        $this->paths = $paths;
        $this->appBasePath = $appBasePath;
    }

    /**
     * Getter for base path
     *
     * @return string
     */
    public function getAppBasePath()
    {
        return $this->appBasePath;
    }

    /**
     * Adds a path
     *
     * @param $path
     */
    public function addPath($path)
    {
        if (in_array($path, $this->paths) === false) {
            $this->paths[] = $path;
        }
    }

    /**
     * Checks if this controller should run on a path
     *
     * @param $path
     * @return bool
     */
    public function isRunnable($path)
    {
        foreach ($this->paths as $runPath) {
            $runPath = str_replace('//', '/', $this->getAppBasePath() . $runPath);
            if ($this->isIndexController() === false) {
                if (strpos($path, $runPath) === 0) {
                    return true;
                }
            } else {
                if (rtrim($path) == rtrim($runPath)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    abstract public function isIndexController();

    /**
     * Run method for Page preparation and rendering
     *
     * @param Request $request
     * @param Template $templateHandler
     * @return mixed
     */
    abstract public function run(Request $request, Template $templateHandler);
} 