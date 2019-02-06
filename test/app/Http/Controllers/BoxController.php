<?php

namespace App\Http\Controllers;

use App\Product;
use App\Box;

use Illuminate\Http\Request;

class BoxController extends Controller
{
    /**
    * Crear cajas N x N   alfajores con gustos aleatorios.
    *
    * @return \Illuminate\Http\Response
    */

    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required',
            'rows' => 'required_if:dinamic,true',
            'columns' => 'required_if:dinamic,true'
        ]);

        $box = Box::create($request->all());

        $filledBox = Box::fillBox($box);

        $priceBox = Box::calculateCost($filledBox);

        $box->price = $priceBox['price_box'];
        
        $box->save();
        
        return response()->json([
            'status' => true,
            'data' => $priceBox,
            'message' => 'Box created successfully!',
        ]);
    }

    /**
    * reporte top 3 de la mejores cajas segun su precio mas alto.
    *
    * @return \Illuminate\Http\Response
    */
    public function top()
    {
        $query = Box::All()->sortByDesc('price')->take(3);

        foreach ($query->toArray() as $top) {
            $boxes[] = $top;
        }

        return response()->json([
            'status' => true,
            'data' => $boxes,
            'msg' => 'The top has been loaded successfully',
        ]);
    }

}
