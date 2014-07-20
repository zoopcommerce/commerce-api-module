<?php

namespace Zoop\Api\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zoop\Api\Test\AbstractTest;
use Zoop\Api\Test\Assets\TestModel;

class EndpointTest extends AbstractTest
{
    public function testOptionsRequestSucceed()
    {
        $accept = new Accept;
        $accept->addMediaType('application/json');

        $this->getRequest()
            ->setMethod('OPTIONS')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://apple.zoopcommerce.com')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/test');

        $response = $this->getResponse();

        $this->assertResponseStatusCode(201);
    }
    
    public function testGetRequestSucceed()
    {
        $this->createTestData();
        
        $accept = new Accept;
        $accept->addMediaType('application/json');

        $this->getRequest()
            ->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://apple.zoopcommerce.com')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/test');

        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(200);
        
        $content = $response->getContent();
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertCount(5, $data);
    }

    public function testHealthEndpoint()
    {
        $accept = new Accept;
        $accept->addMediaType('application/json');

        $request = $this->getRequest();

        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
            ]);

        $this->dispatch('http://api.zoopcommerce.local/ping');

        $response = $this->getResponse();

        $this->assertResponseStatusCode(204);

        $content = $response->getContent();
        $this->assertEmpty($content);
    } 
    
    protected function createTestData()
    {
        for ($i = 0; $i < 5; $i++) {
            $test = new TestModel;
            $test->setName('Name' . rand(10, 1000));
            
            self::getDocumentManager()->persist($test);
            self::getDocumentManager()->flush($test);
            self::getDocumentManager()->clear($test);
        }
    }
}
