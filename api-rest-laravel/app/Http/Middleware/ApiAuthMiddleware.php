<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Comprobar si el usuario está autentificado
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if($checkToken){
            // Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);
            // Lo añadimos al request para pasarlo al controlador
            $request->request->add(['user' => $user]);
            return $next($request);
        }else{
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no está identificado'
            );
            return response($data, $data['code']);
        }
    }
}
