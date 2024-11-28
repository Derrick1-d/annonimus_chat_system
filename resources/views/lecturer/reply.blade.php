@extends('layouts.temp')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center" style="background-color: #ffffff;">
            <h1>Chat with {{ $student->name }}</h1>
        </div>
        <div class="card-body chat-history">
            @foreach($chats as $chat)
            <div class="d-flex {{ $chat->sender_id == $lecturer->id ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                <div class="p-2">
                    <i class="{{ $chat->sender_id == $lecturer->id ? 'fas fa-user-circle' : 'fas fa-user' }}"></i>
                    <strong>
                        {{ $chat->sender_id == $lecturer->id ? 'You' : ($chat->is_anonymous ? 'Anonymous' : $student->name) }}:
                    </strong>
                </div>
                <div class="p-2 bg-light rounded">
                    <p class="mb-0">{{ $chat->message }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer">
            <form id="replyForm" method="POST">
                @csrf
                <div class="input-group">
                    <textarea name="message" class="form-control" rows="1" placeholder="Type your reply here..." required></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="identityVisibility" name="is_identity_visible"
                           {{ $lecturer->settings && $lecturer->settings->is_identity_visible ? 'checked' : '' }}>
                    <label class="form-check-label" for="identityVisibility">Reveal Identity</label>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Additional Styles -->
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .chat-history {
        height: 500px;
        overflow-y: auto;
        padding: 10px;
    }

    .card-body, .card-header, .card-footer {
        background-color: #ffffff;
    }

    .input-group textarea {
        resize: none;
    }

    .justify-content-end .fas.fa-user-circle {
        color: #007bff;
    }

    .justify-content-start .fas.fa-user {
        color: #6c757d;
    }
</style>

<!-- AJAX Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('replyForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);

            fetch("{{ route('chat.reply', ['chat' => $chat->id]) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.message === 'Reply sent successfully!') {
                    // Append the new message to the chat history
                    let chatHistory = document.querySelector('.chat-history');
                    chatHistory.insertAdjacentHTML('beforeend', `
                        <div class="d-flex justify-content-end mb-2">
                            <div class="p-2">
                                <i class="fas fa-user-circle"></i>
                                <strong>You:</strong>
                            </div>
                            <div class="p-2 bg-light rounded">
                                <p class="mb-0">${formData.get('message')}</p>
                            </div>
                        </div>
                    `);
                    chatHistory.scrollTop = chatHistory.scrollHeight; // Scroll to the bottom

                    // Clear the textarea
                    this.reset();
                } else {
                    alert('There was an error sending your reply.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection
