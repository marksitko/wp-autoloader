<?php

/**
 *
 * Class loader
 *
 *
 * @author    Abdelkarim Chatei
 * @copyright Copyright (c) 2015
 * @license
 * @version
 */

namespace db\wp\loader;


/**
 * Class loader
 */
class ClassLoader
{
    /**
     * @var array
     */
    private $namespaces = null;

    /**
     * @var ClassLoader
     */
    private static $instance = null;

    /**
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces)
    {
        $this->addNamespaces($namespaces);
    }

    /**
     * @param string $class
     */
    public function __invoke($class)
    {
        if ($file = $this->findFile($class)) {
            include $file;
            return true;
        }
    }

    /**
     * Initialize autoload
     */
    public static function init()
    {
        if (self::$instance) {
            return;
        }

        self::$instance = new self(self::getDefaultNamespaces());

        spl_autoload_register(self::$instance);
    }

    /**
     * @return array
     */
    public static function getDefaultNamespaces()
    {
        return  array(
            'db\\wp\\lib\\' => array('lib'),
        );
    }

    /**
     * @return self
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self(self::getDefaultNamespaces());
        }

        return self::$instance;
    }

    /**
     * @return self
     */
    public function addNamespaces(array $namespaces)
    {
        foreach ($namespaces as $ns => $paths) {
            $this->add($ns, $paths);
        }

        return $this;
    }

    /**
     * @param string $prefix
     * @param string $paths
     */
    public function add($prefix, $paths)
    {
        $first = substr($prefix, 0, 1);
        if ($first != strtolower($first)) {
            return;
        }

        if (!is_array($paths)) {
            $paths = array($paths);
        }

        foreach ($paths as $path) {
            if (substr($prefix, -1) != '\\') {
                $prefix .= '\\';
            }

            $this->namespaces[$first][$prefix][] = $path;
        }
    }

    /**
     * @param string $class
     * @return boolean|string
     */
    protected function findFile($class)
    {
        $first = substr($class, 0, 1);

        if (!isset($this->namespaces[$first])) {
            return false;
        }

        foreach ($this->namespaces[$first] as $prefix => $paths) {
            if (strpos($class, $prefix) === 0) {
                $file = substr($class, strlen($prefix));
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $file) . '.php';

                foreach ($paths as $path) {
                    if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
                        return $path . DIRECTORY_SEPARATOR . $file;
                    }
                }
            }
        }

        return false;
    }
}

ClassLoader::init();