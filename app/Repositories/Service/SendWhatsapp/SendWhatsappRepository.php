<?php

namespace App\Repositories\Service\SendWhatsapp;

use App\Models\Service\SendWhatsapp;
use App\Repositories\MasterDataRepository;

class SendWhatsappRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return SendWhatsapp::class;
    }

    public static function datatable()
    {
        return SendWhatsapp::query();
    }
}
