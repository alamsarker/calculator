<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Stock extends Constraint
{
    /**
     * @var string $message The error message is shown if not stock avaiable on sale.
     */
    public string $message = 'No stock available for sale.';
}
