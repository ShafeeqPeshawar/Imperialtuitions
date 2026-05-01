import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { ForgotPasswordForm } from "@/components/forgot-password-form";

export const dynamic = "force-dynamic";

export default async function ForgotPasswordPage() {
  const user = await getCurrentUser();
  if (user) redirect("/dashboard");

  return (
    <main className="hero-section">
      <div className="container">
        <div className="success-box" style={{ width: "min(520px, 92vw)", margin: "0 auto", textAlign: "left" }}>
          <h2 className="success-title">Forgot Password</h2>
          <p className="success-text">
            Forgot your password? No problem. Enter your email and we will send a password reset link.
          </p>
          <ForgotPasswordForm />
        </div>
      </div>
    </main>
  );
}
