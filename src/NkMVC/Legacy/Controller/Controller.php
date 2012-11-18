<?php

namespace NkMVC\Legacy\Controller;

use NkMVC\Legacy\Bridge\Bridge;
use NkMVC\Legacy\View\View;


class Controller
{
    /**
     * @var \NkMVC\Legacy\Bridge\Bridge|null
     */
    protected $bridge = null;

    /**
     * @var array NkMVC\Legacy\Request\Request
     */
    protected $request = array();

    /**
     * Module name
     *
     * @var
     */
    protected $moduleName;

    /**
     * Action name
     *
     * @var
     */
    protected $actionName;

    /**
     * Class
     *
     * @var
     */
    protected $class;

    /**
     * Defined vars
     *
     * @var array
     */
    protected $definedVars = array();

    /**
     * @var array
     */
    protected $vars = array();

    /**
     * @var
     */
    protected $modulePath;

    /**
     * @param \NkMVC\Legacy\Bridge\Bridge $bridge
     */
    public function __construct(Bridge $bridge)
    {
        $this->bridge = $bridge;
        $this->request = $this->bridge->getRequest();
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        $this->moduleName = $this->request->getModuleName();
        $this->actionName = $this->request->getActionName();

        // @TODO: wtf!, wtf!, wtf! ... change this childpid!
        $this->modulePath = __DIR__ . '/../../Modules/' . $this->moduleName;
        $frontController = $this->modulePath . '/index.php';

        if (file_exists(realpath($frontController))) {

            // @TODO: Doh! you did it again, FU childpid! FU!
            $moduleClass = "NkMVC\\Modules\\" . $this->moduleName . "\\" . $this->moduleName;

            if (class_exists($moduleClass, false)) {
                $this->class = $moduleClass;
            }
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function execute()
    {
        $this->getController();

        $actionToRun = 'execute' . ucfirst($this->actionName);

        if ($actionToRun === 'execute') {
            // no action given
            throw new \Exception(sprintf('Module "%s". There was no action given.', $this->class));
        }

        $class = new $this->class;

        if (!is_callable(array($class, $actionToRun))) {
            // action not found
            throw new \Exception(sprintf(
                'Module "%s", action "%s". You must create a "%s" method.',
                $this->class,
                $this->actionName,
                $actionToRun
            ));
        }

        // run action
        $class->$actionToRun($this->request);
        $this->doRender();
    }


    /**
     * Sets a new variable
     *
     * @param string $name the name of the variable
     * @param mixed $value the value
     */
    public function set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * Sets a new variable (magic method)
     *
     * @param string $name the name of the variable
     * @param mixed $value the value
     */
    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * Returns a variable
     *
     * @param $name
     * @return null
     */
    public function get($name)
    {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    /**
     * Returns a variable (magic method)
     *
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    /**
     * Rendering
     */
    private function doRender()
    {
        $content = View::render($this->modulePath, $this->actionName);

        //$this->vars = array_merge($this->vars, array('raw_content' => $content));

        extract($this->vars);

        // @TODO Ok childpid, you want to fucking changet this
        $tpl = $this->modulePath . '/' . 'templates/' . $this->actionName . '.tpl.php';

        ob_start();

        // @TODO add some checks in this block and return throws Exception ?!
        if (file_exists($tpl) || is_readable($tpl)) {
            include $tpl;
        }

        $parsed = ob_get_contents();

        ob_end_clean();

        eval("?>" . $parsed . "<?");
    }

    /**
     *
     */
    private function _doError404()
    {

    }

}
