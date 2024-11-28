<!-- resources/views/chat/lecturer_reply.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Reply to {{ $student->name === 'Anonymous' ? 'Anonymous Student' : $student->name }}</h3>
        <div id="chat-box">
            <!-- Messages will be loaded here -->
        </div>
        <form id="reply-form" method="POST" action="{{ route('chat.reply', $chat->id) }}">
            @csrf
            <textarea name="message" id="message" rows="3" required></textarea>
            <div>
                <label>
                    <input type="checkbox" name="is_identity_visible" checked>
                    Show my identity
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Send Reply</button>
        </form>
    </div>

    <script>
        document.getElementById('reply-form').addEventListener('submit', function(e) {
            e.preventDefault();
            let message = document.getElementById('message').value;
            let isIdentityVisible = document.querySelector('input[name="is_identity_visible"]').checked;
            let url = this.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message, is_identity_visible: isIdentityVisible })
            }).then(response => response.json())
              .then(data => {
                  document.getElementById('message').value = '';
                  // Optionally, update the chat-box with the new reply
              });
        });
    </script>
@endsection
