<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAll()
    {
        $data = Producto::get();
        return response()->json($data, 200);
    }

    public function addProduct(Request $request)
    {
        $data['codigo'] = $request['code'];
        $data['descripcion'] = $request['item'];
        $data['cantidad'] = $request['quantity'];
        $data['precio_venta'] = $request['price'];

        Producto::create($data);

        return response()->json([
            'message' => "Successfully created",
            'success' => true
        ], 200);
    }

    public function delete($id)
    {
        $res = Producto::find($id)->delete();
        return response()->json([
            'message' => "Successfully deleted",
            'success' => true
        ], 200);
    }

    public function get($id)
    {
        $data = Producto::find($id);
        return response()->json($data, 200);
    }

    public function updateProduct(Request $request, $id)
    {
        $data['codigo'] = $request['code'];
        $data['descripcion'] = $request['item'];
        $data['cantidad'] = $request['quantity'];
        $data['precio_venta'] = $request['price'];
        Producto::find($id)->update($data);
        return response()->json([
            'message' => "Successfully updated",
            'success' => true
        ], 200);
    }
}
