<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Terminbestätigung</title>
</head>
<body>
    <h1>Terminbestätigung</h1>
    
    <p>Sehr geehrte(r) {{ $appointment->patient_name }},</p>
    
    <p>Ihr Telemedizin-Termin wurde bestätigt. Hier sind die Details:</p>
    
    <ul>
        <li>Datum und Uhrzeit: {{ $appointment->date_time->format('d.m.Y H:i') }}</li>
        <li>Arzt: {{ $appointment->doctor->name }}</li>
        <li>Fachbereich: {{ $appointment->doctor->specialization->name }}</li>
    </ul>
    
    <p>Um Ihren Termin zu stornieren, besuchen Sie bitte unsere Website oder rufen Sie unser Servicecenter an.</p>
    
    <p>Mit freundlichen Grüßen,<br>
    Ihr Telemedizin-Team</p>
</body>
</html>
