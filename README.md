# Imperial Tuitions

Next.js production app (App Router): course catalog, admin, inquiries, enrollments, email notifications.

## Quick start

```bash
npm install
cp .env.example .env
# Edit .env: DB_*, AUTH_SECRET, APP_URL_NEXT, MAIL_*
npm run dev
```

Open [http://localhost:3000](http://localhost:3000).

See [RUN_AND_ADMIN_GUIDE.md](RUN_AND_ADMIN_GUIDE.md) for admin URLs, SMTP, and common issues.

## Legacy

The old Laravel / Blade tree was removed. Course images live under `public/images/` (database paths should match filenames).
