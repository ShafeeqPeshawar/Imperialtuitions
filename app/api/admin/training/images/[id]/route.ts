import fs from "fs/promises";
import path from "path";
import { randomUUID } from "crypto";
import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type ImgRow = { image: string };

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function PUT(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  const form = await request.formData();
  const categoryId = Number(form.get("category_id") ?? 0);
  const image = form.get("image");
  if (!categoryId) return NextResponse.json({ success: false, message: "Category is required." }, { status: 422 });

  const existing = await dbQuery<ImgRow[]>("SELECT image FROM training_images WHERE id = ? LIMIT 1", [id]);
  if (!existing.length) return NextResponse.json({ success: false, message: "Image not found." }, { status: 404 });
  let relPath = existing[0].image;

  if (image instanceof File && image.size > 0) {
    try {
      await fs.unlink(path.join(process.cwd(), "public", relPath));
    } catch {}
    const ext = path.extname(image.name).toLowerCase();
    const safeExt = [".png", ".jpg", ".jpeg", ".webp"].includes(ext) ? ext : ".jpg";
    const filename = `${Date.now()}-${randomUUID()}${safeExt}`;
    relPath = `training/${filename}`;
    const absDir = path.join(process.cwd(), "public", "training");
    await fs.mkdir(absDir, { recursive: true });
    await fs.writeFile(path.join(absDir, filename), Buffer.from(await image.arrayBuffer()));
  }

  await dbQuery("UPDATE training_images SET training_category_id = ?, image = ?, updated_at = NOW() WHERE id = ?", [
    categoryId,
    relPath,
    id,
  ]);
  return NextResponse.json({ success: true, message: "Image updated" });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  const existing = await dbQuery<ImgRow[]>("SELECT image FROM training_images WHERE id = ? LIMIT 1", [id]);
  if (existing.length) {
    try {
      await fs.unlink(path.join(process.cwd(), "public", existing[0].image));
    } catch {}
  }
  await dbQuery("DELETE FROM training_images WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Image deleted" });
}
