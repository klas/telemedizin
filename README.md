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

## Technische Anforderungen

- PHP 8.1 oder höher
- Composer
- MySQL oder eine andere Laravel-kompatible Datenbank
- Laravel 10.x

## Installation

### 1. Repository klonen

```bash
git clone https://github.com/username/telemedizin-api.git
cd telemedizin-api
```

### 2. Abhängigkeiten installieren

```bash
composer install
```

### 3. Umgebungsvariablen konfigurieren

```bash
cp .env.example .env
```

Bearbeiten Sie die `.env`-Datei und konfigurieren Sie Ihre Datenbankverbindung:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=telemedizin
DB_USERNAME=root
DB_PASSWORD=
```

Für E-Mail-Funktionalität, konfigurieren Sie auch die Mail-Einstellungen:

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@telemedizin.de"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Anwendungsschlüssel generieren

```bash
php artisan key:generate
```

### 5. Datenbank-Migrationen ausführen

```bash
php artisan migrate
```

### 6. (Optional) Seed-Daten einfügen

```bash
php artisan db:seed
```

## Starten der Anwendung

```bash
php artisan serve
```

Die API ist nun unter `http://localhost:8000` verfügbar.

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

## Datenbank-Seeding

Um Testdaten zu erstellen, können Sie folgende Befehle ausführen:

```bash
php artisan db:seed --class=SpecializationSeeder
php artisan db:seed --class=DoctorSeeder
php artisan db:seed --class=TimeSlotSeeder
```

## Datenmodell

- **Doctor**: `id`, `name`, `specialization_id`
- **Specialization**: `id`, `name`
- **Appointment**: `id`, `doctor_id`, `patient_name`, `patient_email`, `date_time`, `status`
- **TimeSlot**: `id`, `doctor_id`, `start_time`, `end_time`, `is_available`

## Benutzerdefinierte Seeder

Sie können Seeders für Ihre Testdaten erstellen:

```bash
php artisan make:seeder SpecializationSeeder
php artisan make:seeder DoctorSeeder
php artisan make:seeder TimeSlotSeeder
```

Beispielinhalt für `database/seeders/SpecializationSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run()
    {
        $specializations = [
            'Allgemeinmedizin',
            'Kardiologie',
            'Dermatologie',
            'Neurologie',
            'Orthopädie',
            'Pädiatrie',
            'Psychiatrie',
            'Urologie',
            'Gynäkologie',
            'Onkologie'
        ];

        foreach ($specializations as $name) {
            Specialization::create(['name' => $name]);
        }
    }
}
```

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
