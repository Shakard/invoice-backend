<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getAll()
    {
        $data = Client::get();
        return response()->json($data, 200);
    }

    public function autocomplete(Request $request)
    {
        $data = Client::where('name', 'LIKE', '%' . $request->get('query') . '%')->get();

        return response()->json($data);
    }

    public function addReceptor(Request $request)
    {
        $data['ruc'] = $request['ruc'];
        $data['razon_social'] = $request['name'];
        $data['telefono'] = $request['phone'];
        $data['direccion'] = $request['address'];
        $data['correo'] = $request['email'];

        Client::create($data);

        return response()->json([
            'message' => "Successfully created",
            'success' => true
        ], 200);
    }

    public function delete($id)
    {
        $res = Client::find($id)->delete();
        return response()->json([
            'message' => "Successfully deleted",
            'success' => true
        ], 200);
    }

    public function get($id)
    {
        $data = Client::find($id);
        return response()->json($data, 200);
    }

    public function updateReceptor(Request $request, $id)
    {
        $data['ruc'] = $request['ruc'];
        $data['razon_social'] = $request['name'];
        $data['telefono'] = $request['phone'];
        $data['direccion'] = $request['address'];
        $data['correo'] = $request['email'];
        Client::find($id)->update($data);
        return response()->json([
            'message' => "Successfully updated",
            'success' => true
        ], 200);
    }
}
