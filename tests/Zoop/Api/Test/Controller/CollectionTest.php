<?php

namespace Zoop\Api\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zoop\Api\Test\AbstractTest;

class CollectionTest extends AbstractTest
{
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
}
