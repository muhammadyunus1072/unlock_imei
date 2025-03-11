<?php

namespace App\Repositories\Account;

use App\Models\UserStudio;
use App\Repositories\MasterDataRepository;

class UserStudioRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return UserStudio::class;
    }

    public static function createIfNotExist($data)
    {
        $check = UserStudio::where('user_id', $data['user_id'])
            ->where('studio_id', $data['studio_id'])
            ->first();

        if ($check) {
            return;
        }

        self::create($data);
    }

    public static function deleteExcept($userId, $ids)
    {
        $deletedData = UserStudio::where('user_id', $userId)
            ->whereNotIn('studio_id', $ids)
            ->get();

        foreach ($deletedData as $item) {
            $item->delete();
        }
    }
}
