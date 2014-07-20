<?php

namespace Zoop\Api\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Shard\Manifest;
use Zoop\Shard\Serializer\Serializer;
use Zoop\Shard\Serializer\Unserializer;
use Zoop\Shard\Core\Events;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractTest extends AbstractHttpControllerTestCase
{
    protected static $documentManager;
    protected static $serviceManager;
    protected static $dbName;
    protected static $serializer;
    protected static $unserializer;
    protected static $manifest;
    protected static $store;
    protected static $creator;
    public $calls;

    public function setUp()
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.config.php'
        );

        //create db connection and store requests
        if (!isset(self::$documentManager)) {
            self::$documentManager = $this->getApplicationServiceLocator()
                ->get('doctrine.odm.documentmanager.commerce');

            self::$dbName = $this->getApplicationServiceLocator()
                ->get('config')['doctrine']['odm']['connection']['commerce']['dbname'];

            $eventManager = self::$documentManager->getEventManager();
            $eventManager->addEventListener(Events::EXCEPTION, $this);

            if (!isset(self::$manifest)) {
                self::$manifest = $this->getApplicationServiceLocator()
                    ->get('shard.commerce.manifest');
            }

            if (!isset(self::$unserializer)) {
                self::$unserializer = self::$manifest->getServiceManager()
                    ->get('unserializer');
            }

            if (!isset(self::$serializer)) {
                self::$serializer = self::$manifest->getServiceManager()
                    ->get('serializer');
            }
        }
    }

    /**
     * Clears the DB
     */
    public static function tearDownAfterClass()
    {
        self::clearDatabase();
    }

    /**
     * Clears the DB
     */
    public static function clearDatabase()
    {
        if (self::$documentManager) {
            $collections = self::getDocumentManager()
                ->getConnection()
                ->selectDatabase(self::getDbName())
                ->listCollections();

            foreach ($collections as $collection) {
                /* @var $collection \MongoCollection */
                $collection->drop();
            }
            self::$documentManager->clear();
        }
    }

    /**
     * @return DocumentManager
     */
    public static function getDocumentManager()
    {
        return self::$documentManager;
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     * @return string
     */
    public static function getDbName()
    {
        return self::$dbName;
    }

    /**
     *
     * @return Manifest
     */
    public static function getManifest()
    {
        return self::$manifest;
    }

    /**
     *
     * @return Serializer
     */
    public static function getSerializer()
    {
        return self::$serializer;
    }

    /**
     *
     * @return Unserializer
     */
    public static function getUnserializer()
    {
        return self::$unserializer;
    }

    public function __call($name, $arguments)
    {
        var_dump($name, $arguments);
        $this->calls[$name] = $arguments;
    }
}
