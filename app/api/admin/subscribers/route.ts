import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type SubscriberRow = { id: number; email: string; created_at: string };

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function GET() {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const subscribers = await dbQuery<SubscriberRow[]>(
    "SELECT id, email, created_at FROM subscribers ORDER BY created_at DESC"
  );
  return NextResponse.json({ success: true, subscribers });
}
