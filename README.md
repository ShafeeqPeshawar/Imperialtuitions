# Imperial Tuitions

Production application: **Next.js** in the [`nextjs/`](nextjs/) folder.

## Quick start

```bash
cd nextjs
npm install
cp .env.example .env
# Edit .env (database, AUTH_SECRET, mail)
npm run dev
```

Open `http://localhost:3000`.

See [`nextjs/RUN_AND_ADMIN_GUIDE.md`](nextjs/RUN_AND_ADMIN_GUIDE.md) for details.

## Legacy

The previous Laravel/Blade app was removed from this repository. Database schema and uploads you still use should match what the Next app expects; course images live under `nextjs/public/images/`.
