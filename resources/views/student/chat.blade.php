@extends('layouts.stu')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="chat-container">
        <h2 class="text-center text-white my-4">
            Chat with {{ $lecturer->name }}
            @if($lecturer->is_online)
                <span class="text-success" title="Online"><i class="fas fa-circle"></i></span>
            @else
                <span class="text-danger" title="Offline"><i class="fas fa-circle"></i></span>
            @endif
        </h2>
        <div class="card chat-box mb-4">
            <div class="card-body chat-history">
                @foreach($chats as $chat)
                    <div class="d-flex {{ $chat->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                        <div class="chat-message p-2 rounded {{ $chat->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}">
                            <strong>
                                <i class="{{ $chat->sender_id == Auth::id() ? 'fas fa-user-alt' : 'fas fa-user-tie' }}"></i>
                                {{ $chat->sender_id == Auth::id() ? 'You' : ($chat->is_anonymous ? 'Anonymous Lecturer' : $lecturer->name) }}:
                            </strong>
                            <p class="mb-0">{{ $chat->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <form id="chatForm" method="POST">
            @csrf
            <div class="input-group">
                <textarea name="message" class="form-control text-white" placeholder="Type your message here..." required></textarea>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .chat-container {
        width: 100%;
        max-width: 900px;
        background-image: linear-gradient(60deg, #29323c 0%, #485563 100%);      }
    .chat-box {
        height: 550px;
        overflow-y: auto;
        background-image: linear-gradient(60deg, #29323c 0%, #485563 100%);
        }
    .form-control{
        background-image: linear-gradient(60deg, #29323c 0%, #485563 100%);
                text-decoration: white;
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chatForm');
        const chatHistory = document.querySelector('.chat-history');

        // Scroll to the bottom of the chat container
        function scrollToBottom() {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        // Initial scroll to the bottom
        scrollToBottom();

        chatForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const url = "{{ route('chat.send', ['lecturer' => $lecturer->id]) }}";
            const formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Append the new message to the chat history
                    chatHistory.insertAdjacentHTML('beforeend', `
                        <div class="d-flex justify-content-end mb-3">
                            <div class="chat-message p-2 rounded bg-primary text-white">
                                <strong><i class="fas fa-user-alt"></i> You:</strong>
                                <p class="mb-0">${formData.get('message')}</p>
                            </div>
                        </div>
                    `);
                    scrollToBottom(); // Scroll to the bottom after appending the new message

                    // Clear the textarea
                    chatForm.reset();
                } else {
                    alert('There was an error sending your message.');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Optional: Using Laravel Echo for real-time updates
        Echo.private('chat.{{ $lecturer->id }}')
            .listen('MessageSent', (e) => {
                chatHistory.insertAdjacentHTML('beforeend', `
                    <div class="d-flex justify-content-start mb-3">
                        <div class="chat-message p-2 rounded bg-light">
                            <strong><i class="fas fa-user-tie"></i> ${e.message.sender_name}:</strong>
                            <p class="mb-0">${e.message.content}</p>
                        </div>
                    </div>
                `);
                scrollToBottom(); // Scroll to the bottom after appending the new message
            });
    });
</script>
@endsection
