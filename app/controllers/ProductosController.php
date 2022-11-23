<?php
require_once './models/Productos.php';


class ProductosController extends Productos 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $prod = new Productos();
        $prod->descripcion = $parametros['descripcion'];
        $prod->idsector = $parametros['idsector'];
        $prod->precio = $parametros['precio'];
               
        $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $prod = $args['descripcion'];
        $producto = Productos::obtenerProducto($prod);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Productos::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Productos::modificarProducto($id);

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
}
