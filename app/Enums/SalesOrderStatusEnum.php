<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self draft()
 * @method static self processing()
 * @method static self packing()
 * @method static self delivering()
 * @method static self completed()
 * @method static self canceled()
 */
class SalesOrderStatusEnum extends Enum
{
}
