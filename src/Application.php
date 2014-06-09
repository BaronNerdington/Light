<?php
/**
 * Application.php
 *
 * @author Christopher Simon <mail@christopher-simon.de>
 */

namespace Light;


/**
 * Class Application
 * @package Light
 */
class Application
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var Template
     */
    protected $templateHandler;

    /**
     * @var Controller[]
     */
    protected $controllers = array();

    /**
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param array $config
     */
    public function loadConfig(array $config)
    {
        foreach ($config as $configParam => $configValue) {
            switch ($configParam) {
                case 'domain':
                    $this->setDomain($configValue);
                    break;
                case 'controllers':
                    foreach ($configValue as $controllerClass => $controllerPaths) {
                        $route = new $controllerClass($controllerPaths, $this->getPath());
                        $this->addController($route);
                    }
                    break;
            }
        }
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return Controller[]
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * @param Controller $controller
     */
    public function addController(Controller $controller)
    {
        $this->controllers[] = $controller;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setTemplateHandler(Template $templateHandler)
    {
        $this->templateHandler = $templateHandler;
    }

    /**
     * @return \Light\Template
     */
    public function getTemplateHandler()
    {
        return $this->templateHandler;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function run(Request $request)
    {
        $url = $request->getUrl();
        foreach ($this->getControllers() as $controller) {
            if ($controller->isRunnable($url->getPath())) {
                return $controller->run($request, $this->getTemplateHandler());
            }
        }

        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        exit;
    }

} 