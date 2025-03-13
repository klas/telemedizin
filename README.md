# Telemedizin API

Eine RESTful API für ein Telemedizin-Terminsystem, entwickelt mit Laravel. Diese API ermöglicht das Verwalten von Ärzten, Fachgebieten, Terminen und Zeitslots für Telemedizin-Konsultationen.

## Funktionen

- Verwaltung von Ärzten und deren Fachgebieten
- Terminbuchung und -stornierung
- Verfügbare Zeitfwenster abrufen
- Echtzeit-Verfügbarkeitsprüfung
- E-Mail-Benachrichtigungen für Terminbestätigungen
- Suchfunktion für Ärzte und Fachgebiete

## Installation

- Repository klonen git clone https://github.com/klas/telemedizin.git
- Abhängigkeiten installieren: `docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php84-composer:latest \
  composer install --ignore-platform-reqs`
- Wenn einige Classes fehlen: `docker run --rm --interactive --tty --volume $PWD:/app composer dump-autoload`
- .env-Datei kopieren: `cp .env.example .env`
- Den Container starten: `vendor/bin/sail up -d`
- Migrationen und Seeder ausführen: `vendor/bin/sail artisan migrate:fresh --seed`

## API-Endpunkte

Die API ist nun unter `http://localhost` verfügbar.

### Zeitfenster

- `GET /api/v1/time-slots?doctor_id={id}&datum={datum}` - Verfügbare Zeitslots für einen Arzt abrufen
- `POST /api/v1/time-slots/check-availability` - Echtzeit-Verfügbarkeit eines Zeitslots prüfen

### Termine

- `GET /api/v1/appointments` - Alle Termine abrufen
- `GET /api/v1/appointments/{id}` - Einen bestimmten Termin abrufen
- `POST /api/v1/appointments` - Einen neuen Termin erstellen
- `PUT /api/v1/appointments/{id}/cancel` - Einen Termin stornieren

### Ärzte

- `GET /api/v1/doctors` - Alle Ärzte abrufen
- `GET /api/v1/doctors/{id}` - Einen bestimmten Arzt abrufen
- `GET /api/v1/doctors?search={suchbegriff}` - Ärzte nach Name oder Fachgebiet suchen

### Fachgebiete

- `GET /api/v1/specializations` - Alle Fachgebiete abrufen

## Beispielanfragen

### Verfügbare Zeitfenster abrufen

```bash
curl -X GET "http://localhost/api/v1/time-slots?doctor_id=1&datum=2025-03-20"
```

### Termin erstellen

```bash
curl -X POST http://localhost/api/v1/appointments \
  -H "Content-Type: application/json" \
  -d '{
    "doctor_id": 1,
    "patient_name": "Max Mustermann",
    "patient_email": "max@example.com",
    "date_time": "2025-03-20 14:30:00"
  }'
```

### Nach Ärzten suchen

```bash
curl -X GET "http://localhost/api/v1/doctors?search=Kardiologie"
```

## Testen

Unit-Tests können mit folgendem Befehl ausgeführt werden:

```bash
vendor/bin/sail artisan test
```

## Datenmodell

- **Doctor**: `id`, `name`, `specialization_id`
- **Specialization**: `id`, `name`
- **Appointment**: `id`, `doctor_id`, `patient_name`, `patient_email`, `date_time`, `status`
- **TimeSlot**: `id`, `doctor_id`, `start_time`, `end_time`, `is_available`

## Lizenz
Dieses Projekt ist unter der MIT-Lizenz lizenziert.
