import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { sendMail } from "@/lib/mailer";
import { subscriberWelcomeEmail } from "@/lib/email-templates";
import { subscribeSchema } from "@/lib/validation";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const parsed = subscribeSchema.safeParse(body);

    if (!parsed.success) {
      return NextResponse.json(
        { success: false, message: "Invalid email or name." },
        { status: 422 }
      );
    }

    const { email, name } = parsed.data;
    const existing = await dbQuery<{ id: number }[]>(
      "SELECT id FROM subscribers WHERE email = ? LIMIT 1",
      [email]
    );

    if (existing.length > 0) {
      return NextResponse.json(
        { success: false, message: "Already subscribed." },
        { status: 409 }
      );
    }

    const result = (await dbQuery<{ insertId: number }[]>(
      "INSERT INTO subscribers (email, created_at, updated_at) VALUES (?, NOW(), NOW())",
      [email]
    )) as unknown as { insertId: number };

    await sendMail({
      to: email,
      subject: "Welcome to Imperial Tuitions",
      html: subscriberWelcomeEmail(name),
    });

    return NextResponse.json({
      success: true,
      id: result.insertId,
      message: "Subscription successful.",
    });
  } catch (error) {
    console.error("Subscribe API error:", error);
    return NextResponse.json(
      { success: false, message: "Server error while subscribing." },
      { status: 500 }
    );
  }
}
