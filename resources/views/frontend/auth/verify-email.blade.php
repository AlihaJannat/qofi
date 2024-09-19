@extends('layout.frontapp')
@section('title')
    Login
@endsection
@section('content')
<main class="login-container flex justify-center items-center py-12">
    <div class="container mx-auto flex justify-center">
        <div class="w-full max-w-2xl p-10 rounded shadow-sm bg-card-bg text-center">

            <h2 class="text-4xl font-extrabold text-center text-black-5 mb-8">Email Not Verified</h2>
            <p>Click Verify Email Address button in your mail</p>
            <br><br>
            <p>If you have not recieved email. click below button</p>
            <a href="#" onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();">
                <button class="btn btn-info">Resend Email</button>
            </a>

            <form id="resend-verification-form" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
                @csrf
            </form>
            
        </div>
    </div>
</main>
@endsection

