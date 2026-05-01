import { NextResponse } from "next/server";
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
};


export async function GET(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
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
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;
  await dbQuery("DELETE FROM course_inquiries WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Inquiry deleted successfully." });
}
