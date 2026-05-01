import { NextRequest, NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";

type SwitchRow = { id: number };

export async function GET(request: NextRequest) {
  try {
    const title = request.nextUrl.searchParams.get("title")?.trim() ?? "";
    const level = request.nextUrl.searchParams.get("level")?.trim() ?? "";

    if (!title || !level) {
      return NextResponse.json(
        { found: false, message: "Title and level are required." },
        { status: 422 }
      );
    }

    const rows = await dbQuery<SwitchRow[]>(
      `SELECT id
       FROM courses
       WHERE title = ? AND level = ? AND is_active = 1
       LIMIT 1`,
      [title, level]
    );

    if (rows.length === 0) {
      return NextResponse.json({
        found: false,
        message: "No course available right now for this level",
      });
    }

    return NextResponse.json({
      found: true,
      url: `/courses/${rows[0].id}`,
    });
  } catch (error) {
    console.error("course/switch-level API error:", error);
    return NextResponse.json(
      { found: false, message: "Unable to switch course level." },
      { status: 500 }
    );
  }
}
