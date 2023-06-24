@extends('layouts/app')

@section('content')
    <main>
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">created_at</th>
                        <th scope="col">updated_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            <td>{{ $cart->id }}</td>
                            <td>{{ $cart->created_at }}</td>
                            <td>{{ $cart->updated_at }}</td>
                            <td>
                                <a class="btn btn-outline-primary" href="{{ route('carts.show', $cart) }}">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
