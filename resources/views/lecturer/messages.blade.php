@extends('layouts.temp')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center" style="background-color: #ffffff;">
            <h1>Student Messages</h1>
        </div>
        <div class="card-body chat-history">
            @foreach($chats as $chat)
            <div class="d-flex justify-content-start mb-2">
                <div class="p-2 bg-light rounded">
                    <p class="mb-0"><strong>Anonymous Student:</strong> {{ $chat->message }}</p>
                </div>
                <form action="{{ route('chat.reply', ['chat' => $chat->id]) }}" method="POST">
                    @csrf
                    <textarea name="message" class="form-control" placeholder="Reply..." required></textarea>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
