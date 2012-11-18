<?php
namespace Install;

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
require_once __DIR__ . '/../autoload.php';

use NkMVC\Legacy\Bridge\Bridge;

final class NkMVCInstall
{
    const EN_INSTALLATION_MSG = "During the installation of NkMVC, your current <strong>index.php</strong> will be backuped and then modified";
    const FR_INSTALLATION_MSG = "Durant l'installation de NkMVC, une sauvegarde de votre fichier <strong>index.php</strong> se effectué, il sera ensuite modifié";
    const EN_NO_CONFIG_FILE_FOUND = "Unable to find file %s";
    const FR_NO_CONFIG_FILE_FOUND = "Impossible de trouver le fichier %s";
    const EN_ENABLE_TO_WRITE_CONFIG_FILE = "Unable to write to file %s";
    const FR_ENABLE_TO_WRITE_CONFIG_FILE = "Impossible d'écrire dans le fichier %s";
    const EN_ENABLE_TO_CREATE_CONFIG_FILE = "Unable to create file %s";
    const FR_ENABLE_TO_CREATE_CONFIG_FILE = "Impossible de créer le fichier %s";
    const EN_ENABLE_TO_SET_CONTENT = "Unable to set content for file %s";
    const FR_ENABLE_TO_SET_CONTENT = "Impossible d'insérer du contenu dans le fichier %s";
    const EN_NKMVC_ALREADY_INSTALLED = 'NkMVC is already installed';
    const FR_NKMVC_ALREADY_INSTALLED = 'NkMVC est actuellement installé';
    const EN_NKMVC_INSTALLATION_COMPLETED = 'has been successfully installed';
    const FR_NKMVC_INSTALLATION_COMPLETED = 'a été installé avec succès';

    /**
     * @var \NkMVC\Legacy\Bridge\Bridge
     */
    private $bridge;

    /**
     * @var string
     */
    private $indexFilename = 'index.php';

    /**
     * @var string
     */
    private $indexSavedFilename = 'index.php_saved_by_NKMVC';

    /**
     * @var string
     */
    private $indexFilePath;

    /**
     * @var string
     */
    private $indexSavedFilePath;

    /**
     * @var null|string
     */
    private $rootDir = null;


    /**
     * @var string
     */
    private $defaultLanguage = 'fr';

    /**
     * @var
     */
    private $acceptedLanguage;

    /**
     * @var
     */
    private $lang;

    /**
     * @var null
     */
    private $content = null;

    /**
     * Constructor
     */
    final public function __construct()
    {
        $this->bridge = new Bridge();
        $this->rootDir = realpath(__DIR__ . '/../../') ;
        $this->indexFilePath = $this->getRootDir() . '/' . $this->getIndexFileName();
        $this->indexSavedFilePath = $this->getRootDir() . '/' . $this->getIndexSavedFileName();
    }

    /**
     * Backups the current config.inc.php and create a new one if needed.
     *
     * @throws \Exception
     */
    public function run()
    {
        print $this->isInstalled();

        if (false == $this->isInstalled()) {
                $this->backup();
                $this->createNew();

            return self::FR_NKMVC_INSTALLATION_COMPLETED;
        } else {
            return self::FR_NKMVC_ALREADY_INSTALLED;
        }
    }

    /**
     * Returns the index filename
     *
     * @return string
     */
    private function getIndexFileName()
    {
        return $this->indexFilename;
    }

    /**
     * Returns the index saved filename
     *
     * @return string
     */
    private function getIndexSavedFileName()
    {
        return $this->indexSavedFilename;
    }

    /**
     * Returns the index file path
     *
     * @return string
     */
    private function getIndexFilePath()
    {
        return $this->indexFilePath;
    }

    /**
     * Returns the index saved file path
     *
     * @return string
     */
    private function getIndexSavedFilePath()
    {
        return $this->indexSavedFilePath;
    }

    /**
     * Returns the Legacy install root directory
     *
     * @return null|string
     */
    private function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Backups the current config.inc.php file
     *
     * @throws \Exception
     */
    private function backup()
    {
        if (!file_exists($this->getIndexFilePath())) {
            throw new \Exception(sprintf(self::FR_NO_CONFIG_FILE_FOUND, $this->getIndexFilePath()));
        }

        $saved = $this->getIndexSavedFilePath();

        if (false === $fh = @fopen($saved, 'w')) {
            throw new \Exception(sprintf(self::FR_ENABLE_TO_CREATE_CONFIG_FILE, $saved));
        }

        fclose($fh);

        file_put_contents($saved, $this->getIndexFileContent());

        @unlink($this->getIndexFilePath());
    }

    /**
     * Creates a new config.inc.php file
     *
     * @throws \Exception
     */
    private function createNew()
    {
        if (false === $fh = @fopen($this->getIndexFilePath(), 'w')) {
            throw new \Exception(sprintf(self::FR_ENABLE_TO_CREATE_CONFIG_FILE, $this->getIndexFilePath()));
        }

        fclose($fh);

        if (false === is_writable($this->getIndexFilePath()) OR false === @chmod($this->getIndexFilePath(), 0644)) {
            throw new \Exception(sprintf(self::FR_ENABLE_TO_WRITE_CONFIG_FILE, $this->getIndexFilePath()));
        }

        file_put_contents($this->getIndexFilePath(), $this->buildContent());
    }

    /**
     * Returns the current content for config.inc.php file
     *
     * @return string
     */
    private function getIndexFileContent()
    {
        $this->content = file_get_contents($this->getIndexFilePath());

        return $this->content;
    }

    /**
     * Checks whether NKMVC is already installed
     *
     * @return string
     */
    private function isInstalled()
    {
        $content = file_get_contents($this->getIndexFilePath());

        if (!preg_match('/USE_NKMVC/', $content) /*OR !file_exists($this->getIndexSavedFilePath())*/) {
            return false;
        }

        return self::FR_NKMVC_ALREADY_INSTALLED;
    }

    /**
     * Creates a new content for config.inc.php file
     *
     * @return string
     */
    private function buildContent()
    {
$top = <<<TOP
<?php

DEFINE('USE_NKMVC', true);

TOP;

$bottom = <<<BOTTTOM

if (USE_NKMVC) {
    require_once __DIR__ . '/NkMVC/autoload.php';
}
?>
BOTTTOM;

        $content = str_replace("<?php", $top, $this->content);
        $content = preg_replace('/if \(is_file\(\'modules\/\'/', "if (is_file('NkMVC/src/NkMVC/Modules/'", $content);
        $content = preg_replace('/include\(\'modules\/\'/', "include('NkMVC/src/NkMVC/Modules/'", $content);
        $content = str_replace(substr($this->content, strrpos($this->content, '?>', -1)), '', $content);
        //$content .= $bottom;

        return $content;
    }
}

$install = new NkMVCInstall;
$install->run();
