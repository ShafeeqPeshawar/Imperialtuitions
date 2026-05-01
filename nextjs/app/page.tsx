import Image from "next/image";
import { dbQuery } from "@/lib/db";
import { NewsletterForm } from "@/components/newsletter-form";
import { CourseBrowser } from "@/components/course-browser";
import { ContactHeroButton } from "@/components/contact-hero-button";
import { HomeOffer } from "@/components/home-offer";

export const dynamic = "force-dynamic";

type CourseRow = {
  id: number;
  title: string;
  description: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
  skills: string | null;
  training_category_id: number | null;
};

type CategoryRow = {
  id: number;
  name: string;
};

function dbMessage(error: unknown): string {
  return error instanceof Error ? error.message : "Database query failed.";
}

function orderByUnknownColumn(message: string, column: string): boolean {
  return message.includes("Unknown column") && message.includes(column);
}

export default async function Home() {
  let courses: CourseRow[] = [];
  let categories: CategoryRow[] = [];
  let catalogLoadError: string | null = null;

  const coursesSqlOrderSort = `SELECT id, title, description, level, duration, price, skills, training_category_id
       FROM courses
       WHERE is_active = 1
       ORDER BY sort_order ASC, id ASC`;
  const coursesSqlOrderId = `SELECT id, title, description, level, duration, price, skills, training_category_id
       FROM courses
       WHERE is_active = 1
       ORDER BY id ASC`;

  try {
    courses = await dbQuery<CourseRow[]>(coursesSqlOrderSort);
  } catch (error) {
    const msg = dbMessage(error);
    if (orderByUnknownColumn(msg, "sort_order")) {
      try {
        courses = await dbQuery<CourseRow[]>(coursesSqlOrderId);
      } catch (e2) {
        console.warn("Homepage courses fetch failed:", e2);
        catalogLoadError = dbMessage(e2);
      }
    } else {
      console.warn("Homepage courses fetch failed:", error);
      catalogLoadError = msg;
    }
  }

  const categoriesSqlOrderSort = `SELECT id, name
       FROM training_categories
       ORDER BY sort_order ASC, id ASC`;
  const categoriesSqlOrderId = `SELECT id, name
       FROM training_categories
       ORDER BY id ASC`;

  try {
    categories = await dbQuery<CategoryRow[]>(categoriesSqlOrderSort);
  } catch (error) {
    const msg = dbMessage(error);
    if (orderByUnknownColumn(msg, "sort_order")) {
      try {
        categories = await dbQuery<CategoryRow[]>(categoriesSqlOrderId);
      } catch (e2) {
        console.warn("Homepage categories fetch failed:", e2);
        if (!catalogLoadError) catalogLoadError = dbMessage(e2);
      }
    } else {
      console.warn("Homepage categories fetch failed:", error);
      if (!catalogLoadError) catalogLoadError = msg;
    }
  }

  return (
    <>
      <section className="hero-section" id="home">
        <div className="container">
          <div className="hero-content">
            <div className="hero-text">
              <h1>Empowering IT Skills, Transforming Futures</h1>
              <p>
                Learn in-demand IT skills from expert instructors. Join our online and in-person tuition
                programs and build your future in tech!
              </p>
              <div style={{ display: "flex", gap: 12 }}>
                <a className="btn-primary" href="#courses">Browse Courses</a>
                <ContactHeroButton />
              </div>
            </div>
            <div className="hero-image">
              <Image
                src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800"
                alt="Students learning"
                width={800}
                height={533}
                priority
                style={{ width: "100%", height: "auto" }}
              />
            </div>
          </div>
        </div>
      </section>

      <HomeOffer />

      <section className="courses-section" id="courses">
        <div className="container">
          <h2>Courses</h2>
          {catalogLoadError ? (
            <div
              role="alert"
              style={{
                marginBottom: 20,
                padding: "14px 18px",
                borderRadius: 12,
                background: "#fef3c7",
                border: "1px solid #f59e0b",
                color: "#78350f",
                fontSize: 15,
                lineHeight: 1.55,
              }}
            >
              <strong style={{ display: "block", marginBottom: 6 }}>Could not load data from MySQL</strong>
              <span style={{ wordBreak: "break-word" }}>{catalogLoadError}</span>
              <span style={{ display: "block", marginTop: 10, fontSize: 14, opacity: 0.95 }}>
                If you see &quot;Too many connections&quot;, restart MySQL or stop extra dev servers, then raise{" "}
                <code style={{ fontSize: 13 }}>max_connections</code> in MySQL config. Next.js uses a small pool (
                <code style={{ fontSize: 13 }}>DB_POOL_LIMIT</code>, default 3).
              </span>
            </div>
          ) : null}
          <CourseBrowser
            initialCourses={courses}
            categories={categories}
            catalogLoadError={catalogLoadError}
          />
        </div>
      </section>

      <section className="newsletter-cta-section" id="contact">
        <div className="container">
          <div className="newsletter-content">
            <div className="newsletter-text">
              <h2>Join us to Grow Skills, Together!</h2>
              <p>Stay informed about new courses and special offers</p>
            </div>
            <div className="newsletter-form">
              <NewsletterForm />
            </div>
          </div>
        </div>
      </section>

      <footer className="main-footer">
        <div className="container">
          <p>&copy; 2024 Imperial Tuitions. All rights reserved.</p>
        </div>
      </footer>
    </>
  );
}
