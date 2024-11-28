<!-- resources/views/lecturer/settings.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Identity Settings</h3>
        <form id="settings-form" method="POST" action="{{ route('chat.toggleIdentity') }}">
            @csrf
            <div>
                <label>
                    <input type="checkbox" name="is_identity_visible" {{ $setting->is_identity_visible ? 'checked' : '' }}>
                    Show my identity by default
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>

    <script>
        document.getElementById('settings-form').addEventListener('submit', function(e) {
            e.preventDefault();
            let isIdentityVisible = document.querySelector('input[name="is_identity_visible"]').checked;
            let url = this.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_identity_visible: isIdentityVisible })
            }).then(response => response.json())
              .then(data => {
                  alert('Settings updated!');
              });
        });
    </script>
@endsection
