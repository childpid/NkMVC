<?php
require_once __DIR__ . '/src/NkMVC/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
$loader = new UniversalClassLoader();
$loader->registerNamespaces(array('NkMVC'=> __DIR__ . '/src'));
$loader->useIncludePath(true);
$loader->register();


