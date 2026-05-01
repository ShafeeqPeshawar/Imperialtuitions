import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { AdminTrainingCategoryForm } from "@/components/admin-training-category-form";

export default async function CreateTrainingCategoryPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");
  return <AdminTrainingCategoryForm mode="create" />;
}
