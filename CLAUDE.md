# CLAUDE.md

@AGENTS.md

## Project

**Imperial Tuitions** — course site, admin panel, contacts, enrollments, inquiries, subscribers, and email notifications.

## Where the code lives

- **Application**: Repo root (`app/`, `components/`, `lib/`, API routes).

## Stack

- Next.js 16, TypeScript, Tailwind (v4)
- MySQL via `mysql2` (`lib/db.ts`)
- Auth: session/cookies (`lib/auth.ts`, `AUTH_SECRET` in `.env`)
- Mail: Nodemailer (`lib/mailer.ts`), templates in `lib/email-templates.ts`

## Commands

```bash
npm install
npm run dev
npm run lint
npm run build && npm run start
```

## Environment

Configure `.env` — see `.env.example` and `RUN_AND_ADMIN_GUIDE.md`.

## Notes

- Uploaded course images are served from `public/images/` (paths in DB should match filenames).
- Admin routes live under `/admin/*`; any logged-in user currently has access (prior behavior parity).
