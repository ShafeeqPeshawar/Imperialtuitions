import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function PUT(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  const body = (await request.json()) as { course_id?: number; launch_date?: string };
  const courseId = Number(body.course_id ?? 0);
  const launchDate = String(body.launch_date ?? "").trim();
  if (!courseId || !launchDate) {
    return NextResponse.json({ success: false, message: "Course and launch date are required." }, { status: 422 });
  }
  if (Number.isNaN(Date.parse(launchDate))) {
    return NextResponse.json({ success: false, message: "Launch date must be a valid date." }, { status: 422 });
  }
  const exists = await dbQuery<Array<{ id: number }>>("SELECT id FROM courses WHERE id = ? AND price = 0 LIMIT 1", [courseId]);
  if (!exists.length) {
    return NextResponse.json({ success: false, message: "Only free courses can be launched." }, { status: 422 });
  }

  await dbQuery("UPDATE course_launches SET course_id = ?, launch_date = ?, updated_at = NOW() WHERE id = ?", [
    courseId,
    launchDate,
    id,
  ]);
  return NextResponse.json({ success: true, message: "Launch date updated." });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  await dbQuery("DELETE FROM course_launches WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Launch date deleted." });
}
