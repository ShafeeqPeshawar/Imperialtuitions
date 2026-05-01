# CLAUDE.md

## Project

**Imperial Tuitions** — course site, admin panel, contacts, enrollments, inquiries, subscribers, and email notifications.

## Where the code lives

- **Application**: `nextjs/` (Next.js App Router, React 19, API routes)
- **Not used anymore**: Laravel tree was removed; do not expect PHP/Blade/Vite at repo root.

## Stack

- Next.js 16, TypeScript, Tailwind (v4 in Next)
- MySQL via `mysql2` (`nextjs/lib/db.ts`)
- Auth: session/cookies (`nextjs/lib/auth.ts`, `AUTH_SECRET` in `nextjs/.env`)
- Mail: Nodemailer + `nextjs/lib/mailer.ts`, templates in `nextjs/lib/email-templates.ts`

## Commands

```bash
cd nextjs
npm install
npm run dev
npm run lint
npm run build && npm run start
```

## Environment

Configure `nextjs/.env` — see `nextjs/.env.example` and `nextjs/RUN_AND_ADMIN_GUIDE.md`.

## Notes

- Uploaded course images are served from `nextjs/public/images/` (paths in DB should match filenames).
- Admin routes live under `/admin/*`; any logged-in user currently has access (same as prior Next behavior).
