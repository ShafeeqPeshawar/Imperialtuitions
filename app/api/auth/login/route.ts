import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { dbQuery } from "@/lib/db";
import { apiErrorResponseMessage } from "@/lib/api-errors";
import { authCookieName, createSessionToken } from "@/lib/auth";

type UserWithPassword = {
  id: number;
  name: string;
  email: string;
  password: string;
};

export async function POST(request: Request) {
  try {
    const body = (await request.json()) as { email?: string; password?: string };
    const email = (body.email ?? "").trim().toLowerCase();
    const password = body.password ?? "";

    if (!email || !password) {
      return NextResponse.json({ success: false, message: "Email and password are required." }, { status: 422 });
    }

    const users = await dbQuery<UserWithPassword[]>(
      "SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1",
      [email]
    );

    if (users.length === 0) {
      return NextResponse.json({ success: false, message: "Invalid credentials." }, { status: 401 });
    }

    const user = users[0];
    const ok = await bcrypt.compare(password, user.password);
    if (!ok) {
      return NextResponse.json({ success: false, message: "Invalid credentials." }, { status: 401 });
    }

    const token = createSessionToken(user.id);
    const res = NextResponse.json({ success: true, user: { id: user.id, name: user.name, email: user.email } });
    res.cookies.set(authCookieName, token, {
      httpOnly: true,
      secure: false,
      sameSite: "lax",
      path: "/",
      maxAge: 60 * 60 * 24 * 7,
    });
    return res;
  } catch (error) {
    console.error("login API error:", error);
    const message = apiErrorResponseMessage(
      error,
      "Unable to login due to a server error. Please check server logs."
    );
    return NextResponse.json({ success: false, message }, { status: 500 });
  }
}
