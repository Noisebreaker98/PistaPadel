<?php

class ControladorTramos
{
    public function obtener()
    {
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionBD = new ConexionBD(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionBD->getConexion();

        // Obtener los tramos desde el TramosDAO
        $tramosDAO = new TramosDAO($conn);
        $tramos = $tramosDAO->getAll();

        $tramosJson = [];

        foreach($tramos as $tramo){
            $tramosJson[] = $tramo->toJSON();
        }

         // Devolver los tramos en formato JSON
        print json_encode($tramosJson);
        
    }
}
