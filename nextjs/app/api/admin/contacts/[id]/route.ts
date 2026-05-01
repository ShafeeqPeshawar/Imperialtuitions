import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";

type ContactRow = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  message: string;
};

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function GET(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;

  await dbQuery("UPDATE contacts SET is_viewed = 1, updated_at = NOW() WHERE id = ?", [id]);
  const rows = await dbQuery<ContactRow[]>("SELECT id, name, email, phone, message FROM contacts WHERE id = ? LIMIT 1", [id]);
  if (rows.length === 0) return NextResponse.json({ success: false, message: "Contact not found." }, { status: 404 });
  return NextResponse.json({ success: true, contact: rows[0] });
}

export async function DELETE(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  await dbQuery("DELETE FROM contacts WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Contact deleted successfully." });
}
