import { NextResponse } from "next/server";
import { z } from "zod";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

const schema = z.object({
  name: z.string().trim().min(1).max(255),
  email: z.email(),
});

type ExistingRow = { id: number };

export async function PATCH(request: Request) {
  try {
    const user = await getCurrentUser();
    if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });

    const body = await request.json();
    const parsed = schema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ success: false, message: "Invalid profile data." }, { status: 422 });
    }

    const email = parsed.data.email.trim().toLowerCase();
    const exists = await dbQuery<ExistingRow[]>(
      "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1",
      [email, user.id]
    );
    if (exists.length > 0) {
      return NextResponse.json({ success: false, message: "Email already taken." }, { status: 409 });
    }

    await dbQuery(
      `UPDATE users
       SET name = ?, email = ?, email_verified_at = IF(email = ?, email_verified_at, NULL), updated_at = NOW()
       WHERE id = ?`,
      [parsed.data.name, email, user.email, user.id]
    );

    return NextResponse.json({ success: true, message: "Profile updated." });
  } catch (error) {
    console.error("profile update API error:", error);
    return NextResponse.json({ success: false, message: "Unable to update profile." }, { status: 500 });
  }
}
