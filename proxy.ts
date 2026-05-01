import { NextRequest, NextResponse } from "next/server";

function authSecret() {
  return process.env.AUTH_SECRET ?? "imperial-dev-secret";
}

function toHex(buffer: ArrayBuffer) {
  return Array.from(new Uint8Array(buffer))
    .map((b) => b.toString(16).padStart(2, "0"))
    .join("");
}

async function sign(value: string) {
  const key = await crypto.subtle.importKey(
    "raw",
    new TextEncoder().encode(authSecret()),
    { name: "HMAC", hash: "SHA-256" },
    false,
    ["sign"]
  );
  const signature = await crypto.subtle.sign("HMAC", key, new TextEncoder().encode(value));
  return toHex(signature);
}

async function isAuthenticated(request: NextRequest) {
  const token = request.cookies.get("imperial_auth")?.value;
  if (!token) return false;
  const [payload, signature] = token.split(".");
  if (!payload || !signature) return false;
  return (await sign(payload)) === signature;
}

export async function proxy(request: NextRequest) {
  const { pathname } = request.nextUrl;
  const protectedPaths = ["/dashboard", "/profile", "/admin"];

  if (protectedPaths.some((p) => pathname === p || pathname.startsWith(`${p}/`))) {
    if (!(await isAuthenticated(request))) {
      const loginUrl = new URL("/login", request.url);
      return NextResponse.redirect(loginUrl);
    }
  }

  return NextResponse.next();
}

export const config = {
  matcher: ["/dashboard/:path*", "/profile/:path*", "/admin/:path*"],
};
