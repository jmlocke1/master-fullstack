<?php
namespace app\Utilities;

use Illuminate\Support\Str;
class Utilities {
    /**
     * Función que devuelve un UUID ordenable
     * 
     * @return string   UUID ordenable
     */
    static function getUUID() {
            return (string) Str::orderedUuid();
    }
}