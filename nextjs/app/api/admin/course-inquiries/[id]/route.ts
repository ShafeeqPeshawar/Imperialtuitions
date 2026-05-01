import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type InquiryRow = {
  id: number;
  course_title: string;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  level: string | null;
  launch_date: string | null;
};

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function GET(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;

  await dbQuery("UPDATE course_inquiries SET is_viewed = 1, updated_at = NOW() WHERE id = ?", [id]);
  const rows = await dbQuery<InquiryRow[]>(
    `SELECT id, course_title, name, email, phone, message, level, launch_date
     FROM course_inquiries
     WHERE id = ?
     LIMIT 1`,
    [id]
  );
  if (rows.length === 0) return NextResponse.json({ success: false, message: "Inquiry not found." }, { status: 404 });
  return NextResponse.json({ success: true, inquiry: rows[0] });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  await dbQuery("DELETE FROM course_inquiries WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Inquiry deleted successfully." });
}
