@extends('layout.frontapp')
@section('title')
Checkout
@endsection
@section('content')

<div class="container">
    <h2>Available Subscriptions</h2>
    <div class="row">
        @foreach($subscriptions as $subscription)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $subscription->name }}</h5>
                    <p class="card-text">{{ $subscription->description }}</p>
                    <p class="card-text">Price: ${{ $subscription->price }}</p>
                    <form action="{{ route('subscriptions.subscribe', $subscription->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection