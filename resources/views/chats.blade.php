<!-- resources/views/chats.blade.php -->

@foreach($chats as $chat)
    <div class="chat-message">
        <strong>{{ $chat->sender->role === 'student' ? 'Anonymous' : $chat->sender->name }}:</strong>
        <p>{{ $chat->message }}</p>
    </div>
@endforeach
