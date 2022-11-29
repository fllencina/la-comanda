<?php
require_once "./ManejoArchivos.php";
class ActividadController extends Actividad
{
    public function ExportarCSV()
    {
       $array= Actividad::obtenerTodos();
       Guardarcsv("/test.csv",$array,"w+");
       $data = []; 
       foreach ($array as $item) {
           $values = array_values((array) $item);
           array_push($data, implode(',', $values)); 
       }
       ob_flush();
       $csvData = join("\n", $data);
       header('Content-Disposition: attachment; filename=RegistroActividades.csv');
       header('Content-Type: application/csv; charset=UTF-8');
       die($csvData);
      
    }
    public function ImportarCSV($request, $response, $args)
    {
        $pathImagen="./Importar/";
        if (!is_dir($pathImagen)) {
            mkdir($pathImagen, 0777);
        }
        $Retorno = move_uploaded_file($_FILES["archivo"]["tmp_name"], "./Importar/importarActividades.csv");
        //var_dump($_FILES);
         $array=Leercsv("./Importar/importarActividades.csv");
         //var_dump($array);

         // eliminar todos los registros.
         Actividad::EliminarTodosLosDatos();
         //insertar nuevos datos importados
         date_default_timezone_set("America/Buenos_Aires");
        foreach($array as $datos)
        {
            $actividad = new Actividad();
            $actividad->id= $datos->id;
            $actividad->userid=$datos->userid;        
            $actividad->fecha=str_replace('"','',$datos->fecha);  
            $actividad->accion=$datos->accion;
            $actividad->observaciones=$datos->observaciones;
            $actividad->crear();
        }
         $payload = json_encode(array("mensaje" => "Registro de actividades importado con exito"));

         $response->getBody()->write($payload);
         return $response
           ->withHeader('Content-Type', 'application/json');
    }
}


?>
