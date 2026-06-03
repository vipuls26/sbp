@props([
    'title' => 'SBP',
])

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title> {{ $title }}</title>

</head>

<body>
    

    @if (session('success'))
        <div class="fixed top-4 right-4 z-50 flex max-w-sm mb-4 rounded bg-green-100 p-3 text-green-700 alert">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{ $slot }}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 1500);
    </script>

    @stack('scripts')
</body>

</html>
