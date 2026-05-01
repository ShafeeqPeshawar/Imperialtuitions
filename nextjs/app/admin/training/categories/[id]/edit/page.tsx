import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminTrainingCategoryForm } from "@/components/admin-training-category-form";

export default async function EditTrainingCategoryPage({ params }: { params: Promise<{ id: string }> }) {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const { id } = await params;
  const rows = await dbQuery<Array<{ id: number; name: string; sort_order: number }>>(
    "SELECT id, name, sort_order FROM training_categories WHERE id = ? LIMIT 1",
    [id]
  );
  if (!rows.length) redirect("/admin/training/categories");
  return <AdminTrainingCategoryForm mode="edit" category={rows[0]} />;
}
