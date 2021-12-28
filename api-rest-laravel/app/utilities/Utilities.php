<?php
namespace app\Utilities;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
    
    /**
     * Función que captura los datos mandados por post.
     * Devuelve un Array con los datos en json, en un objeto y en un array asociativo
     * Uso: Añadir esta línea donde se quieran capturar los datos:
     * [$json, $params, $params_array] = Utilities::getDataFromPost($request);
     * 
     * Hay que importar Utilities al principio con:
     * use App\Utilities\Utilities;
     * o usar el nombre totalmente cualificado:
     * [$json, $params, $params_array] = \App\Utilities\Utilities::getDataFromPost($request);
     * 
     * @param Request $request
     * @param string    $key    Nombre de la clave post por donde se manda el json
     * @return array    Array con los datos en json, en un objeto y en un array asociativo
     */
    public static function getDataFromPost(Request $request, string $key = 'json'){
        $json = $request->input($key, null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        return [$json, $params, $params_array];
    }
}