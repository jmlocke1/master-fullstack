<?php
namespace app\Utilities;

use Illuminate\Support\Str;
use \Illuminate\Http\Response;

/**
 * Utilidades varias del sistema
 */
class Utilities {
    /**
     * Función que devuelve un UUID ordenable
     * 
     * @return string   UUID ordenable
     */
    public static function getUUID() {
            return (string) Str::orderedUuid();
    }
    
    /**
     * Devuelve un objeto Response con un mensaje en json
     * 
     * @param int $code                 Código HTTP
     * @param bool $success             Éxito o fracaso en la operación
     * @param string $message           Mensaje
     * @param array $additionalFields   Campos adicionales a añadir al mensaje
     * @return Response
     */
    public static function responseMessage(int $code, bool $success, string $message = '', array $additionalFields = []): Response{
        $data = self::message($code, $success, $message, $additionalFields);
        return response($data, $data['code']);
    }
    
    /**
     * Genera un mensaje de respuesta en un array
     * 
     * @param int $code                 Código HTTP
     * @param bool $success             Éxito o fracaso en la operación
     * @param string $message           Mensaje
     * @param array $additionalFields   Campos adicionales a añadir al mensaje
     * @return array
     */
    public static function message(int $code, bool $success, string $message = '', array $additionalFields = []): array{
        $data = [
            'code' => $code,
            'status' => $success ? 'success' : 'error'
        ];
        if(!empty($message)){
            $data['message'] = $message;
        }
        foreach ($additionalFields as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }
}