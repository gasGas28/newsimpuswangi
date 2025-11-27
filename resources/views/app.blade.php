<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    @inertiaHead
    @routes
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    @inertia
    <!-- Script reCAPTCHA! -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>