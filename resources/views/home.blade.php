@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container py-5">
    <div class="row align-items-center gy-5">
        <div class="col-lg-6">
            <span class="badge bg-primary mb-3">Premium Car Rentals</span>
            <h1 class="display-5 fw-bold">RentNow makes booking rental cars fast and secure.</h1>
            <p class="lead text-muted">Browse the best cars, pick a date, and confirm your booking in minutes. Built with Laravel for a clean and maintainable experience.</p>
            <div class="mt-4">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Create account</a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Login</a>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80" alt="RentNow cars" class="img-fluid rounded-4 shadow-lg">
        </div>
    </div>
</div>
@endsection
