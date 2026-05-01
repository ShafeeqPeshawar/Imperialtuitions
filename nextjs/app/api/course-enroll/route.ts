import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { sendMail } from "@/lib/mailer";
import { enrollmentReceivedEmail } from "@/lib/email-templates";
import { courseEnrollmentSchema } from "@/lib/validation";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = courseEnrollmentSchema.safeParse(body);

    if (!parsed.success) {
      return NextResponse.json(
        { success: false, message: "Invalid enrollment data." },
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
      `INSERT INTO course_enrollments
      (course_id, launch_id, course_name, launch_date, name, email, phone, level, registration_type, preferred_date, preferred_time, message, status, created_at, updated_at)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Individual', ?, ?, ?, 'pending', NOW(), NOW())`,
      [
        data.course_id,
        data.launch_id ?? null,
        data.course_name,
        data.preferred_date || null,
        data.name,
        data.email,
        data.phone || null,
        data.level || null,
        data.preferred_date || null,
        data.preferred_time || null,
        data.message || null,
      ]
    )) as unknown as { insertId: number };

    await sendMail({
      to: data.email,
      subject: "Imperial Tuitions - Registration Received",
      html: enrollmentReceivedEmail(data.name, data.course_name, data.level ?? null, data.message ?? null),
    });

    return NextResponse.json({
      success: true,
      id: result.insertId,
      popup_title: "Enrollment Submitted",
      popup_message:
        "Thank you for enrolling. An Imperial Tuitions coordinator will contact you shortly.",
    });
  } catch (error) {
    console.error("Course enroll API error:", error);
    return NextResponse.json(
      { success: false, message: "Server error while saving enrollment." },
      { status: 500 }
    );
  }
}
