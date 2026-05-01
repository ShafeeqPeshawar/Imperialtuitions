import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { sendMail } from "@/lib/mailer";
import { contactReceivedEmail } from "@/lib/email-templates";
import { contactSchema } from "@/lib/validation";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = contactSchema.safeParse(body);

    if (!parsed.success) {
      return NextResponse.json(
        { success: false, message: "Invalid contact data." },
        { status: 422 }
      );
    }

    const { name, email, phone, message } = parsed.data;

    const result = (await dbQuery<{ insertId: number }[]>(
      `INSERT INTO contacts (name, email, phone, message, reply_status, is_viewed, created_at, updated_at)
       VALUES (?, ?, ?, ?, 'pending', 0, NOW(), NOW())`,
      [name, email, phone || null, message]
    )) as unknown as { insertId: number };

    await sendMail({
      to: email,
      subject: "Imperial Tuitions - Message Received",
      html: contactReceivedEmail(name, message),
    });

    return NextResponse.json({
      success: true,
      id: result.insertId,
      message: "Thank you for contacting us. We will reach out to you shortly.",
    });
  } catch (error) {
    console.error("Contact API error:", error);
    return NextResponse.json(
      { success: false, message: "Server error while saving contact." },
      { status: 500 }
    );
  }
}
