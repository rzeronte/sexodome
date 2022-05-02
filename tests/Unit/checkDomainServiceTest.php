<?php

namespace Tests\Unit;

use Tests\TestCase;
use Sexodome\SexodomeApi\Application\CheckDomainCommandHandler;

class checkDomainServiceTest extends TestCase
{
    public function testSimpleAndNotValid()
    {
        $domain = "hola";
        $service = new CheckDomainCommandHandler();
        $result = $service->execute( $domain );

        $this->assertFalse($result['status']);
    }

    public function testDomainWithHttp()
    {
        $domain = "http://prueba.com";
        $service = new CheckDomainCommandHandler();
        $result = $service->execute( $domain );

        $this->assertFalse($result['status']);
    }

    public function testDomainWithSubdomain()
    {
        $domain = "dominio.prueba2.com";
        $service = new CheckDomainCommandHandler();
        $result = $service->execute( $domain );

        $this->assertFalse($result['status']);
    }

    public function testEmptyDomain()
    {
        $domain = "";
        $service = new CheckDomainCommandHandler();
        $result = $service->execute( $domain );

        $this->assertFalse($result['status']);
    }

    public function testSuccessDomain()
    {
        $domain = "randomdomain.com";
        $service = new CheckDomainCommandHandler();
        $result = $service->execute( $domain );

        $this->assertTrue($result['status']);
    }

    public function testRandomCharacteres()
    {
        $domain = "34234%d'a6&f/a·d!!f.com";
        $service = new CheckDomainCommandHandler();
        $result = $service->execute( $domain );

        $this->assertFalse($result['status']);
    }
}
