import { ResetPasswordForm } from "@/components/reset-password-form";

type Props = {
  params: Promise<{ token: string }>;
  searchParams: Promise<{ email?: string }>;
};

export default async function ResetPasswordPage({ params, searchParams }: Props) {
  const { token } = await params;
  const query = await searchParams;
  const email = query.email ?? "";

  return (
    <main className="hero-section">
      <div className="container">
        <div className="success-box" style={{ width: "min(520px, 92vw)", margin: "0 auto", textAlign: "left" }}>
          <h2 className="success-title">Reset Password</h2>
          <ResetPasswordForm token={token} email={email} />
        </div>
      </div>
    </main>
  );
}
