export const metadata = {
  title: "Privacy Policy | Imperial Tuitions",
};

export default function PrivacyPage() {
  return (
    <main>
      <section style={{ background: "#f6f8fb", padding: "60px 0", textAlign: "center" }}>
        <h1 style={{ fontSize: 30, fontWeight: 700, color: "#1f2933" }}>Privacy Policy</h1>
        <p style={{ fontSize: 14, color: "#6b7280", maxWidth: 640, margin: "12px auto 0" }}>
          Your privacy matters. This page explains how Imperial Tuitions collects, uses, and protects your information.
        </p>
      </section>
      <section className="container" style={{ maxWidth: 900, margin: "60px auto", color: "#374151", lineHeight: 1.7 }}>
        <h2 style={{ fontSize: 32, fontWeight: 700, marginBottom: 20, color: "#111827" }}>Privacy Policy - Imperial Tuitions</h2>
        <h3 style={{ fontSize: 18, fontWeight: 700, marginTop: 20, color: "#09515D" }}>1. Information We Collect</h3>
        <p>We may collect student/guardian name, email, phone, location, course inquiries, and website usage data.</p>
        <h3 style={{ fontSize: 18, fontWeight: 700, marginTop: 20, color: "#09515D" }}>2. How We Use Your Information</h3>
        <p>We use your information to process enrollments, provide educational services, communicate updates, and improve quality.</p>
        <h3 style={{ fontSize: 18, fontWeight: 700, marginTop: 20, color: "#09515D" }}>3. Data Sharing</h3>
        <p>We do not sell personal data. Limited data may be shared with trusted providers for payments, communication, and analytics.</p>
        <h3 style={{ fontSize: 18, fontWeight: 700, marginTop: 20, color: "#09515D" }}>4. Contact</h3>
        <p>
          For privacy questions: <strong>btmgusa@gmail.com</strong>
        </p>
      </section>
    </main>
  );
}
