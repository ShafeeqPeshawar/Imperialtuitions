import { NextRequest, NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

type InquiryRow = {
  id: number;
  course_title: string;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  level: string | null;
  launch_date: string | null;
  is_viewed: number;
  reply_status: "pending" | "replied";
  created_at: string;
};


export async function GET(request: NextRequest) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const page = Math.max(1, Number(request.nextUrl.searchParams.get("page") ?? "1"));
  const limit = 20;
  const offset = (page - 1) * limit;

  const [rows, countRows] = await Promise.all([
    dbQuery<InquiryRow[]>(
      `SELECT id, course_title, name, email, phone, message, level, launch_date, is_viewed, reply_status, created_at
       FROM course_inquiries
       ORDER BY created_at DESC
       LIMIT ? OFFSET ?`,
      [limit, offset]
    ),
    dbQuery<Array<{ total: number }>>("SELECT COUNT(*) as total FROM course_inquiries"),
  ]);

  const total = Number(countRows[0]?.total ?? 0);
  const totalPages = Math.max(1, Math.ceil(total / limit));
  return NextResponse.json({ success: true, inquiries: rows, pagination: { page, limit, total, totalPages } });
}
