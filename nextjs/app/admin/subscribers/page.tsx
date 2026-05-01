import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminSubscribersManager } from "@/components/admin-subscribers-manager";

export const dynamic = "force-dynamic";

type SubscriberRow = { id: number; email: string; created_at: string };

export default async function AdminSubscribersPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const subscribers = await dbQuery<SubscriberRow[]>(
    "SELECT id, email, created_at FROM subscribers ORDER BY created_at DESC"
  );

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminSubscribersManager initialSubscribers={subscribers} />
    </section>
  );
}
