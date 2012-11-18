<?php

namespace NkMVC\Legacy\View;

class View
{
    /**
     * @var array
     */
    protected static $vars = array();

    /**
     * Renders the template
     *
     * @param $path
     * @param $action
     *
     * @return string
     */
    public static function render($path, $action)
    {
        // get  template
        $tpl = $path . '/templates/' . $action. '.tpl.php';

        return self::return_content($tpl);
    }

    /**
     * @param $tpl
     * @return string
     */
    static private function return_content($tpl)
    {
        $output = file_get_contents($tpl);

        return $output;
    }
}
