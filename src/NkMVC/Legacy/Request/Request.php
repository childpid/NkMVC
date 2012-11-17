<?php

namespace NkMVC\Legacy\Request;

use NkMVC\Legacy\Bridge\Bridge;

class Request
{
    /**
     * @var \NkMVC\Legacy\Bridge\Bridge|null
     */
    protected $bridge = null;

    /**
     * @var array
     */
    protected $get = array();

    /**
     * @var array
     */
    protected $post = array();

    /**
     * @var array
     */
    protected $server = array();

    /**
     * @var
     */
    protected $headers;

    /**
     * @var array
     */
    protected $cookie = array();

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var array
     */
    protected $request = array();

    /**
     * @param \NkMVC\Legacy\Bridge\Bridge $bridge
     */
    public function __construct(Bridge $bridge)
    {
        $this->bridge = $bridge;
        $this->definedVars = $this->bridge->getDefinedVars();

        $this->initialize();
    }

    /**
     * Init
     */
    protected function initialize()
    {
        $this->get = $this->definedVars['_GET'];
        $this->post = $this->definedVars['_POST'];
        $this->server = $this->definedVars['_SERVER'];
        $this->cookie = $this->definedVars['_COOKIE'];
        $this->files = $this->definedVars['_FILES'];
        $this->request = $this->definedVars['_REQUEST'];
    }

    /**
     * Returns module's name
     *
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->request['file'];
    }

    /**
     * Returns page's name
     *
     * @return mixed
     */
    public function getPageName()
    {
        return $this->request['page'];
    }

    /**
     * Returns the controller action's name
     *
     * @return mixed
     */
    public function getActionName()
    {
        return $this->request['op'];
    }
}