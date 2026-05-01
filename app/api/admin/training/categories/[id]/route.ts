import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

function slugify(input: string) {
  return input
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function PUT(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  const body = (await request.json()) as { name?: string; sort_order?: number | null };
  const name = String(body.name ?? "").trim();
  if (!name) return NextResponse.json({ success: false, message: "Category name is required." }, { status: 422 });

  const exists = await dbQuery<Array<{ id: number }>>("SELECT id FROM training_categories WHERE name = ? AND id <> ? LIMIT 1", [name, id]);
  if (exists.length) return NextResponse.json({ success: false, message: "Category with this name already exists." }, { status: 409 });

  await dbQuery("UPDATE training_categories SET name = ?, slug = ?, sort_order = ?, updated_at = NOW() WHERE id = ?", [
    name,
    slugify(name),
    Number(body.sort_order ?? 0),
    id,
  ]);
  return NextResponse.json({ success: true, message: "Category updated" });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  await dbQuery("DELETE FROM training_categories WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Category deleted" });
}
