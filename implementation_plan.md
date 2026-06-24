# Implementation Plan - Onyendozi Connect Laravel Backend

This plan outlines the steps required to build the Laravel aspect of the voter mobilization & campaign management platform.

## User Review Required

> [!IMPORTANT]
> The database schema covers 7 LGAs (Anaocha, Awka North, Awka South, Dunukofia, Idemili North, Idemili South, Njikoka). We will seed geographic data for these LGAs and their wards and polling units.

> [!WARNING]
> We will install and configure standard packages: `spatie/laravel-permission`, `spatie/laravel-activitylog`, `laravel/sanctum`, `barryvdh/laravel-dompdf`, `maatwebsite/laravel-excel`.

## Proposed Changes

### Database & Models

#### [NEW] [Migration Files & Models]
Create models and migrations for the following entities:
- `Lga`: id, name, state
- `Ward`: id, name, lga_id
- `PollingUnit`: id, name, code, ward_id, lat, lng, registered_voters
- `Event`: id, title, description, date, venue, lga_id, ward_id, created_by
- `EventAttendance`: id, event_id, user_id, checked_in_at, method (qr/manual)
- `Task`: id, title, description, deadline, status, assigned_to_role, lga_id, ward_id, assigned_user_id, created_by, verified_by
- `TaskCompletion`: id, task_id, user_id, notes, completed_at, verified_at
- `DoorKnock`: id, user_id, lat, lng, address_description, voter_name, outcome, notes, visited_at
- `AgentLocation`: id, user_id, lat, lng, accuracy, battery_level, recorded_at
- `Incident`: id, user_id, type, description, urgency, lat, lng, status, reported_at
- `IncidentMedia`: id, incident_id, path, type (photo/video)
- `Result`: id, user_id, polling_unit_id, ec8a_image_path, submitted_at, verified_at, verified_by
- `ResultEntry`: id, result_id, party, votes
- `VoiceReport`: id, user_id, audio_path, transcript, duration, created_at
- `Material`: id, type, quantity, lga_id, ward_id, polling_unit_id, distributed_at, confirmed_by
- `Message`: id, type (sms/whatsapp), body, audience_type, audience_id, created_by, scheduled_at, sent_at
- `News`: id, title, body, image_path, category, is_breaking, published_at
- `MediaLibrary`: id, name, category, path, mime_type, size, uploaded_by
- `LeaderboardPoint`: id, user_id, points, source_type, source_id, earned_at

#### [MODIFY] [User.php](file:///c:/Users/User/Herd/onyedozieadmin/app/Models/User.php)
Add fields: `phone`, `role_id` (or use spatie roles), `lga_id`, `ward_id`, `polling_unit_id`, `status`, `device_token`, `last_seen_at`.

### Seeders
- Seed Anambra Central LGAs, Wards, and Polling Units.

### Authentication & API Layer
- Configure Sanctum and Spatie Permissions.
- Create API routes in `routes/api.php` for:
  - Auth: login, register, logout, OTP verification
  - Members: profile, list, nearby
  - Events: list, check-in
  - Tasks: list, complete
  - Field Operations: location ping, door knocks
  - Results & Incidents: upload EC8A, report incident, voice reports
  - Content: news, media, leaderboard, notifications

### Admin Livewire Dashboard
- Build core Livewire components and blade templates for:
  - Dashboard Home (with key stats and maps)
  - User management (approval queue, roles)
  - Event & Task management
  - Field Operations map tracking
  - Election Day command center (EC8A uploads and status feed)

## Verification Plan

### Automated Tests
- Run `php artisan test` or `vendor/bin/pest` to verify the api endpoints and models.

### Manual Verification
- Verify that livewire components render successfully.
