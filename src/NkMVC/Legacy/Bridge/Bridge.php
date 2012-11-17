<?php

namespace NkMVC\Legacy\Bridge;

use NkMVC\Legacy\Module\Module;
use NkMVC\Legacy\User\User;
use NkMVC\Legacy\Storage\DB;
use NkMVC\Legacy\Request\Request;
use NkMVC\Legacy\Controller\Controller;


class Bridge implements \ArrayAccess
{
    /**
     * php version required
     */
    const PHP_VERSION_REQUIRED = '5.2.0';

    const NK_VERSION_REQUIRED = '1.7.9';

    /**
     *
     * @var NkMVC\Legacy\Module\Module
     */
    protected $module;

    /**
     * @var NkMVC\Legacy\Storage\DB
     */
    protected static $db;

    /**
     * @var NkMVC\Legacy\User\User
     */
    protected $user;

    /**
     * @var NkMVC\Legacy\Request\Request
     */
    protected $request;

    /**
     * @var NkMVC\Legacy\Controller\Controller
     */
    protected $controller;

    /**
     * The includes files
     *
     * @var array
     */
    protected $includedFiles = array();

    /**
     * The defined variables
     *
     * @var array
     */
    protected $definedVars = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        //if ($this->isAcceptedPHPVersion() && $this->isAcceptedNkVersion()) {
            $this->init();
        //}
    }

    /**
     * Initialization
     */
    public function init()
    {

        $this->includedFiles = get_included_files();
        $this->definedVars = $GLOBALS;
        $this->request = new Request($this);
        $this->user = new User($this);
        self::$db = new DB($this);
        $this->module = new Module($this, self::$db);
        $this->controller = new Controller($this);
    }

    public function run()
    {
        $this->controller->execute();
    }

    /**
     * Returns an array with the names of included or required files
     *
     * @return array The includes/required files
     */
    public function getIncludedFiles()
    {
        return $this->includedFiles;
    }

    /**
     * Returns an array of all defined variables
     *
     * @return array The returned vars
     */
    public function getDefinedVars()
    {
        return $this->definedVars;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->definedVars[] = $value;
        } else {
            $this->definedVars[$offset] = $value;
        }
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->definedVars[$offset]) ? $this->definedVars[$offset] : null;
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->definedVars[$offset]);
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->definedVars[$offset]);
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function isAcceptedPHPVersion()
    {
        var_dump(PHP_VERSION, self::PHP_VERSION_REQUIRED);


        if ($t = version_compare(PHP_VERSION, self::PHP_VERSION_REQUIRED, '>=')) {
            echo $t;

            return false;
            throw new \Exception('NkMVC PHP version: ' . self::PHP_VERSION_REQUIRED . ' is required, your version is ' . PHP_VERSION . "\n");
        }

        //return true;
    }

    /**
     * @return NkMVC\Legacy\Request\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return NkMVC\Legacy\User\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return NkMVC\Legacy\Storage\DB
     */
    public function getDB()
    {
        return self::$db;
    }

    /**
     * @return NkMVC\Legacy\Module\Module
     */
    public function getModule()
    {
        return $this->module;
    }
}
