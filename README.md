NkMVC
=====

Un PoC [Preuve de Concept] permettant d'utiliser du MVC dans le CMS Nuked-Klan

Pour tester NkMVC:

Créez le répertoire "NkMVC et placez les fichiers et répertoires que vous avez téléchargé à l'interieur


Insérez en haut de page de votre fichier "index.php" 

```php
<?php 
...

define('USE_NKMVC', true)
...
```

Puis, remplacez le code suivant:

```php
if (is_file('modules/' . $_REQUEST['file'] . '/' . $_REQUEST['im_file'] . '.php')) {
    include('modules/' . $_REQUEST['file'] . '/' . $_REQUEST['im_file'] . '.php');
}
else include('modules/404/index.php');
```

par

```php
if (USE_NKMVC) {

    require_once __DIR__ . '/NkMVC/autoload.php';

    if (is_file('NkMVC/src/NkMVC/Modules/' . $_REQUEST['file'] . '/' . $_REQUEST['im_file'] . '.php')) {
        include('NkMVC/src/NkMVC/Modules/' . $_REQUEST['file'] . '/' . $_REQUEST['im_file'] . '.php');

        $bridge = new \NkMVC\Legacy\Bridge\Bridge();
        $bridge->run();
    }
}
else include('modules/404/index.php');
```

Un fichier "index.example.php" d'exemple est disponible, vous devez l'insérez à la racine de votre CMS nuked-klan