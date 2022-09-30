<?php

namespace Tests\Builders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class UserBuilder
{
    /**
     * @var array<int, PermissionEnum>
     */
    private $permissions = [];

    public static function make(): self
    {
        return new self();
    }

    public function addPermission(PermissionEnum $permission): self
    {
        $this->permissions[] = $permission;

        return $this;
    }

    public function build(): Authenticatable
    {
        /** @var User */
        $user = User::factory()->create();

        $permissions = Permission::query()
            ->whereIn('name', $this->permissions)
            ->get();

        $user->givePermissionTo($permissions);

        return $user;
    }
}
