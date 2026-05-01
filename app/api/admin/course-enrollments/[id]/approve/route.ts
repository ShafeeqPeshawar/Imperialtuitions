import { NextResponse } from "next/server";
import { applyEnrollmentDecision } from "@/lib/admin-enrollment-decision";
import { requireUser } from "@/lib/api-auth";

export async function POST(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;

  const { message } = await applyEnrollmentDecision(id, "approved");
  return NextResponse.json({ success: true, message });
}
