<?php
use Illuminate\Support\Str;
class Util {
    /**
     * Función que devuelve un UUID ordenable
     * 
     * @return string   UUID ordenable
     */
    static function getUUID() {
            return (string) Str::orderedUuid();
    }
}