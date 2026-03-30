<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ANS Realty') }}</title>
    
    <!-- Push Notification Script -->
    <script src="{{ asset('js/push-notifications.js') }}" defer></script>
    
    <script>
        // Auto-request notification permission on first visit
        if ('Notification' in window && Notification.permission === 'default') {
            setTimeout(() => {
                Notification.requestPermission();
            }, 5000); // Wait 5 seconds before asking
        }
    </script>
</head>
<body>
    @yield('content')
    
    @stack('scripts')
</body>
</html>
