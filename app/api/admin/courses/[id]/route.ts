import fs from "fs/promises";
import path from "path";
import { randomUUID } from "crypto";
import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type ExistingRow = { image: string | null };

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
  const title = String(form.get("title") ?? "").trim();
  const description = String(form.get("description") ?? "").trim();
  const level = String(form.get("level") ?? "").trim();
  const durationValue = String(form.get("duration_value") ?? "").trim();
  const durationUnit = String(form.get("duration_unit") ?? "").trim();
  const price = Number(form.get("price") ?? 0);
  const skills = String(form.get("skills") ?? "").trim();
  const sortOrder = Number(form.get("sort_order") ?? 10);
  const trainingCategoryIdRaw = String(form.get("training_category_id") ?? "").trim();
  const isActive = form.get("is_active") ? 1 : 0;
  const image = form.get("image");

  const durationNumber = Number(durationValue);
  const validUnits = new Set(["hours", "days", "weeks", "months"]);
  if (!title || !description || !level || !durationValue || !durationUnit || Number.isNaN(price)) {
    return NextResponse.json({ success: false, message: "Missing required fields." }, { status: 422 });
  }
  if (!Number.isInteger(durationNumber) || durationNumber < 1) {
    return NextResponse.json({ success: false, message: "Duration value must be an integer greater than 0." }, { status: 422 });
  }
  if (!validUnits.has(durationUnit.toLowerCase())) {
    return NextResponse.json({ success: false, message: "Duration unit must be hours, days, weeks or months." }, { status: 422 });
  }
  if (!Number.isInteger(sortOrder)) {
    return NextResponse.json({ success: false, message: "Sort order is required and must be an integer." }, { status: 422 });
  }
  if (trainingCategoryIdRaw && (!Number.isInteger(Number(trainingCategoryIdRaw)) || Number(trainingCategoryIdRaw) <= 0)) {
    return NextResponse.json({ success: false, message: "Invalid training category." }, { status: 422 });
  }

  const existing = await dbQuery<ExistingRow[]>("SELECT image FROM courses WHERE id = ? LIMIT 1", [id]);
  if (existing.length === 0) {
    return NextResponse.json({ success: false, message: "Course not found." }, { status: 404 });
  }

  let filename = existing[0].image;
  if (image instanceof File && image.size > 0) {
    const ext = path.extname(image.name).toLowerCase();
    if (![".png", ".jpg", ".jpeg", ".webp"].includes(ext)) {
      return NextResponse.json({ success: false, message: "Image must be png, jpg, jpeg or webp." }, { status: 422 });
    }
    const safeExt = [".png", ".jpg", ".jpeg", ".webp"].includes(ext) ? ext : ".jpg";
    filename = `${Date.now()}-${randomUUID()}${safeExt}`;
    const bytes = Buffer.from(await image.arrayBuffer());
    const imagesDir = path.join(process.cwd(), "public", "images");
    await fs.mkdir(imagesDir, { recursive: true });
    await fs.writeFile(path.join(imagesDir, filename), bytes);
  }

  const duration = `${durationValue} ${durationUnit.charAt(0).toUpperCase()}${durationUnit.slice(1)}`;
  const trainingCategoryId = trainingCategoryIdRaw ? Number(trainingCategoryIdRaw) : null;
  if (trainingCategoryId) {
    const categories = await dbQuery<Array<{ id: number }>>("SELECT id FROM training_categories WHERE id = ? LIMIT 1", [trainingCategoryId]);
    if (!categories.length) {
      return NextResponse.json({ success: false, message: "Training category does not exist." }, { status: 422 });
    }
  }
  const duplicate = await dbQuery<Array<{ id: number }>>(
    "SELECT id FROM courses WHERE title = ? AND level = ? AND id <> ? LIMIT 1",
    [title, level, id]
  );
  if (duplicate.length) {
    return NextResponse.json({ success: false, message: "A course with this title already exists in this level!" }, { status: 422 });
  }

  await dbQuery(
    `UPDATE courses
     SET title = ?, description = ?, image = ?, level = ?, duration = ?, price = ?, skills = ?, sort_order = ?, is_active = ?, training_category_id = ?, updated_at = NOW()
     WHERE id = ?`,
    [title, description, filename, level, duration, price, skills || null, sortOrder, isActive, trainingCategoryId, id]
  );

  return NextResponse.json({ success: true, message: "Course updated successfully." });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;

  await dbQuery("DELETE FROM courses WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Course deleted successfully." });
}
