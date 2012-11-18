<?php

namespace NkMVC\Modules\Defy;

use NkMVC\Legacy\Request\Request;
use NkMVC\Legacy\Storage\DB;
use NkMVC\Legacy\View\View;

class Defy extends View implements \NkMVC\Modules\ModuleInterface
{
    public function executeIndex(Request $request)
    {
    }

    public function executeDownload(Request $request)
    {
    }

    public function getModuleName()
    {
        return __CLASS__;
    }
}
