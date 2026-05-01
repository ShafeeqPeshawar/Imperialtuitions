import { AdminCoursesManager } from "@/components/admin-courses-manager";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";
import { redirect } from "next/navigation";

export const dynamic = "force-dynamic";

type CourseRow = {
  id: number;
  title: string;
  description: string | null;
  image: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
  skills: string | null;
  sort_order: number;
  is_active: number;
  is_popular: number;
  training_category_id: number | null;
  category_name: string | null;
};

type Category = { id: number; name: string };

export default async function AdminCoursesPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const [courses, categories] = await Promise.all([
    dbQuery<CourseRow[]>(
      `SELECT c.id, c.title, c.description, c.image, c.level, c.duration, c.price, c.skills, c.sort_order, c.is_active, c.is_popular, c.training_category_id,
              tc.name as category_name
       FROM courses c
       LEFT JOIN training_categories tc ON tc.id = c.training_category_id
       ORDER BY c.sort_order ASC, c.id DESC`
    ),
    dbQuery<Category[]>("SELECT id, name FROM training_categories ORDER BY name ASC"),
  ]);

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminCoursesManager initialCourses={courses} categories={categories} />
    </section>
  );
}
