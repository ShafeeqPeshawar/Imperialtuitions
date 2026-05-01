import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { z } from "zod";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

const schema = z
  .object({
    current_password: z.string().min(1),
    password: z.string().min(8),
    password_confirmation: z.string().min(8),
  })
  .refine((v) => v.password === v.password_confirmation, {
    path: ["password_confirmation"],
    message: "Passwords do not match.",
  });

type PasswordRow = { password: string };

export async function PUT(request: Request) {
  try {
    const auth = await requireUser();
    if (!auth.ok) return auth.response;

    const body = await request.json();
    const parsed = schema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ success: false, message: "Invalid password data." }, { status: 422 });
    }

    const rows = await dbQuery<PasswordRow[]>("SELECT password FROM users WHERE id = ? LIMIT 1", [auth.user.id]);
    if (rows.length === 0) return NextResponse.json({ success: false, message: "User not found." }, { status: 404 });

    const ok = await bcrypt.compare(parsed.data.current_password, rows[0].password);
    if (!ok) {
      return NextResponse.json({ success: false, message: "Current password is incorrect." }, { status: 400 });
    }

    const hash = await bcrypt.hash(parsed.data.password, 12);
    await dbQuery("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?", [hash, auth.user.id]);
    return NextResponse.json({ success: true, message: "Password updated." });
  } catch (error) {
    console.error("profile password API error:", error);
    return NextResponse.json({ success: false, message: "Unable to update password." }, { status: 500 });
  }
}
