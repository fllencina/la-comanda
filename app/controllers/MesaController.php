<?php
require_once './models/Mesa.php';


class MesaController extends Mesa 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $Mesa = new Mesa();
        $Mesa->descripcion = $parametros['descripcion'];
        $Mesa->idestadomesa = 4;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $Mesa->id =substr(str_shuffle($permitted_chars), 0, 5);  

        $Mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $mesa = $args['descripcion'];
        $Mesa = Mesa::obtenerMesa($mesa);
        $payload = json_encode($Mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerMasUsada($request, $response, $args)
    {
        $Mesa = Pedido::obtenerMesaMasUsada();
        $payload = json_encode($Mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $header = $request->getHeaderLine('Authorization');
        if(isset($header) && $header)
        {
          $token = trim(explode("Bearer", $header)[1]);
          $data=AutentificadorJWT::ObtenerData($token);
          $UsuarioLogin=Usuario::obtenerUsuario($data->usuario);
       }
        $id = $parametros['id'];
        
        $Mesa=Mesa::obtenerMesa($id);
        $modificar=true;
        $mensaje='Mesa modificada con exito';
        $cerrar=false;

        if(($parametros['idestado']==1 && $Mesa->idestadomesa==4)||($parametros['idestado']==2 && $Mesa->idEstadoMesa==1)||($parametros['idestado']==3 && $Mesa->idestadomesa==2))
        {
          $Mesa->idestadomesa=$parametros['idestado'];          
        }
        else if($parametros['idestado']==4 && $Mesa->idestadomesa==3 && $data->rol==10)
          {
            $Mesa->idestadomesa=$parametros['idestado'];
            $cerrar=true;
          }
        
        else{
          $modificar=false;
          $mensaje="No se pudo modificar la mesa, el estado que intenta establecer no concuerda con el estado previo. o no cuenta con los permisos para realizarlo.";
        }
        if($modificar==true)
        { 
          $Mesa->modificarMesa();
          
            $actividad=new Actividad();
            $actividad->userid=$UsuarioLogin->id;
            $actividad->fecha=date("Y-m-d H:i:s");
            if($cerrar==true)
            {
              $actividad->accion=9;
            }
            else{
              $actividad->accion=10;
            }
            
            $actividad->observaciones=$Mesa->id;
            $actividad->crear();
            
        }

        $payload = json_encode(array("mensaje" =>  $mensaje));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
}
