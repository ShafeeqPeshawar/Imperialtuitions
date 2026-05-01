import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminPopularCoursesManager } from "@/components/admin-popular-courses-manager";

export const dynamic = "force-dynamic";

type Row = { id: number; title: string; is_popular: number };

export default async function AdminPopularCoursesPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const courses = await dbQuery<Row[]>("SELECT id, title, is_popular FROM courses WHERE is_popular = 1 ORDER BY sort_order ASC, id DESC");
  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminPopularCoursesManager initialCourses={courses} />
    </section>
  );
}
