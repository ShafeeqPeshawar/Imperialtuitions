import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { z } from "zod";
import { dbQuery } from "@/lib/db";
import { apiErrorResponseMessage } from "@/lib/api-errors";
import { authCookieName, createSessionToken } from "@/lib/auth";

const schema = z
  .object({
    name: z.string().trim().min(1).max(255),
    email: z.email(),
    password: z.string().min(8),
    password_confirmation: z.string().min(8),
  })
  .refine((v) => v.password === v.password_confirmation, {
    path: ["password_confirmation"],
    message: "Passwords do not match.",
  });

type ExistingUser = { id: number };

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = schema.safeParse(body);
    if (!parsed.success) {
      const errors = parsed.error.issues.map((issue) => {
        const field = issue.path[0];
        if (field === "name") return "Name is required and must be less than 255 characters.";
        if (field === "email") return "Please enter a valid email address.";
        if (field === "password") return "Password must be at least 8 characters.";
        if (field === "password_confirmation") return issue.message || "Password confirmation must be at least 8 characters.";
        return issue.message;
      });
      return NextResponse.json(
        {
          success: false,
          message: "Please fix the highlighted registration fields.",
          errors: Array.from(new Set(errors)),
        },
        { status: 422 }
      );
    }

    const { name, email, password } = parsed.data;
    const normalizedEmail = email.trim().toLowerCase();
    const existing = await dbQuery<ExistingUser[]>("SELECT id FROM users WHERE email = ? LIMIT 1", [normalizedEmail]);
    if (existing.length > 0) {
      return NextResponse.json({ success: false, message: "Email already registered." }, { status: 409 });
    }

    const hash = await bcrypt.hash(password, 12);
    const insert = (await dbQuery<{ insertId: number }[]>(
      `INSERT INTO users (name, email, password, created_at, updated_at)
       VALUES (?, ?, ?, NOW(), NOW())`,
      [name, normalizedEmail, hash]
    )) as unknown as { insertId: number };

    const token = createSessionToken(insert.insertId);
    const res = NextResponse.json({ success: true });
    res.cookies.set(authCookieName, token, {
      httpOnly: true,
      secure: false,
      sameSite: "lax",
      path: "/",
      maxAge: 60 * 60 * 24 * 7,
    });
    return res;
  } catch (error) {
    console.error("register API error:", error);
    const message = apiErrorResponseMessage(
      error,
      "Unable to register due to a server error. Please check server logs."
    );
    return NextResponse.json({ success: false, message }, { status: 500 });
  }
}
