import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminTopicEditForm } from "@/components/admin-topic-edit-form";

type TopicRow = { id: number; course_id: number; title: string; description: string | null; sort_order: number; is_active: number };

export default async function AdminTopicEditPage({ params }: { params: Promise<{ id: string }> }) {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const { id } = await params;
  const rows = await dbQuery<TopicRow[]>(
    "SELECT id, course_id, title, description, sort_order, is_active FROM course_topics WHERE id = ? LIMIT 1",
    [id]
  );
  if (!rows.length) redirect("/admin/courses");
  return <AdminTopicEditForm topic={rows[0]} />;
}
