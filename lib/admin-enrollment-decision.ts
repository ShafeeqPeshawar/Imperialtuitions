import { dbQuery } from "@/lib/db";
import { sendMail } from "@/lib/mailer";
import { enrollmentApprovedEmail, enrollmentRejectedEmail } from "@/lib/email-templates";

export type EnrollmentDecision = "approved" | "rejected";

/**
 * Applies enrollment status update and notifies the trainee by email when possible.
 */
export async function applyEnrollmentDecision(
  enrollmentId: string,
  decision: EnrollmentDecision
): Promise<{ message: string }> {
  if (decision === "approved") {
    await dbQuery("UPDATE course_enrollments SET status = 'approved', updated_at = NOW() WHERE id = ?", [
      enrollmentId,
    ]);
    const rows = await dbQuery<{ name: string; email: string; course_name: string; level: string | null }[]>(
      "SELECT name, email, course_name, level FROM course_enrollments WHERE id = ? LIMIT 1",
      [enrollmentId]
    );
    const row = rows[0];
    if (row?.email) {
      await sendMail({
        to: row.email,
        subject: "Imperial Tuitions Training - Enrollment Approved",
        html: enrollmentApprovedEmail(row.name, row.course_name, row.level),
      });
    }
    return { message: "Enrollment approved." };
  }

  await dbQuery("UPDATE course_enrollments SET status = 'rejected', updated_at = NOW() WHERE id = ?", [
    enrollmentId,
  ]);
  const rows = await dbQuery<{ name: string; email: string; course_name: string }[]>(
    "SELECT name, email, course_name FROM course_enrollments WHERE id = ? LIMIT 1",
    [enrollmentId]
  );
  const row = rows[0];
  if (row?.email) {
    await sendMail({
      to: row.email,
      subject: "Imperial Tuitions Training - Enrollment Update",
      html: enrollmentRejectedEmail(row.name, row.course_name),
    });
  }
  return { message: "Enrollment rejected." };
}
