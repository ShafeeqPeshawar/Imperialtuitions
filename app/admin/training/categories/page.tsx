import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminTrainingCategoriesManager } from "@/components/admin-training-categories-manager";

export const dynamic = "force-dynamic";

export default async function AdminTrainingCategoriesPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const categories = await dbQuery<Array<{ id: number; name: string; slug: string; sort_order: number }>>(
    "SELECT id, name, slug, sort_order FROM training_categories ORDER BY sort_order ASC, id ASC"
  );

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminTrainingCategoriesManager initialCategories={categories} />
    </section>
  );
}
