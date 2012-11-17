<?php

namespace NkMVC\Legacy\Controller;

use NkMVC\Legacy\Bridge\Bridge;


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

        // wtf!, wtf!, wtf! ... change this childpid!
        $modulePath = __DIR__ . '/../../Modules/' . $this->moduleName . '/index.php';

        if (file_exists(realpath($modulePath))) {

            // you did it again, fu childpid!
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
        return $class->$actionToRun($this->request);
    }
}
