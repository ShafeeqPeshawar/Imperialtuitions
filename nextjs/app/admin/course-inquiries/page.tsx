import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminInquiriesManager } from "@/components/admin-inquiries-manager";

export const dynamic = "force-dynamic";

type InquiryRow = {
  id: number;
  course_title: string;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  level: string | null;
  launch_date: string | null;
  is_viewed: number;
  reply_status: "pending" | "replied";
  created_at: string;
};

export default async function AdminCourseInquiriesPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const [inquiries, countRows] = await Promise.all([
    dbQuery<InquiryRow[]>(
      `SELECT id, course_title, name, email, phone, message, level, launch_date, is_viewed, reply_status, created_at
       FROM course_inquiries
       ORDER BY created_at DESC
       LIMIT 20 OFFSET 0`
    ),
    dbQuery<Array<{ total: number }>>("SELECT COUNT(*) as total FROM course_inquiries"),
  ]);

  const total = Number(countRows[0]?.total ?? 0);
  const totalPages = Math.max(1, Math.ceil(total / 20));

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminInquiriesManager initialInquiries={inquiries} initialPagination={{ page: 1, totalPages }} />
    </section>
  );
}
