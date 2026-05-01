import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

type TopicRow = {
  id: number;
  course_id: number;
  title: string;
  description: string | null;
  sort_order: number;
  is_active: number;
};


export async function GET(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;
  const topics = await dbQuery<TopicRow[]>(
    "SELECT id, course_id, title, description, sort_order, is_active FROM course_topics WHERE course_id = ? ORDER BY sort_order ASC, id ASC",
    [id]
  );
  return NextResponse.json({ success: true, topics });
}

export async function POST(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;
  const body = (await request.json()) as {
    title?: string;
    description?: string | null;
    sort_order?: number | null;
    is_active?: boolean;
  };
  const title = String(body.title ?? "").trim();
  if (!title) return NextResponse.json({ success: false, message: "Topic title is required." }, { status: 422 });
  if (title.length > 255) return NextResponse.json({ success: false, message: "Topic title may not be greater than 255 characters." }, { status: 422 });
  if (body.description != null && typeof body.description !== "string") {
    return NextResponse.json({ success: false, message: "Description must be a string." }, { status: 422 });
  }

  let sortOrder = Number(body.sort_order ?? 0);
  if (body.sort_order != null && !Number.isInteger(Number(body.sort_order))) {
    return NextResponse.json({ success: false, message: "Sort order must be an integer." }, { status: 422 });
  }
  if (!sortOrder) {
    const rows = await dbQuery<Array<{ max_sort_order: number | null }>>(
      "SELECT MAX(sort_order) as max_sort_order FROM course_topics WHERE course_id = ?",
      [id]
    );
    sortOrder = Number(rows[0]?.max_sort_order ?? 0) + 10;
  }

  await dbQuery(
    "INSERT INTO course_topics (course_id, title, description, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
    [id, title, body.description || null, sortOrder, body.is_active ? 1 : 0]
  );
  return NextResponse.json({ success: true, message: "Topic added successfully" });
}
