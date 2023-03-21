<?php

    class Conexion
    {

        //Objeto que almacena la conexión
        protected $pdo;

        //Método que accede al servidor y BD
        public function Conectar()
        {
            $conexion = new PDO("mysql:host=localhost;port=3306;dbname=website;charset=utf8", "root", "");
            //$conexion = new PDO("mysql:host=localhost; dbname=id20131721_mvc1; charset=utf8", "id20131721_fabrizio", "Rodrigo-barrios-2");
            return $conexion;
        }

        //Método que retorna el acceso(conexión)
        public function getConexion()
        {
            try {
                //Almacenamos la conexión
                $pdo = $this->Conectar();

                //Controlar excepciones
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Retorno de la conexión
                return $pdo;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

?>