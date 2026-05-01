import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { isMailerConfigured, sendMail } from "@/lib/mailer";
import { inquiryReceivedEmail } from "@/lib/email-templates";
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

    const mailConfigured = isMailerConfigured();
    if (!mailConfigured) {
      return NextResponse.json(
        {
          success: false,
          id: result.insertId,
          message:
            "Inquiry saved, but email service is not configured. Set MAIL_PASSWORD in nextjs/.env and restart dev server.",
        },
        { status: 503 }
      );
    }

    try {
      await sendMail({
        to: data.email,
        subject: "Imperial Tuitions - Inquiry Received",
        html: inquiryReceivedEmail(data.name, data.course_title, data.level ?? null, data.message),
      });
    } catch (mailError) {
      console.error("Inquiry mail send error:", mailError);
      return NextResponse.json(
        {
          success: false,
          id: result.insertId,
          message: "Inquiry saved, but failed to send email copy. Check Gmail App Password and SMTP settings.",
        },
        { status: 502 }
      );
    }

    return NextResponse.json({
      success: true,
      id: result.insertId,
      popup_title: "Inquiry Sent",
      popup_message: "Your inquiry has been received. A copy has been emailed to you.",
      mail_sent: true,
      mail_configured: true,
    });
  } catch (error) {
    console.error("Course inquiry API error:", error);
    return NextResponse.json(
      { success: false, message: "Server error while saving inquiry." },
      { status: 500 }
    );
  }
}
