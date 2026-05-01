import { AdminCourseLaunchesManager } from "@/components/admin-course-launches-manager";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";
import { redirect } from "next/navigation";

export const dynamic = "force-dynamic";

type LaunchRow = { id: number; course_id: number; launch_date: string; course_title: string; level: string | null };
type CourseRow = { id: number; title: string; level: string | null; price: number | null };

export default async function AdminCourseLaunchesPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const [launches, freeCourses] = await Promise.all([
    dbQuery<LaunchRow[]>(
      `SELECT cl.id, cl.course_id, cl.launch_date, c.title as course_title, c.level
       FROM course_launches cl
       JOIN courses c ON c.id = cl.course_id
       WHERE c.price = 0
       ORDER BY cl.launch_date ASC`
    ),
    dbQuery<CourseRow[]>("SELECT id, title, level, price FROM courses WHERE price = 0 ORDER BY title ASC"),
  ]);

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminCourseLaunchesManager initialLaunches={launches} freeCourses={freeCourses} />
    </section>
  );
}
