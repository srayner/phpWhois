<?php

use phpWhois\Provider\ProviderAbstract;
use phpWhois\Query;
use phpWhois\Response;

class ProviderAbstractMock extends ProviderAbstract
{
    protected $port = 77;

    public function connect()
    {
        $this->setConnectionErrNo(0);
        return $this;
    }

    public function setConnectionPointer($pointer)
    {
        return parent::setConnectionPointer($pointer);
    }

    public function isConnected()
    {
        return parent::isConnected();
    }

    public function performRequest()
    {
        return $this->getQuery()->getAddressOrig();
    }
}

class ProviderAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstructor($server, $serverE, $portE)
    {
        $provider = new ProviderAbstractMock(new Query('www.Google.com'), $server);

        $this->assertInstanceOf(Query::class, $provider->getQuery());
        $this->assertTrue($provider->getQuery()->hasData());
        $this->assertEquals($provider->getServer(), $serverE);
        $this->assertEquals($provider->getPort(), $portE);
        $this->assertInstanceOf(Response::class, $provider->getResponse());
    }

    public function constructProvider()
    {
        return [
            ['whois.nic.ru', 'whois.nic.ru', 77],
            ['whois.nic.ru:55', 'whois.nic.ru', 55],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorWithEmptyQuery()
    {
        $provider = new ProviderAbstractMock(new Query(), 'whois.iana.org');
    }

    public function testSetResponseHasProviderReference()
    {
        $provider = new ProviderAbstractMock(new Query('www.GOOGLE.com'), 'whois.iana.org');

        $provider->setSleep(200);
        $response = $provider->getResponse();
        $this->assertEquals(200, $response->getProvider()->getSleep());


        $provider->setSleep(100);
        $this->assertEquals(100, $response->getProvider()->getSleep());
    }

    public function testLookup()
    {
        $address = 'www.Google.COM';

        $provider = new ProviderAbstractMock(new Query($address), 'whois.iana.org');
        $response = $provider->lookup();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($address, $response->getRaw());
    }


    public function testIsConnected()
    {
        $provider = new ProviderAbstractMock(new Query('www.google.com'), 'whois.iana.org');
        $provider->setConnectionPointer(true);
        $this->assertTrue($provider->isConnected());
    }
}