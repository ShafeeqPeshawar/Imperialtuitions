import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type TopicRow = {
  id: number;
  course_id: number;
  title: string;
  description: string | null;
  sort_order: number;
  is_active: number;
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
  const rows = await dbQuery<TopicRow[]>(
    "SELECT id, course_id, title, description, sort_order, is_active FROM course_topics WHERE id = ? LIMIT 1",
    [id]
  );
  if (!rows.length) return NextResponse.json({ success: false, message: "Topic not found." }, { status: 404 });
  return NextResponse.json({ success: true, topic: rows[0] });
}

export async function PUT(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  const body = (await request.json()) as {
    title?: string;
    description?: string | null;
    sort_order?: number;
    is_active?: boolean;
  };
  const title = String(body.title ?? "").trim();
  if (!title) return NextResponse.json({ success: false, message: "Topic title is required." }, { status: 422 });
  if (title.length > 255) return NextResponse.json({ success: false, message: "Topic title may not be greater than 255 characters." }, { status: 422 });
  if (body.description != null && typeof body.description !== "string") {
    return NextResponse.json({ success: false, message: "Description must be a string." }, { status: 422 });
  }
  if (!Number.isInteger(Number(body.sort_order))) {
    return NextResponse.json({ success: false, message: "Sort order is required and must be an integer." }, { status: 422 });
  }
  await dbQuery(
    "UPDATE course_topics SET title = ?, description = ?, sort_order = ?, is_active = ?, updated_at = NOW() WHERE id = ?",
    [title, body.description || null, Number(body.sort_order ?? 0), body.is_active ? 1 : 0, id]
  );
  return NextResponse.json({ success: true, message: "Topic updated successfully" });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  await dbQuery("DELETE FROM course_topics WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Topic deleted successfully" });
}
