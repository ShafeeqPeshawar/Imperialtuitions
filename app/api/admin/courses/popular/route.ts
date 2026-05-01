import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

type CourseRow = { id: number; title: string; is_popular: number };


export async function GET() {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const courses = await dbQuery<CourseRow[]>(
    "SELECT id, title, is_popular FROM courses WHERE is_popular = 1 ORDER BY sort_order ASC, id DESC"
  );
  return NextResponse.json({ success: true, courses });
}

export async function POST(request: Request) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const body = (await request.json()) as { selected_courses?: number[] };
  const selected = Array.isArray(body.selected_courses) ? body.selected_courses : [];
  if (!selected.length) {
    return NextResponse.json({ success: false, message: "Please select at least one course first." }, { status: 422 });
  }
  const placeholders = selected.map(() => "?").join(",");
  await dbQuery(`UPDATE courses SET is_popular = 1, updated_at = NOW() WHERE id IN (${placeholders})`, selected);
  return NextResponse.json({ success: true, message: "Selected courses added to Popular Courses." });
}

export async function DELETE(request: Request) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const body = (await request.json()) as { selected_courses?: number[] };
  const selected = Array.isArray(body.selected_courses) ? body.selected_courses : [];
  if (!selected.length) {
    return NextResponse.json({ success: false, message: "No courses selected." }, { status: 422 });
  }
  const placeholders = selected.map(() => "?").join(",");
  await dbQuery(`UPDATE courses SET is_popular = 0, updated_at = NOW() WHERE id IN (${placeholders})`, selected);
  return NextResponse.json({ success: true, message: "Selected courses removed from Popular." });
}
