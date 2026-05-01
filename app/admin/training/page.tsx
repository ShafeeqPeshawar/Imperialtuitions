import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminTrainingGalleryManager } from "@/components/admin-training-gallery-manager";

export const dynamic = "force-dynamic";

type CategoryRow = { id: number; name: string; sort_order: number };
type ImageRow = { id: number; training_category_id: number; image: string };

export default async function AdminTrainingPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const [categories, images] = await Promise.all([
    dbQuery<CategoryRow[]>("SELECT id, name, sort_order FROM training_categories ORDER BY sort_order ASC, id ASC"),
    dbQuery<ImageRow[]>("SELECT id, training_category_id, image FROM training_images ORDER BY id DESC"),
  ]);

  const grouped = categories.map((c) => ({
    id: c.id,
    name: c.name,
    images: images.filter((i) => i.training_category_id === c.id),
  }));

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminTrainingGalleryManager initialCategories={grouped} />
    </section>
  );
}
