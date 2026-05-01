import { NextResponse } from "next/server";
import { getCurrentUser } from "@/lib/auth";

export type AuthenticatedUser = NonNullable<Awaited<ReturnType<typeof getCurrentUser>>>;

/** Session guard for JSON API routes. */
export async function requireUser(): Promise<
  | { ok: true; user: AuthenticatedUser }
  | { ok: false; response: NextResponse }
> {
  const user = await getCurrentUser();
  if (!user) {
    return {
      ok: false,
      response: NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 }),
    };
  }
  return { ok: true, user };
}
