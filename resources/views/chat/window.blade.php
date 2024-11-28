<!-- resources/views/chat/window.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Chat with {{ $lecturer->name }}</h3>
        <div id="chat-box">
            <!-- Messages will be loaded here -->
        </div>
        <form id="chat-form" method="POST" action="{{ route('chat.send', $lecturer->id) }}">
            @csrf
            <textarea name="message" id="message" rows="3" required></textarea>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>

    <script>
        // Use AJAX for sending messages
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            let message = document.getElementById('message').value;
            let url = this.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            }).then(response => response.json())
              .then(data => {
                  document.getElementById('message').value = '';
                  // Optionally, update the chat-box with the new message
              });
        });
    </script>
@endsection
