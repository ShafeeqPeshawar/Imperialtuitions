import { NextRequest, NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";

type SearchRow = {
  id: number;
  title: string;
  level: string | null;
  duration: string | null;
  price: number | null;
  training_category_id: number | null;
};

export async function GET(request: NextRequest) {
  try {
    const q = request.nextUrl.searchParams.get("q")?.trim() ?? "";

    const rows = await dbQuery<SearchRow[]>(
      `SELECT id, title, level, duration, price, training_category_id
       FROM courses
       WHERE is_active = 1
         AND (? = '' OR title LIKE CONCAT('%', ?, '%') OR skills LIKE CONCAT('%', ?, '%'))
       ORDER BY sort_order ASC, id ASC`,
      [q, q, q]
    );

    return NextResponse.json({ success: true, courses: rows });
  } catch (error) {
    console.error("search-courses API error:", error);
    return NextResponse.json(
      { success: false, message: "Unable to search courses." },
      { status: 500 }
    );
  }
}
