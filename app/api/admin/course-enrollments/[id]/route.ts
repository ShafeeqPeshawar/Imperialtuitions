import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

type EnrollmentRow = {
  id: number;
  course_name: string;
  name: string;
  email: string;
  phone: string | null;
  message: string | null;
  status: string;
  level: string | null;
  preferred_date: string | null;
  preferred_time: string | null;
  created_at: string;
};


export async function GET(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;

  const rows = await dbQuery<EnrollmentRow[]>(
    `SELECT id, course_name, name, email, phone, message, status, level, preferred_date, preferred_time, created_at
     FROM course_enrollments
     WHERE id = ?
     LIMIT 1`,
    [id]
  );
  if (rows.length === 0) {
    return NextResponse.json({ success: false, message: "Enrollment not found." }, { status: 404 });
  }
  return NextResponse.json({ success: true, enrollment: rows[0] });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;
  await dbQuery("DELETE FROM course_enrollments WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Enrollment deleted successfully." });
}
