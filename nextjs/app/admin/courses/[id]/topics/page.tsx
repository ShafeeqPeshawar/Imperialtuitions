import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminCourseTopicsManager } from "@/components/admin-course-topics-manager";

export const dynamic = "force-dynamic";

type CourseRow = { id: number; title: string };
type TopicRow = { id: number; course_id: number; title: string; description: string | null; sort_order: number; is_active: number };

export default async function AdminCourseTopicsPage({ params }: { params: Promise<{ id: string }> }) {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const { id } = await params;

  const [courseRows, topics] = await Promise.all([
    dbQuery<CourseRow[]>("SELECT id, title FROM courses WHERE id = ? LIMIT 1", [id]),
    dbQuery<TopicRow[]>(
      "SELECT id, course_id, title, description, sort_order, is_active FROM course_topics WHERE course_id = ? ORDER BY sort_order ASC, id ASC",
      [id]
    ),
  ]);
  if (!courseRows.length) redirect("/admin/courses");
  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminCourseTopicsManager course={courseRows[0]} initialTopics={topics} />
    </section>
  );
}
