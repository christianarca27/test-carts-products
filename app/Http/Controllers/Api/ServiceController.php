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

            $myfile = fopen("storage/newCarts.txt", "a");
            $txt = "Carrello [" . $newCart->id . "] creato con successo.\n";
            fwrite($myfile, $txt);
            fclose($myfile);

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

            if ($request->has('quantity')) {
                $quantityToAdd = $formData['quantity'];
                if ($quantityToAdd < 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Parametri non validi.',
                    ]);
                }
            } else {
                $quantityToAdd = 1;
            }
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

        // controllo se è già stato aggiunto un prodotto di tipo $product
        if ($cart->products->contains($product)) {
            // in caso affermativo incremento solo la quantità
            $cart->products()->wherePivot('product_id', $product->id)->increment('quantity', $quantityToAdd);
        } else {
            // altrimenti aggiungo il record
            $cart->products()->attach($product, ['quantity' => $quantityToAdd]);
        }

        $myfile = fopen("storage/editCarts.txt", "a");
        $txt = "Carrello [" . $cart->id . "] modificato con successo, aggiunti " . $quantityToAdd . " x " . "prodotto [" . $product->id . "]\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        return response()->json([
            'success' => true,
            'message' => 'Prodotto aggiunto con successo.',
        ]);
    }

    public function deleteProduct(Request $request)
    {
        if ($request->has('cart') && $request->has('product')) {
            $formData = $request->all();

            if ($request->has('quantity')) {
                $quantityToRemove = $formData['quantity'];
                if ($quantityToRemove < 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Parametri non validi.',
                    ]);
                }
            } else {
                $quantityToRemove = 1;
            }
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
            $selectedProduct = $cart->products()->wherePivot('product_id', $product->id)->first();
            if ($selectedProduct->pivot->quantity > $quantityToRemove) {
                $selectedProduct->pivot->decrement('quantity', $quantityToRemove);

                $myfile = fopen("storage/editCarts.txt", "a");
                $txt = "Carrello [" . $cart->id . "] modificato con successo, rimossi " . $quantityToRemove . " x " . "prodotto [" . $product->id . "]\n";
                fwrite($myfile, $txt);
                fclose($myfile);

                return response()->json([
                    'success' => true,
                    'message' => 'Quantita decrementata con successo con successo.',
                ]);
            } else {
                $myfile = fopen("storage/editCarts.txt", "a");
                $txt = "Carrello [" . $cart->id . "] modificato con successo, rimossi " . $selectedProduct->pivot->quantity . " x " . "prodotto [" . $product->id . "]\n";
                fwrite($myfile, $txt);
                fclose($myfile);

                $cart->products()->detach($product);
                return response()->json([
                    'success' => true,
                    'message' => 'Prodotto rimosso con successo.',
                ]);
            }
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
