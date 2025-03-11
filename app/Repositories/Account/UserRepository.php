<?php

namespace App\Repositories\Account;

use App\Helpers\MenuHelper;
use App\Models\User;
use App\Repositories\MasterDataRepository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return User::class;
    }

    public static function update($id, $data)
    {
        $obj = self::find($id);

        MenuHelper::resetCacheByUser($id);

        return $obj->update($data);
    }

    public static function authenticatedUser(): User
    {
        return self::find(Auth::id());
    }

    public static function getByRole($roleId)
    {
        return User::whereHas('roles', function ($query) use ($roleId) {
            $query->whereId($roleId);
        })
            ->get();
    }

    public static function findByUsername($username)
    {
        return User::where('username', '=', $username)
        ->first();
    }

    public static function findByEmail($email)
    {
        return User::whereEmail($email)
        ->first();
    }

    public static function findByUsernameOrEmail($usernameOrEmail)
    {
        return User::where('username', '=', $usernameOrEmail)
            ->orWhere('email', '=', $usernameOrEmail)
            ->first();
    }

    public static function datatable($roleId)
    {
        return User::with('roles')
            ->when($roleId, function ($query) use ($roleId) {
                $query->whereHas('roles', function ($query) use ($roleId) {
                    $query->whereId($roleId);
                });
            });
    }
}
