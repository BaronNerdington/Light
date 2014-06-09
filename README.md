Light
=====

A light PHP library

Why Light?
=====

Because i wanted a simple, easy, non-overbloated routing system, autoloader and templating.

How to use
=====

## Make your own controller

``` php

    namespace MyNamespace;

    use Light\Controller;
    use Light\Request;
    use Light\Template;

    class MyController extends Controller
    {

        public function run(Request $request, Template $templateHandler)
        {
            $templateHandler->addCss('index.css');
            // todo goes here
            return $templateHandler->render('index');
        }

        public function isIndexController()
        {
            return true;
        }


    }

```

## Run the application

``` php

    $app = new Light\Application('/');

    $controller = new \MyNamespace\MyController(array('/'), $app->getPath());

    $app->addController($controller);

    // init template engine
    $template = new Light\Template();
    $template->setTemplatePath('../src/tpl/');

    $template->setHeaderTemplate('header');
    $template->setFooterTemplate('footer');

    $app->setTemplateHandler($template);

    $protocol = 'http';

    if (isset($_SERVER['HTTPS'])) {
        $protocol .= 's';
    }

    $urlString = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $request = new Light\Request(new Light\Url($urlString),  $_SERVER['REQUEST_METHOD']);

    $app->run($request);

```

Done!

License
====

See License.md for details.