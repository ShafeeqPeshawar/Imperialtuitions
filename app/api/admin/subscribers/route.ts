import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

type SubscriberRow = { id: number; email: string; created_at: string };


export async function GET() {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const subscribers = await dbQuery<SubscriberRow[]>(
    "SELECT id, email, created_at FROM subscribers ORDER BY created_at DESC"
  );
  return NextResponse.json({ success: true, subscribers });
}
