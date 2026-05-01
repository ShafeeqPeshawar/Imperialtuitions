import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { dbQuery } from "@/lib/db";
import { AdminContactsManager } from "@/components/admin-contacts-manager";

export const dynamic = "force-dynamic";

type ContactRow = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  reply_status: "pending" | "replied";
  is_viewed: number;
  created_at: string;
};

export default async function AdminContactsPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const [contacts, countRows] = await Promise.all([
    dbQuery<ContactRow[]>(
      `SELECT id, name, email, phone, message, reply_status, is_viewed, created_at
       FROM contacts
       ORDER BY created_at DESC
       LIMIT 20 OFFSET 0`
    ),
    dbQuery<Array<{ total: number }>>("SELECT COUNT(*) as total FROM contacts"),
  ]);

  const total = Number(countRows[0]?.total ?? 0);
  const totalPages = Math.max(1, Math.ceil(total / 20));

  return (
    <section className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <AdminContactsManager initialContacts={contacts} initialPagination={{ page: 1, totalPages }} />
    </section>
  );
}
