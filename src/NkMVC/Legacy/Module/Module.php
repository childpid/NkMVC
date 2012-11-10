<?php

namespace NkMVC\Legacy\Module;

use NkMVC\Legacy\Bridge\Bridge;
use NkMVC\Legacy\Storage\DB;

class Module
{
    /**
     * @var \NkMVC\Legacy\Bridge\Bridge
     */
    protected $bridge;

    /**
     * @var \NkMVC\Legacy\Storage\DB
     */
    protected $db;

    /**
     * The defined variables
     *
     * @var array
     */
    protected $definedVars;

    /**
     * Modules
     *
     * @var array
     */
    protected $modules = array();

    /**
     * Constructor
     *
     * @param \NkMVC\Legacy\Bridge\Bridge $bridge
     * @param \NkMVC\Legacy\Storage\DB $db
     */
    public function __construct(Bridge $bridge, DB $db)
    {
        $this->bridge = $bridge;
        $this->definedVars = $this->bridge->getDefinedVars();
    }

    /**
     * Returns all installed modules
     *
     * @return array An array of installed modules
     */
    public function getInstalledModules()
    {
        $this->modules = $this->definedVars['module_aff_unique'] + $this->definedVars['complet'];

        return array_filter($this->modules);
    }

    /**
     * Whether a module exists
     *
     * @param $module The module to check
     * @return bool
     */
    public function exists($module)
    {
        if (!empty($module)) {
            $module = ucfirst($module);

            return array_key_exists($module, $this->getModules());
        }

        return false;
    }

    /**
     * Returns an array of active modules
     */
    public function getActiveModules()
    {

    }

    /**
     * Whether a module is active
     *
     * @param $module
     */
    public function isActive($module)
    {

    }

    /**
     * Returns the current module used
     */
    public function getCurrentModule()
    {
        return $this->definedVars['ModName'];
    }
}
