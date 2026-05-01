import crypto from "crypto";
import { NextResponse } from "next/server";
import { z } from "zod";
import { dbQuery } from "@/lib/db";
import { sendMail } from "@/lib/mailer";

const schema = z.object({ email: z.email() });
type UserRow = { id: number; email: string; name: string };

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = schema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ success: false, message: "Invalid email." }, { status: 422 });
    }

    const email = parsed.data.email.trim().toLowerCase();
    const users = await dbQuery<UserRow[]>("SELECT id, email, name FROM users WHERE email = ? LIMIT 1", [email]);

    if (users.length > 0) {
      const user = users[0];
      const token = crypto.randomBytes(32).toString("hex");
      const tokenHash = crypto.createHash("sha256").update(token).digest("hex");

      await dbQuery("DELETE FROM password_reset_tokens WHERE email = ?", [email]);
      await dbQuery(
        "INSERT INTO password_reset_tokens (email, token, created_at) VALUES (?, ?, NOW())",
        [email, tokenHash]
      );

      const resetUrl = `${process.env.APP_URL_NEXT ?? "http://localhost:3000"}/reset-password/${token}?email=${encodeURIComponent(email)}`;
      await sendMail({
        to: user.email,
        subject: "Reset your password",
        html: `<p>Hi ${user.name},</p><p>Click the link below to reset your password:</p><p><a href="${resetUrl}">${resetUrl}</a></p>`,
      });
    }

    return NextResponse.json({
      success: true,
      message: "If your email exists in our system, a reset link has been sent.",
    });
  } catch (error) {
    console.error("forgot-password API error:", error);
    return NextResponse.json({ success: false, message: "Unable to process request." }, { status: 500 });
  }
}
