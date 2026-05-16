<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <div class="w-64 bg-gray-700 text-white p-5">

            <h2 class="text-xl font-bold mb-6">Admin Panel</h2>

            <ul class="space-y-3">

                <li>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>

                @if(in_array(auth()->user()->role, ['admin', 'manager', 'staff']))
                <li>
                    <a href="{{ route('students.index') }}">Manage Students</a>
                </li>

                <li>
                    <a href="{{ route('courses.index') }}">Manage Courses</a>
                </li>
                @endif

                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('users.index') }}">Manage Users</a>
                </li>
                @endif

            </ul>

        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 p-6 bg-gray-800">

            @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- PAGE CONTENT --}}
            @yield('content')

        </div>

    </div>

    <script>
        const passwordInput = document.querySelector('#password');
        const toggleButton = document.querySelector('#togglePassword');
        const eyeIcon = document.querySelector('#eyeIcon');

        if (passwordInput && toggleButton && eyeIcon) {
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('text-blue-500', type === 'text');
            });
        }
    </script>

</body>

</html>