<?php
function GuardarFoto($file, $Nombre,$pathImagen)
    {        
        if (!is_dir($pathImagen)) {
            mkdir($pathImagen, 0777);
        }
        $dic = $pathImagen;
        $nameImagen = $file["name"];
        //echo $nameImagen;
        $explode = explode(".", $nameImagen);
        $tamaño = count($explode);
        $dic .= $Nombre;
        $dic .= ".";
        $dic .= $explode[$tamaño - 1];
        //echo $dic;
        $Retorno = false;
        var_dump($_FILES["foto"]["tmp_name"]);

        $Retorno = move_uploaded_file($_FILES["foto"]["tmp_name"], $dic);
        var_dump($dic);
        var_dump($Retorno);
        return $Retorno;
    }

    function Guardarcsv($path, $Array, $modoApertura)
    {
        $retorno = false;
        $aperturaOK = false;
        switch ($modoApertura) {
            case 'a+':
                $file = fopen($path, "a+");
                $aperturaOK = true;
                break;
            case 'w+':
                $file = fopen($path, "w+");
                $aperturaOK = true;
                break;
            default:
    
                echo "No selecciono modo de apertura valido";
                return $retorno;
                break;
        }
        if ($aperturaOK) {
            for ($i = 0; $i < count($Array); $i++) {
                $linea = array($Array[$i]->id, $Array[$i]->userid, $Array[$i]->fecha,$Array[$i]->accion,$Array[$i]->observaciones);
                if (fputcsv($file, $linea)) {
                    $retorno = true;
                }
            }
            fclose($file);
        }
    
        return $retorno;
    }
    function AgregarUnUsuarioCSV($path, $Array)
    {
        Guardarcsv($path, $Array, 'a+');
    }
    function SobreEscribirUsuariosCSV($path, $Array)
    {
        Guardarcsv($path, $Array, 'w+');
    }
    
    function Leercsv($path)
    {
        $elementosArray = [];
    
        if (file_exists($path)) {
            $file = fopen($path, "r");
    
            while (!feof($file)) {
                $linea = fgets($file);
                if (!empty($linea)) {
                   // var_dump($linea);
                    $datos = explode(",", $linea);
                    $id = $datos[0];
                    $userid = $datos[1];
                    $fecha = $datos[2];
                    $accion = $datos[3];
                    $observaciones = $datos[4];
                    if($datos[4]=='')
                    {
                        $observaciones=null;
                    }
                    
                    $actividad = new Actividad();
                    $actividad->id=$id;
                    $actividad->userid=$userid;
                    $actividad->fecha=$fecha;
                    $actividad->accion=$accion;
                    $actividad->observaciones=$observaciones;

                    //var_dump($actividad);
                    array_push($elementosArray, $actividad);
                }
            }
            fclose($file);
        }
        return $elementosArray;
    }

    ?>