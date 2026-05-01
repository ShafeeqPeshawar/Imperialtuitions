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

export async function GET() {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const categories = await dbQuery<Array<{ id: number; name: string; slug: string; sort_order: number }>>(
    "SELECT id, name, slug, sort_order FROM training_categories ORDER BY sort_order ASC, id ASC"
  );
  return NextResponse.json({ success: true, categories });
}

export async function POST(request: Request) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const body = (await request.json()) as { name?: string; sort_order?: number | null };
  const name = String(body.name ?? "").trim();
  if (!name) return NextResponse.json({ success: false, message: "Category name is required." }, { status: 422 });

  const exists = await dbQuery<Array<{ id: number }>>("SELECT id FROM training_categories WHERE name = ? LIMIT 1", [name]);
  if (exists.length) return NextResponse.json({ success: false, message: "Category with this name already exists." }, { status: 409 });

  let sortOrder = Number(body.sort_order ?? 0);
  if (!sortOrder) {
    const rows = await dbQuery<Array<{ max_sort_order: number | null }>>("SELECT MAX(sort_order) as max_sort_order FROM training_categories");
    sortOrder = Number(rows[0]?.max_sort_order ?? 0) + 10;
  }

  await dbQuery(
    "INSERT INTO training_categories (name, slug, sort_order, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
    [name, slugify(name), sortOrder]
  );
  return NextResponse.json({ success: true, message: "Category added" });
}
