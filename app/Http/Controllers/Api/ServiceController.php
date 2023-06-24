<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function newCart(Request $request)
    {
        $formData = $request->all();

        $validProducts = [];
        foreach ($formData['products'] as $product) {
            if (Product::where('id', $product)->first()) {
                array_push($validProducts, $product);
            }
        }

        if (count($validProducts)) {
            $newCart = new Cart();
            $newCart->save();

            foreach ($validProducts as $product) {
                $newCart->products()->attach($product);
            }

            return response()->json([
                'success' => true,
                'message' => 'Carrello creato con successo.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Inserisci almeno un prodotto valido.',
        ]);
    }
}
