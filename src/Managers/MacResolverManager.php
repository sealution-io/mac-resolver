<?php

namespace Sealution\MacResolver\Managers;

use Illuminate\Support\Str;
use Sealution\MacResolver\Models\OuiAssignment;

class MacResolverManager
{

    public static function getOUI($macAddress): string
    {
        return substr(str_replace(['.', ':', '-'], '', $macAddress), 0, 6);
    }

    public static function getMacDetails($macAddress): ?array
    {
        $oui = static::getOUI($macAddress);
        $vendor = OuiAssignment::find($oui);

        if ($vendor == null)
            return null;

        return [
            'mac_address' => $macAddress,
            'oui' => $vendor[0]['oui'],
            'vendor' => Str::title($vendor[0]['organization']),
            'address' => $vendor[0]['address'],
            'private' => $vendor[0]['private'],
        ];
    }

}