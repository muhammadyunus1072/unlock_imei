<?php

namespace App\Helpers;

use App\Permissions\PermissionHelper;
use App\Models\Account\User;
use App\Repositories\Account\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class MenuHelper
{
    public static function menu()
    {
        $user = Auth::user();
        $validatedMenu = Cache::remember(CacheHelper::KEY_MENU . $user->id, CacheHelper::TIME_MENU, function () {
            return self::getValidatedMenu();
        });

        return self::markActiveMenu($validatedMenu);
    }

    private static function getValidatedMenu()
    {
        $user = Auth::user();
        $menus = config('template.menu');

        $validatedMenu = [];
        foreach ($menus as $menu) {
            $menu['is_active'] = 0;

            if (isset($menu['submenu'])) {
                $validatedSubmenu = [];
                foreach ($menu['submenu'] as $submenu) {
                    if (!isset($submenu['route']) || PermissionHelper::isRoutePermitted($submenu['route'], $user)) {
                        $validatedSubmenu[] = $submenu;
                    }
                }

                if (count($validatedSubmenu) > 0) {
                    $menu['submenu'] = $validatedSubmenu;
                    $validatedMenu[] = $menu;
                }
            } else {

                if (!isset($menu['route']) || PermissionHelper::isRoutePermitted($menu['route'], $user)) {
                    $validatedMenu[] = $menu;
                }
            }
        }

        return $validatedMenu;
    }

    private static function markActiveMenu($menus)
    {
        $currentRoute = Route::currentRouteName();

        foreach ($menus as $keyMenu => $menu) {
            $menus[$keyMenu]['is_active'] = isset($menu['route']) && $menu['route'] == $currentRoute;

            if (isset($menu['submenu'])) {
                foreach ($menu['submenu'] as $keySubmenu => $submenu) {
                    $menus[$keyMenu]['submenu'][$keySubmenu]['is_active'] = 0;
                    if (isset($submenu['route']) && $submenu['route'] == $currentRoute) {
                        $menus[$keyMenu]['is_active']['is_active'] = 1;
                        $menus[$keyMenu]['submenu'][$keySubmenu]['is_active'] = 1;
                    }
                }
            }
        }

        return $menus;
    }

    public static function resetCacheByUser($userId)
    {
        Cache::forget(CacheHelper::KEY_MENU . $userId);
    }

    public static function resetCacheByRole($roleId)
    {
        $users = UserRepository::getByRole($roleId);
        foreach ($users as $user) {
            Cache::forget(CacheHelper::KEY_MENU . $user->id);
        }
    }
}
