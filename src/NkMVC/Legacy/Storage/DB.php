<?php

namespace NkMVC\Legacy\Storage;

use NkMVC\Legacy\Bridge\Bridge;

class DB
{
    /**
     * The current database resource id
     *
     * @var null
     */
    protected static $db = null;

    /**
     * Constructor
     *
     * @param \NkMVC\Legacy\Bridge\Bridge $bridge
     */
    public function __construct(Bridge $bridge)
    {
        $definedVars = $bridge->getDefinedVars();

        self::$db = is_resource($definedVars['db']) ? $definedVars['db'] : null;
    }

    /**
     * @return null
     */
    public static function getInstance()
    {
        return self::$db;
    }
}
