<?php

namespace NkMVC\Legacy\Bridge;

class Bridge implements \ArrayAccess
{
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

    /*
     * Constructor
     */
    public function __construct()
    {
        $this->includedFiles = get_included_files();
        $this->definedVars = $GLOBALS;
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
}
