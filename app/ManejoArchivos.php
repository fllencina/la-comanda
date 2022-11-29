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



    ?>