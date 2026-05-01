import { NextRequest, NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";

type ContactRow = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  reply_status: "pending" | "replied";
  is_viewed: number;
  created_at: string;
};


export async function GET(request: NextRequest) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const page = Math.max(1, Number(request.nextUrl.searchParams.get("page") ?? "1"));
  const limit = 20;
  const offset = (page - 1) * limit;

  const [rows, countRows] = await Promise.all([
    dbQuery<ContactRow[]>(
      `SELECT id, name, email, phone, message, reply_status, is_viewed, created_at
       FROM contacts
       ORDER BY created_at DESC
       LIMIT ? OFFSET ?`,
      [limit, offset]
    ),
    dbQuery<Array<{ total: number }>>("SELECT COUNT(*) as total FROM contacts"),
  ]);

  const total = Number(countRows[0]?.total ?? 0);
  const totalPages = Math.max(1, Math.ceil(total / limit));
  return NextResponse.json({ success: true, contacts: rows, pagination: { page, limit, total, totalPages } });
}
