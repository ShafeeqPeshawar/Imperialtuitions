import crypto from "crypto";
import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { z } from "zod";
import { dbQuery } from "@/lib/db";

const schema = z
  .object({
    token: z.string().min(1),
    email: z.email(),
    password: z.string().min(8),
    password_confirmation: z.string().min(8),
  })
  .refine((v) => v.password === v.password_confirmation, {
    path: ["password_confirmation"],
    message: "Passwords do not match.",
  });

type TokenRow = { email: string; token: string; created_at: string };

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = schema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ success: false, message: "Invalid reset data." }, { status: 422 });
    }

    const { token, email, password } = parsed.data;
    const normalizedEmail = email.trim().toLowerCase();
    const tokenHash = crypto.createHash("sha256").update(token).digest("hex");

    const rows = await dbQuery<TokenRow[]>(
      "SELECT email, token, created_at FROM password_reset_tokens WHERE email = ? LIMIT 1",
      [normalizedEmail]
    );

    if (rows.length === 0 || rows[0].token !== tokenHash) {
      return NextResponse.json({ success: false, message: "Invalid or expired token." }, { status: 400 });
    }

    const createdAt = new Date(rows[0].created_at).getTime();
    if (Date.now() - createdAt > 1000 * 60 * 60) {
      return NextResponse.json({ success: false, message: "Reset token expired." }, { status: 400 });
    }

    const hash = await bcrypt.hash(password, 12);
    await dbQuery("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?", [hash, normalizedEmail]);
    await dbQuery("DELETE FROM password_reset_tokens WHERE email = ?", [normalizedEmail]);

    return NextResponse.json({ success: true, message: "Password reset successfully." });
  } catch (error) {
    console.error("reset-password API error:", error);
    return NextResponse.json({ success: false, message: "Unable to reset password." }, { status: 500 });
  }
}
