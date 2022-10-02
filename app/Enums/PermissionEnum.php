<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self view_roles()
 * @method static self manage_roles()
 * @method static self view_users()
 * @method static self manage_users()
 * @method static self view_products()
 * @method static self manage_products()
 */
final class PermissionEnum extends Enum
{
}
