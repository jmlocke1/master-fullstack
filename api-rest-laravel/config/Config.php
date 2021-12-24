<?php
namespace Config;
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Config
 *
 * @author jmizq_z
 */
class Config {
    
    /**
     * Expresión regular para validar un nombre o apellido
     */
    const VALIDATE_NAME = 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s]+$/';
    
    /**
     * Coste del algoritmo de generación de claves
     */
    const PASSWORD_COST = 10;

    /**
     * Rol del usuario
     */
    const ROLE_USER = 'ROLE_USER';
}
