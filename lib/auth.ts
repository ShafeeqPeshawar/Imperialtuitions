import crypto from "crypto";
import { cookies } from "next/headers";
import { dbQuery } from "@/lib/db";

const AUTH_COOKIE = "imperial_auth";

type UserRow = {
  id: number;
  name: string;
  email: string;
};

function authSecret() {
  return process.env.AUTH_SECRET ?? "imperial-dev-secret";
}

function sign(value: string) {
  return crypto.createHmac("sha256", authSecret()).update(value).digest("hex");
}

export function createSessionToken(userId: number) {
  const payload = `${userId}`;
  const signature = sign(payload);
  return `${payload}.${signature}`;
}

function verifySessionToken(token: string | undefined) {
  if (!token) return null;
  const [payload, signature] = token.split(".");
  if (!payload || !signature) return null;
  if (sign(payload) !== signature) return null;
  const userId = Number(payload);
  if (!Number.isFinite(userId) || userId <= 0) return null;
  return userId;
}

export async function getCurrentUser() {
  const cookieStore = await cookies();
  const token = cookieStore.get(AUTH_COOKIE)?.value;
  const userId = verifySessionToken(token);
  if (!userId) return null;

  const rows = await dbQuery<UserRow[]>(
    "SELECT id, name, email FROM users WHERE id = ? LIMIT 1",
    [userId]
  );

  return rows[0] ?? null;
}

export const authCookieName = AUTH_COOKIE;
