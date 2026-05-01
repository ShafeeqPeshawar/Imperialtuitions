import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { RegisterForm } from "@/components/register-form";

export const dynamic = "force-dynamic";

export default async function RegisterPage() {
  const user = await getCurrentUser();
  if (user) redirect("/dashboard");

  return (
    <main className="hero-section">
      <div className="container">
        <div className="success-box" style={{ width: "min(480px, 92vw)", margin: "0 auto", textAlign: "left" }}>
          <h2 className="success-title">Register</h2>
          <RegisterForm />
        </div>
      </div>
    </main>
  );
}
