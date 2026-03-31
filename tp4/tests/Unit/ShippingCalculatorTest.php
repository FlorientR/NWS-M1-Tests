<?php

namespace App\Tests\Unit;

use App\Repository\ShippingRateRepository;
use App\Service\ShippingCalculator;
use PHPUnit\Framework\TestCase;

/**
 * TP4 - Test unitaire du ShippingCalculator (version corrigée du TP1).
 * Sert de référence pour mesurer le temps d'exécution parallèle.
 */
class ShippingCalculatorTest extends TestCase
{
    private ShippingCalculator $calculator;

    protected function setUp(): void
    {
        $repository      = $this->createStub(ShippingRateRepository::class);
        $this->calculator = new ShippingCalculator($repository);
    }

    public function testColisLegerRetourneCinqEuros(): void
    {
        self::assertSame(5.00, $this->calculator->calculate(3.0));
    }

    public function testColisMoyenRetourneDixEuros(): void
    {
        self::assertSame(10.00, $this->calculator->calculate(7.5));
    }

    public function testColisLourdRetourneVingtEuros(): void
    {
        self::assertSame(20.00, $this->calculator->calculate(15.0));
    }

    public function testColisTresLourdRetourneCinquanteEuros(): void
    {
        self::assertSame(50.00, $this->calculator->calculate(40.0));
    }

    public function testPoidsNegatifLeveException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->calculate(-1.0);
    }

    public function testPoidsNulLeveException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->calculate(0.0);
    }

    public function testLimiteHautePremiereTranche(): void
    {
        self::assertSame(5.00, $this->calculator->calculate(5.0));
    }

    public function testLimiteBasseDerniereTranche(): void
    {
        self::assertSame(50.00, $this->calculator->calculate(30.1));
    }
}
