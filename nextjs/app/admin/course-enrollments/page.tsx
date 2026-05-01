import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminEnrollmentsManager } from "@/components/admin-enrollments-manager";

export const dynamic = "force-dynamic";

type EnrollmentRow = {
  id: number;
  course_name: string;
  registration_type: string | null;
  name: string;
  email: string;
  phone: string | null;
  message: string | null;
  status: "pending" | "approved" | "rejected";
  level: string | null;
  preferred_date: string | null;
  preferred_time: string | null;
  created_at: string;
};

export default async function AdminCourseEnrollmentsPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const [enrollments, countRows] = await Promise.all([
    dbQuery<EnrollmentRow[]>(
      `SELECT id, course_name, registration_type, name, email, phone, message, status, level, preferred_date, preferred_time, created_at
       FROM course_enrollments
       ORDER BY created_at DESC
       LIMIT 20 OFFSET 0`
    ),
    dbQuery<Array<{ total: number }>>("SELECT COUNT(*) as total FROM course_enrollments"),
  ]);

  const total = Number(countRows[0]?.total ?? 0);
  const totalPages = Math.max(1, Math.ceil(total / 20));

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminEnrollmentsManager initialEnrollments={enrollments} initialPagination={{ page: 1, totalPages }} />
    </section>
  );
}
