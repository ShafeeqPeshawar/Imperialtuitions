import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminTrainingImageForm } from "@/components/admin-training-image-form";

export default async function CreateTrainingImagePage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  const categories = await dbQuery<Array<{ id: number; name: string }>>(
    "SELECT id, name FROM training_categories ORDER BY sort_order ASC, id ASC"
  );
  return <AdminTrainingImageForm mode="create" categories={categories} />;
}
