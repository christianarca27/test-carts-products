@extends('layouts/app')

@section('content')
    <main>
        <div class="container">
            <h1>Carrello {{ $cart->id }}</h1>

            <h2>Prodotti</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Prezzo</th>
                        <th scope="col">Quantità</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->products()->withTrashed()->get() as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>€ {{ $product->price }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('carts.index') }}">Torna alla lista dei carrelli</a>
        </div>
    </main>
@endsection
