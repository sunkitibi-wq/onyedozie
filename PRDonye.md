# Product Requirements Document (PRD)
## Onyendozi Connect — Voter Mobilization & Campaign Management Platform
**Version:** 1.0  
**Date:** June 2026  
**Campaign:** Hon. Dozie Nwankwo — Anambra Central Senatorial District 2027  
**Stack:** Laravel 13 + Livewire (Backend & Admin Dashboard) · Flutter (iOS & Android)

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Goals & Success Metrics](#2-goals--success-metrics)
3. [System Architecture](#3-system-architecture)
4. [User Roles & Permissions](#4-user-roles--permissions)
5. [Phase 1 — Foundation & Infrastructure](#phase-1--foundation--infrastructure)
6. [Phase 2 — Authentication & User Management](#phase-2--authentication--user-management)
7. [Phase 3 — Core Campaign Management (Admin Dashboard)](#phase-3--core-campaign-management-admin-dashboard)
8. [Phase 4 — Field Operations Module](#phase-4--field-operations-module)
9. [Phase 5 — Flutter Mobile App (iOS & Android)](#phase-5--flutter-mobile-app-ios--android)
10. [Phase 6 — Communications & Media](#phase-6--communications--media)
11. [Phase 7 — Election Day Command Centre](#phase-7--election-day-command-centre)
12. [Phase 8 — Analytics, Reporting & AI Insights](#phase-8--analytics-reporting--ai-insights)
13. [Phase 9 — Security, Hardening & Compliance](#phase-9--security-hardening--compliance)
14. [Phase 10 — Testing, QA & Deployment](#phase-10--testing-qa--deployment)
15. [Database Schema Overview](#15-database-schema-overview)
16. [API Contract Overview](#16-api-contract-overview)
17. [Non-Functional Requirements](#17-non-functional-requirements)
18. [Milestones & Timeline](#18-milestones--timeline)

---

## 1. Executive Summary

Onyendozi Connect is a full-stack political campaign management platform for the 2027 Anambra Central Senatorial race. It replaces a fragile PWA prototype with a production-grade system comprising:

- **Laravel 13 + Livewire** backend serving a real-time admin dashboard and a REST/JSON API
- **Flutter** cross-platform mobile app (iOS + Android) consumed by field agents, volunteers, ward and LGA coordinators
- Coverage of 7 LGAs: Anaocha, Awka North, Awka South, Dunukofia, Idemili North, Idemili South, Njikoka

---

## 2. Goals & Success Metrics

| Goal | KPI |
|------|-----|
| Mobilize 10,000+ registered voters | Member count in dashboard |
| 100% Polling Unit coverage across 7 LGAs | PU registration completion rate |
| Real-time field agent visibility | GPS ping latency < 5 seconds |
| Election Day results upload within 10 min of vote count | EC8A upload timestamp |
| Zero data loss | Daily automated cloud backup success rate = 100% |
| App store availability | Live on Google Play & Apple App Store before Q1 2027 |

---

## 3. System Architecture

```
┌─────────────────────────────────────────────────────┐
│                  ADMIN LAYER                        │
│   Laravel 13 (PHP 8.3) + Livewire 3                 │
│   Real-time dashboard, admin CRUD, reports, media   │
│   Hosted: Ubuntu VPS / Laravel Forge / Railway      │
└───────────────────┬─────────────────────────────────┘
                    │ REST API (JSON, Sanctum auth)
┌───────────────────▼─────────────────────────────────┐
│              FLUTTER MOBILE APP                     │
│   iOS + Android · Dart 3 · Flutter 3.x              │
│   Offline-capable · FCM push notifications          │
└─────────────────────────────────────────────────────┘
                    │
┌───────────────────▼─────────────────────────────────┐
│             DATA & SERVICES LAYER                   │
│   MySQL 8 (primary DB)                              │
│   Redis (queues, cache, real-time pub/sub)          │
│   Laravel Echo + Pusher/Soketi (WebSocket events)   │
│   Firebase Cloud Messaging (push notifications)     │
│   AWS S3 / Cloudflare R2 (media & document storage) │
│   Twilio / Africa's Talking (SMS)                   │
└─────────────────────────────────────────────────────┘
```

---

## 4. User Roles & Permissions

| Role | Scope | Dashboard Access | Mobile Access |
|------|-------|-----------------|---------------|
| Super Admin | Full system | Full | No |
| Admin | Campaign-wide | Most modules | No |
| Candidate Dashboard | Read-only exec view | Candidate view | Read-only |
| LGA Coordinator | One LGA | LGA view | Yes |
| Ward Coordinator | One Ward | Ward view | Yes |
| Polling Unit Coordinator | One PU | PU view | Yes |
| Volunteer | Own activity | None | Yes |

---

## Phase 1 — Foundation & Infrastructure

**Goal:** Set up the project skeleton, development environment, CI/CD, and core configuration before writing any feature code.

### 1.1 Laravel 13 Project Scaffold

- Install Laravel 13 via Composer
- Configure `.env` for MySQL, Redis, mail, S3
- Set up Pest PHP for testing
- Install and configure:
  - `laravel/sanctum` — API token authentication
  - `livewire/livewire` v3 — reactive admin UI
  - `spatie/laravel-permission` — role & permission management
  - `spatie/laravel-media-library` — media uploads
  - `spatie/laravel-activitylog` — audit logging
  - `laravel/horizon` — queue monitoring
  - `barryvdh/laravel-dompdf` — PDF report generation
  - `maatwebsite/laravel-excel` — Excel/CSV exports
  - `pusher/pusher-php-server` + Laravel Echo — real-time events

### 1.2 Flutter Project Scaffold

- Create Flutter project targeting iOS 14+ and Android 7.0+ (API 24+)
- Configure Flutter flavors: `dev`, `staging`, `production`
- Add core packages:
  - `dio` — HTTP client
  - `flutter_riverpod` or `bloc` — state management
  - `hive` + `hive_flutter` — local offline storage
  - `google_maps_flutter` — mapping
  - `geolocator` — GPS location
  - `firebase_messaging` — push notifications
  - `flutter_local_notifications` — local notification display
  - `image_picker` — camera/photo upload
  - `record` + `just_audio` — voice notes and audio playback
  - `flutter_pdfview` — PDF report viewer
  - `connectivity_plus` — offline detection

### 1.3 Infrastructure & DevOps

- Set up Git repository (GitHub/GitLab) with protected `main` and `develop` branches
- Configure GitHub Actions CI/CD pipelines:
  - PHP linting (Laravel Pint), Pest tests on pull request
  - Flutter lint and unit tests on pull request
  - Auto-deploy `develop` → staging; `main` → production
- Provision VPS/cloud server:
  - Ubuntu 22.04 LTS
  - Nginx + PHP-FPM 8.3
  - MySQL 8, Redis 7
  - SSL/TLS via Let's Encrypt
- Set up Laravel Forge or Ploi for server management
- Configure Soketi (self-hosted Pusher) or Pusher for WebSockets
- Set up object storage (AWS S3 or Cloudflare R2) with a CDN
- Configure daily database backups to offsite storage

### 1.4 Geographic Data Seed

- Source INEC ward and polling unit data for all 7 LGAs in Anambra Central
- Create database seeders for:
  - States → LGAs → Wards → Polling Units hierarchy
  - Approximate GPS coordinates per polling unit
- Validate completeness against INEC official PU register

**Deliverables:** Running Laravel app at staging URL, Flutter app launching on emulator, CI passing, DB seeded with LGA/Ward/PU data.

---

## Phase 2 — Authentication & User Management

**Goal:** Secure multi-role authentication for both the web dashboard and the Flutter mobile API.

### 2.1 Web Authentication (Laravel + Livewire)

- Laravel Breeze or custom Livewire auth screens
- Login via phone number + password (no email required)
- Password reset via SMS OTP (Africa's Talking / Twilio)
- "Remember Me" session persistence
- Rate limiting: max 5 failed login attempts → 15-minute lockout
- Super Admin can force-logout any user

### 2.2 API Authentication (for Flutter)

- Laravel Sanctum token-based auth
- `POST /api/auth/login` → returns Bearer token
- `POST /api/auth/logout` → revokes token
- `POST /api/auth/refresh` → rotate token
- Token expiry: 30 days; refresh window: 7 days
- Device fingerprinting: store `device_id`, `device_name`, `platform` on token

### 2.3 Registration & Approval Workflow

- Self-registration form (web & mobile): Full Name, Phone, Password, Role, LGA, Ward, PU
- Volunteer accounts activate immediately
- Coordinator accounts go into `pending_approval` state
- Super Admin receives in-app + email notification of pending accounts
- Approval/rejection with optional note → applicant notified via SMS
- Admin can bulk-approve pending accounts

### 2.4 User Management (Admin Dashboard — Livewire)

- Searchable, filterable member list (by role, LGA, Ward, PU, status)
- View member profile: activity log, events attended, tasks completed, recruitment count
- Edit member details, role, geographic assignment
- Deactivate / reactivate accounts
- Export member list to CSV/Excel
- Pagination with infinite scroll on mobile

### 2.5 Profile Management (Flutter)

- View and edit own profile
- Change password
- Upload profile photo (compressed before upload)
- View own activity history and task list

**Deliverables:** Web login/register working; Flutter app authenticates and receives token; role-based middleware active; admin approval queue live.

---

## Phase 3 — Core Campaign Management (Admin Dashboard)

**Goal:** Build the central Livewire admin dashboard that campaign administrators use daily.

### 3.1 Dashboard Home (Livewire)

- Real-time summary cards:
  - Total members (with today's growth delta)
  - Members by role (Volunteer / PU / Ward / LGA coordinator)
  - Active agents in the field (live GPS pings in last 30 min)
  - Upcoming events (next 3)
  - Pending approvals count
  - Tasks overdue count
- Activity feed: last 20 system events (new registrations, completed tasks, uploads)
- Map widget: cluster markers of all members by LGA
- Charts: member growth over time, registrations by LGA

### 3.2 Member Registration (Admin-side)

- Manual member registration form (admin adding a supporter directly)
- Bulk import from CSV template
- Duplicate detection: flag same phone number
- Auto-assign to geographic hierarchy on creation

### 3.3 Events Management

- Create campaign events: title, description, date/time, venue, LGA/Ward scope
- Attach media (poster/flyer) to events
- Push notification to all relevant members on event creation
- Track attendance: members check in via mobile app (QR code or manual)
- Post-event summary: attendance count vs invites

### 3.4 Tasks Management

- Create tasks: title, description, deadline, assigned to (role/individual/LGA/ward)
- Task status: `pending` → `in_progress` → `completed` → `verified`
- Admin can verify and close tasks
- Overdue task alerts (email digest to admins)
- Tasks visible to assignees on Flutter app

### 3.5 Leaderboard

- Ranks volunteers by: members recruited, events attended, tasks completed, doors knocked
- Filter by LGA, Ward, time period
- Real-time updates via Livewire polling
- Trophy badges: Top Recruiter, Most Active, Door-to-Door Champion

### 3.6 Candidate Dashboard

- Simplified read-only view for the candidate:
  - Total member count, growth trend
  - Coverage map (PUs with at least 1 registered member)
  - Top performing LGAs
  - Upcoming events
- Shareable public link (token-protected, read-only)

**Deliverables:** Full Livewire admin dashboard with live stats, events, tasks, leaderboard, candidate view.

---

## Phase 4 — Field Operations Module

**Goal:** Enable real-time field coordination, canvassing tracking, and GPS-based agent monitoring.

### 4.1 Live Agent Tracker

- Flutter app sends GPS ping to `POST /api/location/ping` every 60 seconds when tracker is active
- Laravel stores: user_id, latitude, longitude, timestamp, battery_level, accuracy
- Admin dashboard (Livewire) shows live map with agent markers
- Marker color indicates: active (green), idle >15 min (yellow), offline >30 min (grey)
- Click marker to see agent name, last ping time, battery, role
- Historical trail view: replay an agent's route for a given day

### 4.2 Door-to-Door Canvassing Tracker

- Flutter: agent logs a visit — address/description, voter name (optional), outcome (supportive/neutral/opposed/not home), notes, optional photo
- GPS auto-captures location on visit log
- Dashboard: table of all visits with filters; map showing visited locations
- Canvassing summary: visits per agent, coverage heatmap, outcome breakdown
- Daily targets: admin sets visit targets per agent; progress shown in app

### 4.3 Nearby Members Finder

- Flutter: `GET /api/members/nearby?lat=&lng=&radius=5000`
- Returns members within radius (default 5 km), sorted by distance
- Display on map or list in app
- Useful for agents to find local contacts when entering a new area

### 4.4 Map View (Admin)

- Leaflet.js or Google Maps embedded in Livewire component
- Layers: Members, Events, Agent Tracks, Canvassing Coverage
- Toggle layers independently
- Export map snapshot as PNG for reports

**Deliverables:** Live GPS tracking on dashboard, door-to-door canvassing logged from mobile, nearby members API, full map admin view.

---

## Phase 5 — Flutter Mobile App (iOS & Android)

**Goal:** Build the complete Flutter mobile experience — the primary tool for all field users.

### 5.1 App Structure & Navigation

- Bottom navigation bar: Home, Members, Events, Messages, More
- "More" screen: expandable grid of all modules
- Deep linking support (push notification → specific screen)
- App theming: campaign brand color `#006B3F`, dark/light mode support

### 5.2 Onboarding Flow

- Splash screen with campaign branding
- Onboarding carousel (3 screens): what the app does, how to register, how to earn points
- Sign In / Create Account screens
- OTP verification of phone number on first login
- Permission requests: location (when in use / always), notifications, camera, microphone, storage

### 5.3 Home Screen

- Welcome card with member name and role
- Today's task summary (count + quick access)
- Upcoming events (next event card)
- My stats: members recruited, doors knocked, tasks done
- Quick-action buttons: Log Visit, Report Incident, Check In to Event

### 5.4 Members Module (Mobile)

- Search and browse members in own jurisdiction (ward/LGA depending on role)
- View member profile card
- Tap to call or WhatsApp a member
- Coordinators: approve/reject pending registrations in own area

### 5.5 Events Module (Mobile)

- List of upcoming and past events scoped to user's LGA/Ward
- Event detail: date, time, venue, description, poster image
- Check-in button (GPS-verified or manual)
- Calendar view option

### 5.6 Offline Mode

- All API responses cached in Hive local DB
- Users can view members, tasks, and events while offline
- Actions taken offline queued in local storage and synced on reconnect
- "Offline mode" indicator banner when connectivity is lost
- Conflict resolution: last-write-wins for non-critical data; flag conflicts in tasks

### 5.7 Push Notifications (FCM)

- Notification types:
  - New task assigned
  - Event reminder (24h and 1h before)
  - Account approved/rejected
  - Election Day alert (broadcast from admin)
  - Incident escalation
- Deep link from notification to relevant screen
- In-app notification bell with unread count badge

### 5.8 App Store Deployment

- iOS: Xcode build, TestFlight beta, App Store submission
- Android: Signed APK/AAB, Google Play Internal Testing → Production
- App icons, splash screens, store screenshots in both platforms' required formats
- Privacy policy page (required for both stores)

**Deliverables:** Flutter app installed on TestFlight and Google Play Internal Track; all modules accessible; offline mode confirmed working.

---

## Phase 6 — Communications & Media

**Goal:** Equip campaign staff with broadcast, media, and content scheduling tools.

### 6.1 WhatsApp Broadcast

- Admin composes message with optional media attachment
- Select target audience: All Members / By LGA / By Role / Custom list
- Integration: WhatsApp Business API (official) or Baileys (unofficial, self-hosted)
- Delivery queue via Laravel jobs
- Delivery status report: sent, delivered, failed counts

### 6.2 Bulk SMS

- Compose SMS (160-char counter with multi-part warning)
- Audience selection same as WhatsApp
- Provider: Africa's Talking (primary) with Termii as fallback
- Schedule future sends
- SMS credit balance visible in admin
- Delivery report per recipient

### 6.3 Social Media Scheduler

- Compose post: text + up to 4 images
- Select platforms: Facebook, X (Twitter), Instagram
- Schedule date/time or post immediately
- Integration via platform APIs (Meta Graph API, X API v2)
- Post history with engagement metrics (likes, shares, reach) pulled from APIs
- Content calendar view

### 6.4 Campaign Jingles Player (Mobile)

- Admin uploads audio files (MP3) to media library
- Flutter: dedicated Jingles tab in Media section
- Playback with play/pause, seek, next/previous
- Offline download option (for areas with poor connectivity)
- Background audio playback on iOS and Android

### 6.5 Media Library (Admin)

- Upload: images (JPG/PNG/WebP), audio (MP3), video (MP4), documents (PDF)
- Automatic image optimization via `spatie/laravel-image` or Intervention Image
- Organize by category: Jingles, Flyers, Posters, Documents, Videos
- CDN-backed public URLs
- Flutter: browse and download media assets

### 6.6 Weekly Summary Email

- Auto-generated every Monday at 07:00 (WAT)
- Recipients: Super Admin, Admin, LGA Coordinators
- Content: new members this week, events held, tasks completed, top 5 volunteers, agent tracking summary
- HTML email template with campaign branding
- Delivered via Mailgun / Resend / SES

**Deliverables:** WhatsApp and SMS broadcast working; social media scheduler integrated; jingles player in Flutter app; weekly email delivering.

---

## Phase 7 — Election Day Command Centre

**Goal:** Provide a real-time war room for election day — results capture, incident management, and live PU reporting.

### 7.1 Election Day HQ (Admin Dashboard)

- Dedicated full-screen "Election Day" mode in admin
- Live scoreboard: results received from X of Y polling units
- PU status map: colour-coded by status (no agent, agent deployed, voting ongoing, results submitted, incident reported)
- Real-time feed of result uploads and incident reports
- Announcement broadcast panel (push to all agents)

### 7.2 EC8A Results Upload (Mobile)

- Flutter screen: agent selects their assigned PU (pre-set at login)
- Camera capture of EC8A form (results sheet)
- Manual entry fields: Party name + votes received
- Validation: total votes ≤ registered voters for PU
- `POST /api/results/upload` with image + data payload
- Admin dashboard shows upload instantly (WebSocket event)
- Agent cannot re-submit after admin marks as verified

### 7.3 Live PU Reporting

- Agent submits status updates throughout the day:
  - Accreditation started / ended
  - Voting started / ended
  - Counting started
  - Results announced
- Each update timestamped and shown on HQ dashboard
- Admin can annotate status updates

### 7.4 Incident Reporting (Mobile + Admin)

- Flutter: "Report Incident" button prominent on home screen on Election Day
- Incident form: type (violence, ballot stuffing, INEC official misconduct, result falsification, other), description, photo/video evidence, GPS location
- Urgency level: low / medium / high / critical
- `POST /api/incidents` — immediately triggers push to all admins
- Admin dashboard: incident list sortable by severity and time
- Admin can escalate, add notes, mark as resolved
- PDF incident log exportable at end of day

### 7.5 Voice Reports (Mobile)

- Agent records a voice note (up to 2 minutes)
- Compressed and uploaded to S3
- Transcription (optional): OpenAI Whisper API or on-device speech recognition
- Playable from admin dashboard alongside other incident data

### 7.6 Materials Distribution Tracker

- Admin logs material batches: type (T-shirts, caps, flyers), quantity, assigned to (LGA/Ward/PU)
- Distribution confirmed by coordinator on mobile (signature capture optional)
- Real-time inventory: allocated vs confirmed received vs remaining

**Deliverables:** Election Day HQ dashboard live; EC8A upload working end-to-end; incidents created on mobile appearing on dashboard in <5 seconds; voice reports uploadable and playable.

---

## Phase 8 — Analytics, Reporting & AI Insights

**Goal:** Give campaign leadership data-driven visibility into campaign health and momentum.

### 8.1 Analytics Dashboard (Livewire)

- Member growth chart: daily/weekly/monthly (Chart.js via Livewire)
- Geographic heatmap: member density by LGA → Ward → PU
- Recruitment funnel: registered → verified → active (visited an event or logged a door)
- Canvassing coverage: % of PUs with at least one door knock
- Agent productivity: average doors per day per active agent
- Filter all charts by LGA, date range

### 8.2 PDF Reports (Admin)

- On-demand PDF generation via DomPDF:
  - Member roster by LGA/Ward (with contact details)
  - Events attendance report
  - Task completion report
  - Election Day results summary
  - Incident log
- All reports include campaign logo header and page numbers
- Reports downloadable from dashboard and emailed on request

### 8.3 CSV / Excel Exports

- Any data table in the dashboard exportable via Maatwebsite Excel
- Export jobs handled by queues for large datasets (>1,000 rows)
- Download notification when ready

### 8.4 Leaderboard & Gamification

- Points system:
  - 10 pts: recruit a new member
  - 5 pts: attend an event
  - 3 pts: complete a task
  - 2 pts: log a door knock
  - 1 pt: submit a daily report
- Weekly and all-time leaderboards
- Badges awarded automatically by Laravel events/listeners
- Share badge to WhatsApp from Flutter app

### 8.5 News Updates Feed

- Admin posts campaign news items (title, body, image, category)
- Flutter: News tab shows paginated feed
- Option to pull in external RSS feeds (e.g., Vanguard, Punch Anambra news)
- "Breaking" flag on urgent items → push notification

**Deliverables:** Full analytics dashboard with charts; PDF exports working; leaderboard with points live; news feed in Flutter app.

---

## Phase 9 — Security, Hardening & Compliance

**Goal:** Ensure the platform is secure, private, and resilient before going to production.

### 9.1 Authentication Security

- HTTPS enforced on all routes (HSTS header)
- Laravel Sanctum token scopes (read-only tokens for candidate view)
- OTP verification on registration (prevent fake accounts)
- Account lockout after 5 failed API login attempts
- Admin can view all active sessions and revoke specific device tokens
- Passwords: bcrypt hashed, minimum 8 chars, complexity enforced

### 9.2 Authorisation

- `spatie/laravel-permission` gates on every controller method
- Scope enforcement: LGA Coordinators can only read/write their LGA; Ward Coordinators their Ward
- Policy classes for all Eloquent models
- API returns 403 with standard error body on policy failure

### 9.3 Data Security

- All sensitive columns encrypted at rest using Laravel's Crypt facade (phone numbers, passwords, notes)
- Database backups encrypted with AES-256 before upload to offsite storage
- Media files on S3: private bucket with pre-signed URLs (15-min expiry for sensitive docs)
- Environment secrets managed via `.env` (never committed); production via server vault

### 9.4 Input Validation & XSS Prevention

- Laravel Form Requests for all web forms
- API Resource validation on all endpoints
- Sanitise all user-generated content before storage
- CSP headers and X-Frame-Options on web admin
- Flutter: certificate pinning for API requests

### 9.5 Audit Logging

- `spatie/laravel-activitylog` logs: who did what, to which record, at what time
- Retained for 1 year
- Admin can search and filter audit log
- Export audit log to CSV

### 9.6 GDPR / NDPR Compliance

- Privacy notice on registration (web and mobile)
- Members can request data export (their own data) from profile
- Members can request account deletion (soft delete; hard delete after 30 days)
- Admin data retention policy: inactive records archived after 2 years

**Deliverables:** Security checklist passing; penetration test (basic) completed; audit log active; data deletion workflow live.

---

## Phase 10 — Testing, QA & Deployment

**Goal:** Validate the full platform end-to-end and launch to production.

### 10.1 Backend Testing (Laravel + Pest)

- Unit tests: all service classes, helper functions, policy classes
- Feature tests: all API endpoints (happy path + error cases)
- Browser tests: critical Livewire flows (login, member approval, event creation, results upload)
- Target: 80%+ code coverage
- Load testing: Locust or Artillery — simulate 500 concurrent API requests
- Database: test with seeded realistic dataset (10,000 members, 500 PUs)

### 10.2 Flutter Testing

- Widget tests: all screens render without error
- Integration tests: full user flows (login → log door knock → upload result)
- Golden tests: screenshot regression for key screens
- Test on real devices: minimum 2 Android (different OEMs) and 1 iPhone
- Offline mode: disconnect network mid-flow and verify queuing and sync

### 10.3 UAT (User Acceptance Testing)

- Identify 10 real campaign volunteers as beta testers
- Provide test credentials for each role
- Structured test script covering: registration, event check-in, door knock log, WhatsApp blast, EC8A upload, leaderboard
- Collect feedback via Google Form; prioritise and fix critical issues

### 10.4 Performance Optimisation

- Laravel: route caching, config caching, view caching, Opcache
- DB: index review (EXPLAIN ANALYZE on slow queries), query caching via Redis
- API: paginate all list responses (max 50 per page), eager-load relationships
- Flutter: lazy loading images (cached_network_image), ListView.builder for all lists
- CDN: static assets (CSS, JS, images) served from CDN edge

### 10.5 Staging → Production Cutover

- Blue-green deployment strategy (Laravel Forge zero-downtime deploy)
- Production DB: imported from final staging snapshot
- Smoke tests run against production within 30 minutes of deploy
- Rollback plan documented and tested

### 10.6 App Store Submissions

- **Android:** APK → signed AAB → Google Play Console → Internal Test → Closed Test → Open Test → Production
- **iOS:** Xcode Archive → TestFlight (2 weeks beta) → App Store Review → Release
- Required assets: icons, splash, screenshots (multiple device sizes), store descriptions
- Privacy policy hosted at `onyendoziconnect.org/privacy`

### 10.7 Post-Launch Monitoring

- Laravel Telescope: local debugging in staging
- Sentry: error tracking in production (Laravel + Flutter SDK)
- UptimeRobot: public uptime monitoring with SMS/email alerts on downtime
- Laravel Horizon: queue health and failed job monitoring
- Google Analytics (Firebase Analytics in Flutter): screen views, crash reports

**Deliverables:** All tests passing; app live on both app stores; production server monitored; team trained on admin dashboard.

---

## 15. Database Schema Overview

### Core Tables

```
users                   — id, name, phone, email, password, role_id, lga_id, ward_id, polling_unit_id, status, device_token, last_seen_at
lgas                    — id, name, state
wards                   — id, name, lga_id
polling_units           — id, name, code, ward_id, lat, lng, registered_voters
events                  — id, title, description, date, venue, lga_id, ward_id, created_by
event_attendances       — id, event_id, user_id, checked_in_at, method (qr/manual)
tasks                   — id, title, description, deadline, status, assigned_to_role, lga_id, ward_id, assigned_user_id, created_by, verified_by
task_completions        — id, task_id, user_id, notes, completed_at, verified_at
door_knocks             — id, user_id, lat, lng, address_description, voter_name, outcome, notes, visited_at
agent_locations         — id, user_id, lat, lng, accuracy, battery_level, recorded_at
incidents               — id, user_id, type, description, urgency, lat, lng, status, reported_at
incident_media          — id, incident_id, path, type (photo/video)
results                 — id, user_id, polling_unit_id, ec8a_image_path, submitted_at, verified_at, verified_by
result_entries          — id, result_id, party, votes
voice_reports           — id, user_id, audio_path, transcript, duration, created_at
materials               — id, type, quantity, lga_id, ward_id, polling_unit_id, distributed_at, confirmed_by
messages                — id, type (sms/whatsapp), body, audience_type, audience_id, created_by, scheduled_at, sent_at
news                    — id, title, body, image_path, category, is_breaking, published_at
media_library           — id, name, category, path, mime_type, size, uploaded_by
activity_log            — (spatie/laravel-activitylog standard schema)
leaderboard_points      — id, user_id, points, source_type, source_id, earned_at
```

---

## 16. API Contract Overview

All API routes prefixed `/api/v1/`. All responses: `Content-Type: application/json`. Authentication: `Authorization: Bearer {token}`.

### Auth
```
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
POST   /api/v1/auth/register
POST   /api/v1/auth/refresh
POST   /api/v1/auth/forgot-password
POST   /api/v1/auth/verify-otp
```

### Members
```
GET    /api/v1/members                  — list (paginated, filtered)
GET    /api/v1/members/{id}             — show
PUT    /api/v1/members/{id}             — update own profile
GET    /api/v1/members/nearby           — ?lat=&lng=&radius=
```

### Events
```
GET    /api/v1/events                   — list upcoming
GET    /api/v1/events/{id}
POST   /api/v1/events/{id}/check-in
```

### Tasks
```
GET    /api/v1/tasks                    — my tasks
PUT    /api/v1/tasks/{id}/complete
```

### Field Operations
```
POST   /api/v1/location/ping            — GPS heartbeat
POST   /api/v1/door-knocks             — log canvassing visit
GET    /api/v1/door-knocks             — my visit history
```

### Results & Incidents
```
POST   /api/v1/results                  — upload EC8A
GET    /api/v1/results/my-pu           — my PU result
POST   /api/v1/incidents               — report incident
POST   /api/v1/voice-reports           — upload voice note
```

### Content
```
GET    /api/v1/news                     — paginated news feed
GET    /api/v1/media                    — media library (jingles, flyers)
GET    /api/v1/leaderboard             — top 50 by points
GET    /api/v1/notifications           — my notification list
POST   /api/v1/notifications/read-all
```

### Standard Response Envelope
```json
{
  "success": true,
  "data": { ... },
  "message": "OK",
  "meta": { "current_page": 1, "total": 250 }
}
```

---

## 17. Non-Functional Requirements

| Category | Requirement |
|----------|------------|
| Availability | 99.5% uptime during campaign period; 99.9% on Election Day |
| API Response Time | p95 < 300ms for read endpoints; < 800ms for write with media |
| GPS Ping Latency | Agent location reflected on dashboard < 5 seconds |
| Real-time Broadcast | WebSocket event delivered < 2 seconds from trigger |
| Mobile App Size | < 30 MB initial download (iOS & Android) |
| Offline Support | Core features (view members, tasks, queue visits) work without internet |
| Scalability | Must handle 500 concurrent mobile users during Election Day |
| Accessibility | WCAG 2.1 AA on web; Flutter app supports system font scaling |
| Localization | English primary; Igbo language support (Flutter) in Phase 2 extension |
| Data Backup | Automated daily backup; point-in-time restore capability |

---

## 18. Milestones & Timeline

| Phase | Description | Duration | Cumulative |
|-------|-------------|----------|------------|
| Phase 1 | Foundation & Infrastructure | 2 weeks | Week 2 |
| Phase 2 | Authentication & User Management | 2 weeks | Week 4 |
| Phase 3 | Core Admin Dashboard | 3 weeks | Week 7 |
| Phase 4 | Field Operations Module | 2 weeks | Week 9 |
| Phase 5 | Flutter Mobile App | 4 weeks | Week 13 |
| Phase 6 | Communications & Media | 2 weeks | Week 15 |
| Phase 7 | Election Day Command Centre | 2 weeks | Week 17 |
| Phase 8 | Analytics & Reporting | 2 weeks | Week 19 |
| Phase 9 | Security & Compliance | 1 week | Week 20 |
| Phase 10 | Testing, QA & Deployment | 2 weeks | Week 22 |
| **Total** | **Full platform launch** | **~22 weeks** | **~5.5 months** |

> Target launch: **January 2027** (to allow 3 months of active use before the election).

---

*Document prepared by: Technical Team*  
*Project: Onyendozi Connect v1.0*  
*Last updated: June 2026*
