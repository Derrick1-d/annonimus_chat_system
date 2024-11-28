@extends('layouts.temp')

@section('content')
<div class="container justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4 text-center">Lecturer Dashboard</h1>

            <h2 class="mb-4 text-center">Students Who Sent Messages</h2>
            <a href="{{ route('lecturer.messages') }}" class="btn btn-primary">View Student Messages</a>

        </div>
    </div>

    <div class="row justify-content-center mt-4">
        @foreach($students as $student)
            <div class="col-md-6 col-lg-4">
                <div class="card mb-4 custom-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $student->name }}</h5>
                        <p class="card-text">You have a message from {{ $student->name }}.</p>
                        <a href="{{ route('lecturer.reply', ['student' => $student->id]) }}" class="btn btn-primary">
                            Reply to {{ $student->name }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('lecturer.toggleIdentity') }}" method="POST" class="mt-4 text-center">
        @csrf
        <button type="submit" class="btn btn-secondary">Toggle Identity Visibility</button>
    </form>
</div>

<!-- Custom CSS -->
<style>
    .custom-card {
        width: 100%;
        margin: 0 auto; /* Center the card horizontally */
    }

    .container {
        max-width: 1000px; /* Set a specific size for the main container */
    }

    .card {
        background-color: #ffffff; /* Ensure the card has a white background */
        border: 1px solid #dddddd; /* Optional: add a subtle border */
    }
</style>
@endsection
