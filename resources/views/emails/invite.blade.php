<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Email</title>
</head>

<body>
    <h1>Welcome, {{ $name }}!</h1>
    <p>You have been invited to join our platform.</p>
    <p>Click the link below to register:</p>
    <a href="{{ $registrationLink }}">Register Now</a>
</body>

</html>
