@extends('layouts/app')

@section('content')
    <main>
        <div class="container py-5">
            <h1>Prodotti</h1>

            <div class="row g-3">
                @foreach ($products as $product)
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }} [{{ $product->id }}]</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">SKU: {{ $product->SKU }}</h6>
                                <p class="card-text">€ {{ $product->price }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
