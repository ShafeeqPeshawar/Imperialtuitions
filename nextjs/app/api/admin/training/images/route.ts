import fs from "fs/promises";
import path from "path";
import { randomUUID } from "crypto";
import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function GET() {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const images = await dbQuery<Array<{ id: number; training_category_id: number; image: string }>>(
    "SELECT id, training_category_id, image FROM training_images ORDER BY id DESC"
  );
  return NextResponse.json({ success: true, images });
}

export async function POST(request: Request) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const form = await request.formData();
  const categoryId = Number(form.get("category_id") ?? 0);
  const image = form.get("image");
  if (!categoryId) return NextResponse.json({ success: false, message: "Category is required." }, { status: 422 });
  if (!(image instanceof File) || image.size === 0) return NextResponse.json({ success: false, message: "Image is required." }, { status: 422 });

  const ext = path.extname(image.name).toLowerCase();
  const safeExt = [".png", ".jpg", ".jpeg", ".webp"].includes(ext) ? ext : ".jpg";
  const filename = `${Date.now()}-${randomUUID()}${safeExt}`;
  const relPath = `training/${filename}`;
  const absDir = path.join(process.cwd(), "public", "training");
  await fs.mkdir(absDir, { recursive: true });
  await fs.writeFile(path.join(absDir, filename), Buffer.from(await image.arrayBuffer()));

  await dbQuery(
    "INSERT INTO training_images (training_category_id, image, created_at, updated_at) VALUES (?, ?, NOW(), NOW())",
    [categoryId, relPath]
  );
  return NextResponse.json({ success: true, message: "Image added" });
}
