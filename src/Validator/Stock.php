<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Stock extends Constraint
{    
    public $message = 'No stock available for sale.';
}
