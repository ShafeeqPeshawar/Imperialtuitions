import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type LaunchRow = {
  id: number;
  course_id: number;
  launch_date: string;
  course_title: string;
  level: string | null;
};

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function GET() {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });

  const launches = await dbQuery<LaunchRow[]>(
    `SELECT cl.id, cl.course_id, cl.launch_date, c.title as course_title, c.level
     FROM course_launches cl
     JOIN courses c ON c.id = cl.course_id
     WHERE c.price = 0
     ORDER BY cl.launch_date ASC`
  );
  return NextResponse.json({ success: true, launches });
}

export async function POST(request: Request) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const body = (await request.json()) as { course_id?: number; launch_date?: string };
  const courseId = Number(body.course_id ?? 0);
  const launchDate = String(body.launch_date ?? "").trim();
  if (!courseId || !launchDate) {
    return NextResponse.json({ success: false, message: "Course and launch date are required." }, { status: 422 });
  }
  if (Number.isNaN(Date.parse(launchDate))) {
    return NextResponse.json({ success: false, message: "Launch date must be a valid date." }, { status: 422 });
  }
  const course = await dbQuery<Array<{ id: number }>>("SELECT id FROM courses WHERE id = ? AND price = 0 LIMIT 1", [courseId]);
  if (!course.length) {
    return NextResponse.json({ success: false, message: "Only free courses can be launched." }, { status: 422 });
  }

  await dbQuery("INSERT INTO course_launches (course_id, launch_date, created_at, updated_at) VALUES (?, ?, NOW(), NOW())", [
    courseId,
    launchDate,
  ]);

  return NextResponse.json({ success: true, message: "Launch date added." });
}
