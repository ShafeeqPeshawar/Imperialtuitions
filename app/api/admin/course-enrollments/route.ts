import { NextRequest, NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type EnrollmentRow = {
  id: number;
  course_name: string;
  registration_type: string | null;
  name: string;
  email: string;
  phone: string | null;
  message: string | null;
  status: "pending" | "approved" | "rejected";
  level: string | null;
  preferred_date: string | null;
  preferred_time: string | null;
  created_at: string;
};

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function GET(request: NextRequest) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });

  const page = Math.max(1, Number(request.nextUrl.searchParams.get("page") ?? "1"));
  const limit = 20;
  const offset = (page - 1) * limit;

  const [rows, countRows] = await Promise.all([
    dbQuery<EnrollmentRow[]>(
      `SELECT id, course_name, registration_type, name, email, phone, message, status, level, preferred_date, preferred_time, created_at
       FROM course_enrollments
       ORDER BY created_at DESC
       LIMIT ? OFFSET ?`,
      [limit, offset]
    ),
    dbQuery<Array<{ total: number }>>("SELECT COUNT(*) as total FROM course_enrollments"),
  ]);

  const total = Number(countRows[0]?.total ?? 0);
  const totalPages = Math.max(1, Math.ceil(total / limit));

  return NextResponse.json({ success: true, enrollments: rows, pagination: { page, limit, total, totalPages } });
}
