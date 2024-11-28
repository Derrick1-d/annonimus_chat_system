@extends('layouts.stu')

@section('content')
<div class="container mt-1 align-items-center">
    <h2 class="mb-4 text-center text-white">Registered Lecturers</h2>

    <!-- Search Input -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <input type="text" id="searchLecturer" class="form-control" placeholder="Search for lecturers...">
        </div>
    </div>

    <!-- Lecturers List -->
    <div class="row justify-content-center" id="lecturerList">
        @foreach($lecturers as $lecturer)
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-center lecturer-card">
                <div class="card w-100">
                    <div class="card-body d-flex justify-content-between align-items-center lecturer-info">
                        <h5 class="card-title mb-0">{{ $lecturer->name }}</h5>
                        <a href="{{ route('student.chat', $lecturer->id) }}" class="btn btn-primary">
                            Chat
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Custom CSS -->
<style>
    .container {
        background-image: linear-gradient(60deg, #29323c 0%, #485563 100%);
        max-width: 900px; /* Set a specific max width for the container */
        margin-top: 50px; /* Add some top margin for better vertical alignment */
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for a more polished look */
        border-radius: 10px; /* Slightly round the corners of the card */
    }

    .card-title {
        font-weight: 600; /* Make the lecturer's name more prominent */
        font-size: 1.25rem; /* Set a font size for better visibility */
    }

    .lecturer-info {
        padding: 10px 15px; /* Add padding inside the card body */
    }

    .lecturer-info h5 {
        margin-right: 20px; /* Add some space between the name and the button */
        white-space: nowrap; /* Prevent the lecturer's name from wrapping to the next line */
        overflow: hidden; /* Hide overflow if the name is too long */
        text-overflow: ellipsis; /* Add ellipsis for long names */
    }

    .btn {
        font-size: 0.875rem; /* Adjust the button font size */
    }

    h2 {
        font-size: 1.75rem; /* Increase the size of the heading */
        color: #343a40; /* Set a darker color for the heading */
    }

    /* Ensuring the content remains aligned in smaller screens */
    @media (max-width: 576px) {
        .lecturer-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .lecturer-info a {
            margin-top: 10px;
        }
    }
</style>

<!-- JavaScript for Real-Time Search -->
<script>
    document.getElementById('searchLecturer').addEventListener('keyup', function() {
        const query = this.value;

        // Make AJAX request to search route
        fetch('{{ route("student.search") }}?query=' + query)
            .then(response => response.json())
            .then(data => {
                const lecturerList = document.getElementById('lecturerList');
                lecturerList.innerHTML = '';

                data.forEach(function(lecturer) {
                    const lecturerCard = `
                        <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-center lecturer-card">
                            <div class="card w-100">
                                <div class="card-body d-flex justify-content-between align-items-center lecturer-info">
                                    <h5 class="card-title mb-0">${lecturer.name}</h5>
                                    <a href="/student/chat/${lecturer.id}" class="btn btn-primary">
                                        Chat
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    lecturerList.insertAdjacentHTML('beforeend', lecturerCard);
                });
            });
    });
</script>
@endsection
