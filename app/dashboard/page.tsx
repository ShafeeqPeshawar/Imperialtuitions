import { redirect } from "next/navigation";
import Link from "next/link";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";
import { LogoutButton } from "@/components/logout-button";

type CountRow = { count: number };
type ActivityRow = { activity: string; date: string; status: string };

export const dynamic = "force-dynamic";

export default async function DashboardPage() {
  const user = await getCurrentUser();
  if (!user) redirect("/login");

  const enrollments = await dbQuery<CountRow[]>("SELECT COUNT(*) as count FROM course_enrollments");
  const activeCourses = await dbQuery<CountRow[]>("SELECT COUNT(*) as count FROM courses WHERE is_active = 1");
  const pendingInquiries = await dbQuery<CountRow[]>("SELECT COUNT(*) as count FROM course_inquiries WHERE reply_status = 'pending'");
  const pendingContacts = await dbQuery<CountRow[]>("SELECT COUNT(*) as count FROM contacts WHERE reply_status = 'pending'");

  const activities = await dbQuery<ActivityRow[]>(
    `SELECT activity, date, status FROM (
       SELECT CONCAT('New enrollment in ', course_name) as activity, created_at as date, IFNULL(status, 'pending') as status
       FROM course_enrollments
       UNION ALL
       SELECT CONCAT('New course inquiry: ', course_title) as activity, created_at as date, reply_status as status
       FROM course_inquiries
       UNION ALL
       SELECT CONCAT('New contact inquiry from ', name) as activity, created_at as date, reply_status as status
       FROM contacts
     ) a
     ORDER BY date DESC
     LIMIT 5`
  );

  return (
    <main className="courses-section">
      <div className="container">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 20 }}>
          <h2>Dashboard</h2>
          <div style={{ display: "flex", gap: 10, alignItems: "center" }}>
            <span style={{ color: "#64748b" }}>{user.name}</span>
            <LogoutButton />
          </div>
        </div>

        <div className="dashboard-kpi-grid">
          <div className="dashboard-card"><p className="dashboard-card-label">Total Enrollments</p><p className="dashboard-card-value">{enrollments[0]?.count ?? 0}</p></div>
          <div className="dashboard-card"><p className="dashboard-card-label">Active Courses</p><p className="dashboard-card-value">{activeCourses[0]?.count ?? 0}</p></div>
          <div className="dashboard-card"><p className="dashboard-card-label">Inquiries</p><p className="dashboard-card-value">{(pendingInquiries[0]?.count ?? 0) + (pendingContacts[0]?.count ?? 0)}</p></div>
        </div>

        <div className="dashboard-activities-card" style={{ marginTop: 20 }}>
          <h3>Admin Quick Links</h3>
          <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fit, minmax(220px, 1fr))", gap: 10 }}>
            <Link href="/admin/courses" className="admin-btn-sm admin-btn-edit">Courses</Link>
            <Link href="/admin/courses/popular" className="admin-btn-sm admin-btn-edit">Popular Courses</Link>
            <Link href="/admin/course-launches" className="admin-btn-sm admin-btn-edit">Course Launches</Link>
            <Link href="/admin/course-enrollments" className="admin-btn-sm admin-btn-edit">Enrollments</Link>
            <Link href="/admin/course-inquiries" className="admin-btn-sm admin-btn-edit">Inquiries</Link>
            <Link href="/admin/contacts" className="admin-btn-sm admin-btn-edit">Contacts</Link>
            <Link href="/admin/subscribers" className="admin-btn-sm admin-btn-edit">Subscribers</Link>
            <Link href="/admin/training" className="admin-btn-sm admin-btn-edit">Training Gallery</Link>
            <Link href="/admin/training/categories" className="admin-btn-sm admin-btn-edit">Training Categories</Link>
          </div>
        </div>

        <div className="dashboard-activities-card" style={{ marginTop: 28 }}>
          <h3>Recent Activities</h3>
          <table className="dashboard-table">
            <thead><tr><th>Activity</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
              {activities.map((a, i) => (
                <tr key={i}>
                  <td>{a.activity}</td>
                  <td>{new Date(a.date).toLocaleDateString()}</td>
                  <td><span className={a.status === "approved" || a.status === "replied" ? "status-ok" : "status-pending"}>{a.status}</span></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </main>
  );
}
