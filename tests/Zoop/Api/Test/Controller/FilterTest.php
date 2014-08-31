<?php

namespace Zoop\Api\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Mvc\MvcEvent;
use Zoop\Api\Test\AbstractTest;
use Zoop\Api\Test\Assets\TestModel;

class Filter extends AbstractTest
{
    public function testGetRequestSucceed()
    {
        $this->createTestData();
        
        $accept = new Accept;
        $accept->addMediaType('application/json');

        $this->getRequest()
            ->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                ContentType::fromString('Content-type: application/json'),
                Origin::fromString('Origin: http://api.zoopcommerce.com')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/test');

        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(200);
        
        $content = $response->getContent();
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertCount(5, $data);
        
        return $data[0];
    }
    
    /**
     * @depends testGetRequestSucceed
     */
    public function testGetRequestFilterSucceed($testData)
    {
        //attached a zone filter so that we only return 1 of the test documents
        $this->attachZoneEvent();
        
        $accept = new Accept;
        $accept->addMediaType('application/json');

        $this->getRequest()
            ->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                ContentType::fromString('Content-type: application/json'),
                Origin::fromString('Origin: http://api.zoopcommerce.com')
            ]);

        $this->dispatch(sprintf('http://api.zoopcommerce.local/name/%s/test', $testData['zones'][0]));

        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(200);
        
        $content = $response->getContent();
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertCount(1, $data);
    }
    
    protected function attachZoneEvent()
    {
        $app = $this->getApplication();
        $eventManager = $app->getEventManager();
        $eventManager->attach('route', [$this, 'applyZone']);
    }
    
    public function applyZone(MvcEvent $event)
    {
        $data = $event->getRouteMatch()->getParam('name');
        $sm = $event->getApplication()->getServiceManager();
        $manifest = $sm->get('shard.commerce.manifest');
        
        $extension = $manifest->getServiceManager()->get('extension.zone');
        $extension->setReadFilterInclude([$data]);
    }

    protected function createTestData()
    {
        for ($i = 0; $i < 5; $i++) {
            $test = new TestModel;
            $test->setName('Name-' . $i);
            $test->setZones(['Name-' . $i]);
            self::getDocumentManager()->persist($test);
        }
        self::getDocumentManager()->flush();
        self::getDocumentManager()->clear();
    }
}
