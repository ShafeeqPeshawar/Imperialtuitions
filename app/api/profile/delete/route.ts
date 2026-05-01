import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { z } from "zod";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";
import { authCookieName } from "@/lib/auth";

const schema = z.object({ password: z.string().min(1) });
type PasswordRow = { password: string };

export async function DELETE(request: Request) {
  try {
    const auth = await requireUser();
    if (!auth.ok) return auth.response;

    const body = await request.json();
    const parsed = schema.safeParse(body);
    if (!parsed.success) {
      return NextResponse.json({ success: false, message: "Password is required." }, { status: 422 });
    }

    const rows = await dbQuery<PasswordRow[]>("SELECT password FROM users WHERE id = ? LIMIT 1", [auth.user.id]);
    if (rows.length === 0) return NextResponse.json({ success: false, message: "User not found." }, { status: 404 });

    const ok = await bcrypt.compare(parsed.data.password, rows[0].password);
    if (!ok) {
      return NextResponse.json({ success: false, message: "Password is incorrect." }, { status: 400 });
    }

    await dbQuery("DELETE FROM users WHERE id = ?", [auth.user.id]);
    const res = NextResponse.json({ success: true, message: "Account deleted." });
    res.cookies.set(authCookieName, "", {
      httpOnly: true,
      secure: false,
      sameSite: "lax",
      path: "/",
      maxAge: 0,
    });
    return res;
  } catch (error) {
    console.error("profile delete API error:", error);
    return NextResponse.json({ success: false, message: "Unable to delete account." }, { status: 500 });
  }
}
