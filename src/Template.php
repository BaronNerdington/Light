<?php
/**
 * Template.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * (c) Christopher Simon
 *
 * @author Christopher Simon <mail@christopher-simon.de>
 */

namespace Light;

use Light\Template\Exception;


/**
 * Class Template
 *
 * Basic simple template rendering class
 *
 * @package Light
 */
class Template
{
    /**
     * @var string
     */
    protected $templateFileExtension = '.php';

    /**
     * @var array
     */
    protected $vars = array();

    /**
     * @var
     */
    protected $templatePath;

    /**
     * @var bool
     */
    protected $autoEscape = true;

    /**
     * @var bool
     */
    protected $onlyBody = false;

    /**
     * @var array
     */
    protected $css = array();

    /**
     * @var array
     */
    protected $js = array();

    /**
     * @var string
     */
    protected $headerTemplate;

    /**
     * @var string
     */
    protected $footerTemplate;

    /**
     * @param $name
     * @param $value
     */
    public function setTemplateVar($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getTemplateVar($name)
    {
        if ($this->autoEscape === true) {
            return $this->escape($this->vars[$name]);
        }

        return $this->vars[$name];
    }

    /**
     * Sanitizes a value for output in HTML
     *
     * @param $value
     * @return mixed
     */
    public function escape($value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    /**
     * Toggles auto escaping for values
     *
     * @param bool $autoEscape
     */
    public function setAutoEscape($autoEscape = true)
    {
        $this->autoEscape = $autoEscape === true;
    }

    /**
     * @return bool
     */
    public function getAutoEscape()
    {
        return $this->autoEscape;
    }

    /**
     * @param mixed $templatePath
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return rtrim($this->templatePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param mixed $footerTemplate
     */
    public function setFooterTemplate($footerTemplate)
    {
        $this->footerTemplate = $footerTemplate;
    }

    /**
     * @return mixed
     */
    public function getFooterTemplate()
    {
        return $this->footerTemplate;
    }

    /**
     * @param mixed $headerTemplate
     */
    public function setHeaderTemplate($headerTemplate)
    {
        $this->headerTemplate = $headerTemplate;
    }

    /**
     * @return mixed
     */
    public function getHeaderTemplate()
    {
        return $this->headerTemplate;
    }

    /**
     * @param string $templateFileExtension
     */
    public function setTemplateFileExtension($templateFileExtension)
    {
        $this->templateFileExtension = $templateFileExtension;
    }

    /**
     * @return string
     */
    public function getTemplateFileExtension()
    {
        return $this->templateFileExtension;
    }

    /**
     * Shorthand get
     *
     * @param $name
     * @return mixed
     */
    public function g($name)
    {

        return $this->getTemplateVar($name);
    }

    /**
     * Shorthand escape
     *
     * @param $value
     * @return mixed
     */
    public function e($value)
    {
        return $this->escape($value);
    }

    /**
     * Shorthand include
     *
     * @param $tpl
     */
    public function i($tpl)
    {
        return $this->render($tpl, true);
    }

    /**
     * @param $file
     */
    public function addCss($file)
    {
        $this->css[] = $file;
    }

    /**
     * @param $file
     */
    public function addJs($file)
    {
        $this->js[] = $file;
    }

    /**
     * Renders a template
     *
     * @param string $templateName
     * @param bool $onlyBody Render only template, not header and footer templates
     *
     * @throws Exception
     */
    public function render($templateName = null, $onlyBody = false)
    {
        $tpl = $this;

        $css = $this->css;
        $js = $this->js;

        if ($onlyBody === false) {
            include $this->getTemplatePath() . $this->getHeaderTemplate() . $this->getTemplateFileExtension();
        }

        $templatePath = stream_resolve_include_path(
            $this->getTemplatePath() . $templateName . $this->getTemplateFileExtension()
        );

        if ($templatePath === false) {
            throw new Exception('Template not found');
        }

        include $templatePath;

        if ($onlyBody === false) {
            include $this->getTemplatePath() . $this->getFooterTemplate() . $this->getTemplateFileExtension();
        }
    }
} 