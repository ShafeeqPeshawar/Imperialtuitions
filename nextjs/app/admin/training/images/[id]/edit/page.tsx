import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminTrainingImageForm } from "@/components/admin-training-image-form";

export default async function EditTrainingImagePage({ params }: { params: Promise<{ id: string }> }) {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const { id } = await params;

  const [categories, rows] = await Promise.all([
    dbQuery<Array<{ id: number; name: string }>>("SELECT id, name FROM training_categories ORDER BY sort_order ASC, id ASC"),
    dbQuery<Array<{ id: number; training_category_id: number; image: string }>>(
      "SELECT id, training_category_id, image FROM training_images WHERE id = ? LIMIT 1",
      [id]
    ),
  ]);

  if (!rows.length) redirect("/admin/training");
  return <AdminTrainingImageForm mode="edit" categories={categories} image={rows[0]} />;
}
