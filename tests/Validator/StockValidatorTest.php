<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\{Stock, StockValidator};
use App\Repository\{
    PurchaseRepository,
    SaleRepository,
};
use Symfony\Component\Validator\{
    Context\ExecutionContext,
    Violation\ConstraintViolationBuilder,
};
use PHPUnit\Framework\TestCase;

/**
 * StockValidatorTest
 */
final class StockValidatorTest extends TestCase
{
    /**
     * @var SaleRepository $saleRepositoryMock;
     */
    private SaleRepository $saleRepositoryMock;

    /**
     * @var PurchaseRepository $purchaseRepositoryMock;
     */
    private PurchaseRepository $purchaseRepositoryMock;

    protected function setUp()
    {
        $this->saleRepositoryMock = $this->createPartialMock(SaleRepository::class, ['totalStock']);
        $this->purchaseRepositoryMock = $this->createPartialMock(PurchaseRepository::class, ['totalStock']);
    }

    /**
     * Assert true with valid stock
     */
    public function testValidateWithStock()
    {
        $this->saleRepositoryMock
            ->expects($this->once())
            ->method("totalStock")
            ->will($this->returnValue(0))
            ;

        $this->purchaseRepositoryMock
            ->expects($this->once())
            ->method("totalStock")
            ->will($this->returnValue(1))
            ;

        $constrain = $this->createMock(Stock::class);

        $stockValidator = new StockValidator(
            $this->saleRepositoryMock,
            $this->purchaseRepositoryMock
        );

        $response = $stockValidator->validate(1, $constrain);
        $this->assertTrue($response);
    }

    /**
     * Should assert error as no stock available
     */
    public function testValidateWithNoStock()
    {
        $stock = 1;
        $this->saleRepositoryMock
            ->expects($this->once())
            ->method("totalStock")
            ->will($this->returnValue($stock))
            ;

        $this->purchaseRepositoryMock
            ->expects($this->once())
            ->method("totalStock")
            ->will($this->returnValue($stock))
            ;

        $constraint = $this->createMock(Stock::class);

        $stockValidator = new StockValidator(
            $this->saleRepositoryMock,
            $this->purchaseRepositoryMock
        );

        $constraintViolationBuilderMock = $this->createPartialMock(
            ConstraintViolationBuilder::class, [
            'addViolation'
        ]);

        $contextMock = $this->createPartialMock(
            ExecutionContext::class, [
            'buildViolation'
        ]);

        $contextMock
            ->expects($this->once())
            ->method("buildViolation")
            ->with('No stock available for sale.')
            ->will($this->returnValue($constraintViolationBuilderMock))
            ;

        $constraintViolationBuilderMock
            ->expects($this->once())
            ->method("addViolation")
            ;

        $stockValidator->initialize($contextMock);
        $stockValidator->validate($stock, $constraint);
    }
}
