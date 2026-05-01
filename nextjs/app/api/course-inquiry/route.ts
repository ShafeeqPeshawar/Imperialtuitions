import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { sendMail } from "@/lib/mailer";
import { courseInquirySchema } from "@/lib/validation";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = courseInquirySchema.safeParse(body);

    if (!parsed.success) {
      return NextResponse.json(
        { success: false, message: "Invalid inquiry data." },
        { status: 422 }
      );
    }

    const data = parsed.data;

    const courseExists = await dbQuery<{ id: number }[]>(
      "SELECT id FROM courses WHERE id = ? LIMIT 1",
      [data.course_id]
    );

    if (courseExists.length === 0) {
      return NextResponse.json(
        { success: false, message: "Invalid course selected." },
        { status: 422 }
      );
    }

    const result = (await dbQuery<{ insertId: number }[]>(
      `INSERT INTO course_inquiries
       (course_id, launch_id, course_title, name, email, phone, message, level, launch_date, is_viewed, reply_status, created_at, updated_at)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 'pending', NOW(), NOW())`,
      [
        data.course_id,
        data.launch_id ?? null,
        data.course_title,
        data.name,
        data.email,
        data.phone || null,
        data.message,
        data.level || null,
        data.launch_date || null,
      ]
    )) as unknown as { insertId: number };

    await sendMail({
      to: data.email,
      subject: "Imperial Tuitions - Inquiry Received",
      html: `<p>Hi ${data.name},</p><p>Your inquiry for <strong>${data.course_title}</strong> has been received. Our team will respond within 24 hours.</p>`,
    });

    return NextResponse.json({
      success: true,
      id: result.insertId,
      popup_title: "Inquiry Sent",
      popup_message: "Your inquiry has been received. Our team will respond within 24 hours.",
    });
  } catch (error) {
    console.error("Course inquiry API error:", error);
    return NextResponse.json(
      { success: false, message: "Server error while saving inquiry." },
      { status: 500 }
    );
  }
}
