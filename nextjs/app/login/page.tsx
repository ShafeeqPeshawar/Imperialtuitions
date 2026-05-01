import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { LoginForm } from "@/components/login-form";

export const dynamic = "force-dynamic";

export default async function LoginPage() {
  const user = await getCurrentUser();
  if (user) redirect("/dashboard");

  return (
    <main className="hero-section">
      <div className="container">
        <div className="success-box" style={{ width: "min(480px, 92vw)", margin: "0 auto", textAlign: "left" }}>
          <h2 className="success-title">Log in</h2>
          <LoginForm />
          <p style={{ marginTop: 12, fontSize: 14 }}>
            <a href="/forgot-password">Forgot your password?</a> · <a href="/register">Register</a>
          </p>
        </div>
      </div>
    </main>
  );
}
