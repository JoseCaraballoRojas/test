<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'boxes';

    protected $fillable = [
        'price', 'rows', 'columns',
    ];

    public function product() {
        return $this->belongsToMany('App\Product', 'items', 'box_id', 'product_id')
            ->withPivot('box_id', 'product_id', 'position');
    }
    
    /**
     * Crear cajas N numero de ileras por N alfajores en cada ilera con gustos aleatorios.
     *
     * @return \Illuminate\Http\Response
     */

    public static function fillBox($box)
    {
        //Cargar los alfajores registrados en la base de datos
        $products = Product::All();
        $qty_products = $box->rows * $box->columns;
        //Cargar la caja 
        for ($i=0; $i < $qty_products; $i++) 
        {

            // Se carga la caja por hileras de 3 alfajores
            $product = $products->random(1)->pluck('id');

            $box->product()->attach($product, ['position' => $i+1]);
        }
        
        return $box;
    }

    /**
     * Calcular el precio de las cajas de N hileras de N alfajores cada hilera.
     *
     * @return \Illuminate\Http\Response
     */
    public static function calculateCost($box)
    {
        /**
        * Reglas solicitadas para el calculo del costo de la caja por parte del cliente/=>>>
        * 1_ Dos alfajores idénticos alineados en cualquier sentido (horizontal, vertical,diagonal), uno al lado del otro: +$0,25/=>>>
        * 2_ Una hilera de tres alfajores idénticos: +$2,50 /==>
        * 3_ Tres alfajores idénticos alineados en cualquier sentido (horizontal, vertical, diagonal): +$0,60/=>>>
        * 4_ Si todos los alfajores de la caja son idénticos: -$3,40/=>>>
        * 5_ Si la posición central de la caja la ocupa un alfajor de frutilla: +$9,80 /=>>>
        * 6_ Agregar la siguiente regla al cálculo de precios: si la caja contiene al menos un alfajor de cada gusto, +$10,01/=>>>
        */
        
        $identical = TRUE;
        $aux = 0;
        $previous_item = 0;
        $price_box = 0.0;
        $h = 0;
        $box_aux = [];
        $different = [];
        $quantity_products = 6;
        $col = $box->columns;
        $row = $box->rows;
        $n = $col * $row;
        $ind = 0;
        for ($i=0; $i < $n; $i=$i+$col) { 
            for ($j=0; $j < $col; $j++) { 
                //Sumar valor individual de cada alfajor
                $price_box += $box->product[$i+$j]->value;

                //LLevar control para determinar si todos  los alfajor en la caja son identicos
                if ($box->product[$i+$j]->pivot->position == 1) {
                    $aux = $box->product[$i+$j]->id;
                }else {
                    $identical = (($identical) && ($aux == $box->product[$i+$j]->id)) ? TRUE : FALSE;
                    $aux = $box->product[$i+$j]->id;
                }

                $box_aux[$ind][$j] = [
                    'id' => $box->product[$i+$j]->id,
                    'taste' => $box->product[$i+$j]->taste,
                    'letter' => $box->product[$i+$j]->letter,
                    'values' => $box->product[$i+$j]->value,
                    'position' => $box->product[$i+$j]->pivot->position
                ];
            }
            $ind++;
        }

        //iterar en la caja auxiliar para determinar el cumplimiento de las demas reglas de calculo...
        for ($i=0; $i < $row; $i++) { 
            $cont = 0; 
            $hilera = false;
            $diagonal = 0;
            for ($j=0; $j < $col; $j++) { 
                if ($j) {
                    if ($box_aux[$i][$j]['id'] == $box_aux[$i][$j-1]['id']) {
                        $cont++;
                        $hilera = true;
                        //1_ Dos alfajores idénticos alineados en sentido (HORIZONTAL), uno al lado del otro: +$0,25
                        $price_box += 0.25;
                        if ($cont == 2) {
                            //3_ Tres alfajores idénticos alineados en sentido (HORIZONTAL): +$0,60
                            $price_box += 0.60;
                            $cont = 0;
                        }
                    }else {
                        $cont = 0;
                    }
  
                    if (isset($box_aux[$j][$j]['id']) && isset($box_aux[$j-1][$j-1]['id'])) {
                        if ($box_aux[$j][$j]['id'] == $box_aux[$j-1][$j-1]['id']) {
                            $diagonal++;
                            //1_ Dos alfajores idénticos alineados en sentido (DIAGONAL Principal), uno al lado del otro: +$0,25
                            $price_box += 0.25;
                            if ($diagonal == 2) {
                                //3_ Tres alfajores idénticos alineados en sentido (DIAGONAL): +$0,60
                                $price_box += 0.60;
                                $diagonal = 0;
                            }
                            
                        }else {
                            $diagonal = 0;
                        }
                    }

                    if (isset($box_aux[$i][$j]['id']) && isset($box_aux[$i+1][$j+1]['id'])) {
                        if ($box_aux[$i][$j]['id'] == $box_aux[$i+1][$j+1]['id']) {
                            //1_ Dos alfajores idénticos alineados en sentido (DIAGONAL secundarias), uno al lado del otro: +$0,25
                            $price_box += 0.25;  
                        }
                    }

                    if (isset($box_aux[$i][$j]['id']) && isset($box_aux[$i+1][$j-1]['id'])) {
                        if ($box_aux[$i][$j]['id'] == $box_aux[$i+1][$j-1]['id']) {
                            //1_ Dos alfajores idénticos alineados en sentido (DIAGONAL secundarias inversas), uno al lado del otro: +$0,25
                            $price_box += 0.25;  
                        }
                    }
                }

                //acumular los sabores distintos para determinar si hay al menos uno de cada uno
                if (!in_array($box_aux[$i][0]['id'], $different)) {
                    $different[] = $box_aux[$i][0]['id'];
                }
                if (!in_array($box_aux[$i][1]['id'], $different)) {
                    $different[] = $box_aux[$i][1]['id'];
                }
                if (!in_array($box_aux[$i][2]['id'], $different)) {
                    $different[] = $box_aux[$i][2]['id'];
                }

            }
            //2_ Una hilera de tres alfajores idénticos: +$2,50
            if ($hilera) {
                $price_box += 2.50;
            }

        }
        for ($j=0; $j < $col; $j++) {
            $cont = 0;
            $diagonal = 0; 
            for ($i=0; $i < $row; $i++) { 
                if ($i) {
                    if ($box_aux[$i][$j]['id'] == $box_aux[$i-1][$j]['id']) {
                        $cont++;
                        //1_ Dos alfajores idénticos alineados en sentido (VERTICAL), uno al lado del otro: +$0,25
                        $price_box += 0.25;
                        if ($cont == 2) {
                            //3_ Tres alfajores idénticos alineados en sentido (VERTICAL): +$0,60
                            $price_box += 0.60;
                            $cont = 0;
                        }
                    }else {
                        $cont = 0;
                    }
                }
              
            }
        }
            
        if (($col == $row) && ($row % 2 != 0 )) {
            $center = ($row % 2) + (intdiv($row, 2));
            //5_ Si en la posición central de la caja la ocupa un alfajor de frutilla: +$9,80
            $price_box +=  strtolower($box->product[$center]->taste) == 'frutilla' ? 9.80 : 0.0;
        }
        
        //4_ Si todos los alfajores de la caja son idénticos: -$3,40
        $price_box -=  ($identical) ? 3.40 : 0;

        //6_ Agregar la siguiente regla al cálculo de precios: si la caja contiene al menos un alfajor de cada gusto, +$10,01
        $price_box += count($different) == $quantity_products ? 10.01 : 0.0;

        return [
            'price_box'=> $price_box, 
            'product' => $box_aux
        ];
               
    }

}
