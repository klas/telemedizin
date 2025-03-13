# Telemedizin API

Eine RESTful API für ein Telemedizin-Terminsystem, entwickelt mit Laravel. Diese API ermöglicht das Verwalten von Ärzten, Fachgebieten, Terminen und Zeitslots für Telemedizin-Konsultationen.

## Funktionen

- Verwaltung von Ärzten und deren Fachgebieten
- Terminbuchung und -stornierung
- Verfügbare Zeitslots abrufen
- Echtzeit-Verfügbarkeitsprüfung
- E-Mail-Benachrichtigungen für Terminbestätigungen
- Suchfunktion für Ärzte
- Alle Benutzeroberflächen-Texte auf Deutsch

## Installation

- Clone the Repo git clone https://github.com/klas/telemedizin.git
- Install dependencies: `docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php84-composer:latest \
  composer install --ignore-platform-reqs`
- If some Classes are missing: `docker run --rm --interactive --tty --volume $PWD:/app composer dump-autoload`
- copy .env-file: `cp .env.example .env`
- Start the Container: `vendor/bin/sail up -d`
- Run migrations und seeders: `vendor/bin/sail artisan migrate:fresh --seed`

## Testing
* Run tests `vendor/bin/sail artisan test`

Die API ist nun unter `http://localhost:80` verfügbar.

## API-Endpunkte

### Ärzte

- `GET /api/v1/doctors` - Alle Ärzte abrufen
- `GET /api/v1/doctors/{id}` - Einen bestimmten Arzt abrufen
- `GET /api/v1/doctors/search?suche={suchbegriff}` - Ärzte nach Name oder Fachgebiet suchen

### Fachgebiete

- `GET /api/v1/specializations` - Alle Fachgebiete abrufen

### Zeitslots

- `GET /api/v1/time-slots?doctor_id={id}&datum={datum}` - Verfügbare Zeitslots für einen Arzt abrufen
- `POST /api/v1/time-slots/check-availability` - Echtzeit-Verfügbarkeit eines Zeitslots prüfen

### Termine

- `GET /api/v1/appointments` - Alle Termine abrufen
- `GET /api/v1/appointments/{id}` - Einen bestimmten Termin abrufen
- `POST /api/v1/appointments` - Einen neuen Termin erstellen
- `PUT /api/v1/appointments/{id}/cancel` - Einen Termin stornieren

## Beispielanfragen

### Termin erstellen

```bash
curl -X POST http://localhost:8000/api/v1/appointments \
  -H "Content-Type: application/json" \
  -d '{
    "doctor_id": 1,
    "patient_name": "Max Mustermann",
    "patient_email": "max@example.com",
    "date_time": "2025-03-20 14:30:00"
  }'
```

### Verfügbare Zeitslots abrufen

```bash
curl -X GET "http://localhost:8000/api/v1/time-slots?doctor_id=1&datum=2025-03-20"
```

### Nach Ärzten suchen

```bash
curl -X GET "http://localhost:8000/api/v1/doctors/search?suche=Kardiologie"
```

## Testen

Unit-Tests können mit folgendem Befehl ausgeführt werden:

```bash
php artisan test
```

## Datenmodell

- **Doctor**: `id`, `name`, `specialization_id`
- **Specialization**: `id`, `name`
- **Appointment**: `id`, `doctor_id`, `patient_name`, `patient_email`, `date_time`, `status`
- **TimeSlot**: `id`, `doctor_id`, `start_time`, `end_time`, `is_available`


## Fehlerbehebung

### API gibt 500-Fehler zurück
- Überprüfen Sie die Laravel-Logs unter `storage/logs/laravel.log`
- Stellen Sie sicher, dass die Datenbankverbindung korrekt konfiguriert ist
- Überprüfen Sie, ob alle Migrationen erfolgreich ausgeführt wurden

### E-Mails werden nicht gesendet
- Überprüfen Sie Ihre Mail-Konfiguration in der `.env`-Datei
- Für die lokale Entwicklung empfehlen wir [Mailhog](https://github.com/mailhog/MailHog)

## Lizenz
Dieses Projekt ist unter der MIT-Lizenz lizenziert.
