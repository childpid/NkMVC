<?php

namespace NkMVC\Modules\Defy;

use NkMVC\Legacy\Request\Request;
use NkMVC\Legacy\Storage\DB;

class Defy implements \NkMVC\Modules\ModuleInterface
{
    public function executeIndex(Request $request)
    {
        $db = DB::getInstance();
        echo 'NkMV';
        var_dump($db);

    }

    public function executeDownload(Request $request)
    {
        echo 'DOWNLOAD daaaa!';
    }

    public function getModuleName()
    {
        return __CLASS__;
    }
}
