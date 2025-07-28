<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/js/app.js')
    @inertiaHead
    @routes
</head>

<body>
    @inertia
    <!-- Script reCAPTCHA! -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>