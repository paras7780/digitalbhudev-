# DigitalBhudev route inventory (implementation package)

| URL | Name | Behavior |
|---|---|---|
| `/` | `home` | Existing frontend homepage |
| `/bhudevs` | `bhudevs.index` | Dynamic approved/visible profiles with search, city/service filters and pagination |
| `/bhudevs/{slug}` | `bhudevs.show` | Public profile, gated server-side |
| `/services` | `services.index` | Existing frontend service overview (not yet DB-managed) |
| `/panchang` | `panchang.index` | Published date/location-specific Panchang |
| `/rashifal` | `rashifal.index` | Published Rashifal for 12 signs by date |
| `/contact` | `contact` | Contact form |
| `POST /contact` | `contact.store` | Saves inquiry with throttling |
| `/login`, `POST /login` | `login`, `login.store` | Role-aware login |
| `/register`, `POST /register` | `register`, `register.store` | Bhudev registration; pending profile |
| `/forgot-password`, `/reset-password/{token}` | password routes | Laravel password broker |
| `/email/verify` | `verification.notice` | Email verification notice |
| `/admin` | `admin.dashboard` | Admin dashboard (verified admin only) |
| `/admin/bhudevs` | `admin.bhudevs` | Search/filter and status management |
| `/admin/bhudevs/create` | `admin.bhudevs.create` | Admin creates a Bhudev account and requests password setup email |
| `/admin/rashifal` | `admin.rashifals` | Create/publish/delete Rashifal |
| `/admin/panchang` | `admin.panchang` | Create/publish/delete Panchang |
| `/admin/inquiries` | `admin.inquiries` | Review and update inquiry status |
| `/admin/audit-logs` | `admin.audit-logs` | Sensitive-action audit trail |
| `/bhudev` | `bhudev.dashboard` | Bhudev's own status/history |
| `/bhudev/profile/edit` | `bhudev.profile.edit` | Own profile and image management |

Not yet included: verified external source route crawl, booking/payments/chat/video, SMS OTP, provider integrations, full CRUD editing forms for date content, service category admin screens, automated backup scheduler, and production deployment hardening.
