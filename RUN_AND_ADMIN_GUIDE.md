# Imperial Tuitions Next.js Run and Admin Guide

## 1) Run the application locally

From terminal, in the **project root** (where `package.json` lives):

```bash
npm install
npm run dev
```

Open in browser:

- `http://localhost:3000`

If port 3000 is busy:

```bash
npm run dev -- -p 3001
```

Then open `http://localhost:3001`.

---

## 2) Required environment file

Create this file:

- `.env` in the **project root** (next to `package.json`)

Minimum example:

```env
NODE_ENV=development
APP_URL_NEXT=http://localhost:3000
AUTH_SECRET=change-this-to-a-long-random-secret

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imperialtuitions
DB_USERNAME=root
DB_PASSWORD=root

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your-email@gmail.com
```

Copy from `.env.example` if helpful. If `.env` is missing, homepage data fetch can fail with:

- `Missing environment variable: DB_HOST`

---

## 3) Login details for admin panel

There is no hardcoded default admin user/password in the current Next.js app.

Use one of these:

1. Login with an existing user from your DB `users` table, or
2. Create a new account at:
   - `http://localhost:3000/register`

Then login:

- `http://localhost:3000/login`

Current behavior:

- Any authenticated user can access `/admin/*` routes.

---

## 4) Admin panel URLs

- Admin root: `http://localhost:3000/admin`
- Courses: `http://localhost:3000/admin/courses`
- Popular courses: `http://localhost:3000/admin/courses/popular`
- Course launches: `http://localhost:3000/admin/course-launches`
- Enrollments: `http://localhost:3000/admin/course-enrollments`
- Inquiries: `http://localhost:3000/admin/course-inquiries`
- Contacts: `http://localhost:3000/admin/contacts`
- Subscribers: `http://localhost:3000/admin/subscribers`
- Training gallery: `http://localhost:3000/admin/training`

---

## 5) How to add a course from admin panel

1. Login at `/login`
2. Open `/admin/courses`
3. Click `+ Add Course`
4. Fill fields:
   - Course Title
   - Category
   - Description
   - Level
   - Duration value and unit
   - Price
   - Sort Order (optional)
   - Image
   - Active checkbox
5. Click `Save Course`

After saving:

- Course appears in admin courses list
- If active, it appears on public pages

Optional follow-up actions:

- Click `Topics` to manage topics
- Select course and click `Make Popular`
- For free courses, add launch date in `/admin/course-launches`

---

## 6) Common issues and fixes

### A) Vite/Laravel page opens on `localhost:5173`

Cause:

- Running a different project, or the dev server was started from the wrong folder.

Fix:

- `cd` to this repo root (where `package.json` is), then:

```bash
npm run dev
```

### B) `Invalid src prop ... images.unsplash.com`

Status:

- Already fixed in `next.config.ts` by allowing `images.unsplash.com`.

Action:

- Restart dev server once.

### C) `middleware` deprecation / `proxy` conflict

Status:

- Migrated to `proxy.ts` and `middleware.ts` removed.

Action:

- Restart dev server once.

### D) Leftover `nextjs/` folder after layout change

If an empty or cache-only `nextjs` folder remains, stop all Node/dev processes using it, then delete that folder. The app now runs from the repository root.

---

## 7) Production-style run

From project root:

```bash
npm run lint
npm run build
npm run start
```

---

## 8) Quick smoke check

Open and verify:

- `/`
- `/login`
- `/admin/courses`
- Add one test course
- Verify it appears in list
