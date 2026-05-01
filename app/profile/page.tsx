import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { LogoutButton } from "@/components/logout-button";
import { ProfileForms } from "@/components/profile-forms";

export const dynamic = "force-dynamic";

export default async function ProfilePage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  return (
    <main className="courses-section">
      <div className="container">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 20 }}>
          <h2>Profile</h2>
          <LogoutButton />
        </div>
        <ProfileForms initialName={user.name} initialEmail={user.email} />
      </div>
    </main>
  );
}
