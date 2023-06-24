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
        if ($request->has('products')) {
            $formData = $request->all();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Parametri non validi.',
            ]);
        }

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

    public function addProduct(Request $request)
    {
        if ($request->has('cart') && $request->has('product')) {
            $formData = $request->all();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Parametri non validi.',
            ]);
        }

        $cart = Cart::where('id', $formData['cart'])->first();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Carrello non esistente.',
            ]);
        }

        $product = Product::where('id', $formData['product'])->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Prodotto non esistente.',
            ]);
        }

        $cart->products()->attach($product);
        return response()->json([
            'success' => true,
            'message' => 'Prodotto aggiunto con successo.',
        ]);
    }

    public function deleteProduct(Request $request)
    {
        if ($request->has('cart') && $request->has('product')) {
            $formData = $request->all();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Parametri non validi.',
            ]);
        }

        $cart = Cart::where('id', $formData['cart'])->first();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Carrello non esistente.',
            ]);
        }

        $product = Product::where('id', $formData['product'])->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Prodotto non esistente.',
            ]);
        }

        if ($cart->products->contains($product)) {
            $cart->products()->detach($product);

            return response()->json([
                'success' => true,
                'message' => 'Prodotto aggiunto con successo.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Prodotto non presente nel carrello.',
            ]);
        }
    }

    public function getCarts()
    {
        $carts = Cart::with('products')->get();

        return response()->json([
            'success' => true,
            'carts' => $carts,
        ]);
    }
}
