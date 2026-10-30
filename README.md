# DigitalBhudev Laravel 13 integration package

This package merges your supplied HTML/CSS frontend into Laravel Blade and adds a first database-backed implementation of the DigitalBhudev features. It is an integration package, not a standalone Laravel installation; merge it into a fresh Laravel 13 project. It has not been executed against your local MySQL project because that project ZIP has not been supplied yet.

## Included

- Shared Blade layout, separate header/footer, converted frontend views and existing CSS/JS assets.
- Laravel routes for public pages, Bhudev directory/detail, authentication, password reset, email verification, admin, Bhudev dashboard, Rashifal, Panchang and inquiries.
- Role separation: one Admin (created with a one-time interactive Artisan command) and multiple Bhudev users.
- Password reset via Laravel's password broker; login/register throttling; session regeneration; CSRF-protected POST forms; email verification routes.
- Database migrations for profiles, normalized services/pivot, gallery, profile revisions, status history, Rashifal, Panchang, inquiries and audit logs.
- Server-side public visibility scope and feature tests for pending/suspended profile access.
- Image upload validation (JPG/PNG/WebP, 3 MB per file) and image resizing to a maximum 1600px edge using PHP GD; files are stored on Laravel's `public` disk. No runtime CDN is required for the included JavaScript.
- Admin management views for profiles, statuses, Rashifal, Panchang, inquiries and audit logs.

## Integration steps

1. Generate a clean Laravel 13 app and make sure `php artisan --version` reports Laravel Framework 13.x.
2. Unzip this package into the Laravel project root, preserving paths. Back up any files it replaces first, especially `routes/web.php` and `app/Models/User.php`.
3. Merge the `role` middleware alias from `integration/bootstrap-app-middleware-snippet.php.txt` into the existing `withMiddleware` callback in `bootstrap/app.php`. Do not replace your entire bootstrap file.
4. Ensure your default users migration has `email_verified_at`, `remember_token`, `password`, `name`, and `email` columns.
5. Configure `.env`: `APP_URL=http://127.0.0.1:8000`, `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=digitalbhudev`, `DB_USERNAME=root`, `DB_PASSWORD=` (use your actual local password). Configure working SMTP mail settings for verification and password resets.
6. Run `php artisan key:generate` only if APP_KEY is empty, then `php artisan config:clear`.
7. Run `php artisan migrate`.
8. Run `php artisan db:seed` to create default service categories.
9. Run `php artisan storage:link` for public profile/gallery images.
10. Create the first admin securely: `php artisan digitalbhudev:create-admin admin@example.com`. It asks for a password interactively and refuses if any admin already exists. Do not commit the password to source control.
11. Run `php artisan serve` and visit `http://127.0.0.1:8000`.
12. Enable PHP GD in the active XAMPP `php.ini` (`extension=gd`) and restart Apache/PHP; the image optimizer depends on GD.
13. Run tests: `php artisan test`.
14. Test a database backup manually from the project root: `powershell -ExecutionPolicy Bypass -File .\scripts\backup-database.ps1 -Database digitalbhudev -Username root -DumpExe C:\xampp\mysql\bin\mysqldump.exe`. For scheduling, use a protected defaults file with `-DefaultsFile` so the task does not wait for an interactive password prompt.

## Security and important dependencies

- Email verification and password reset require SMTP (or another configured Laravel mail transport). Until configured, email links will not be delivered.
- SMS/mobile OTP is not enabled. It requires a chosen SMS provider and credentials; phone numbers are collected but are not verified by this package.
- Panchang is manually entered by Admin. Correct location/time-specific astronomical data needs a vetted Panchang provider/API or a validated calculation library, location coordinates, timezone, and clear rules for daylight/time-zone conversion. Do not invent astronomical values.
- Rashifal is manually entered and published by Admin; no horoscope API or generated astrology content is integrated.
- A Windows PowerShell `scripts/backup-database.ps1` script creates timestamped SQL dumps using XAMPP `mysqldump`; it prompts for the MySQL password. For unattended Windows Task Scheduler runs, create a MySQL client option file with credentials and lock it down with NTFS permissions, then pass it as `-DefaultsFile C:\secure\digitalbhudev-backup.cnf`. Do not schedule the interactive password-prompt mode. Keep backups out of Git, copy them off-device, and periodically test restoration. For production, use managed backups or a least-privilege backup account.
- Public profile images are stored on the public disk. Sensitive verification documents should use a private disk and authenticated download controller; this package does not accept identity documents.
- Review Laravel's generated `User` model/migration before replacing files. If you have customized them, merge the changes described in `integration/User-model-notes.txt`.
- Public profile edits on an already-approved profile are stored in `profile_revisions` and put the profile into pending/hidden state. The approval screen applies the submitted revision. Test this workflow carefully before production.
- This package is a foundation and must be tested locally before production use. No live reference crawl, deployment, SMTP delivery, backup job, mobile OTP, or external Panchang integration is claimed as completed.

## Notes / current scope

The initial frontend is a concept recreation, not a verified pixel-perfect crawl of digitalbhudev.com. The package preserves the supplied frontend design and makes key sections database-driven. The current first iteration includes create/publish/list/delete flows for Rashifal and Panchang. Service category management screens and automated scheduled database backups remain follow-up work. Rashifal and Panchang can be edited using their Edit links and support future publish timestamps. Admin can create Bhudev accounts and request a password setup email. The backend uses standard Blade and Laravel controllers, not React/Vue.
