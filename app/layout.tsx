import type { Metadata } from "next";
import Link from "next/link";
import { Inter } from "next/font/google";
import "./globals.css";

const inter = Inter({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700", "800", "900"],
});

export const metadata: Metadata = {
  title: "Imperial Tuitions",
  description: "Imperial Tuitions - Next.js migration",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={inter.className}>
      <head>
        <link
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
        />
        <link
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
          rel="stylesheet"
        />
        <link
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          rel="stylesheet"
        />
      </head>
      <body>
        <header className="main-header">
          <div className="container">
            <div className="header-content">
              <h2>Imperial Tuitions</h2>
              <nav className="main-nav">
                <ul>
                  <li>
                    <Link href="/#home">Home</Link>
                  </li>
                  <li>
                    <Link href="/#courses">Courses</Link>
                  </li>
                  <li>
                    <Link href="/#about">We Offer</Link>
                  </li>
                  <li>
                    <Link href="/#contact">Get Notified</Link>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </header>
        {children}
      </body>
    </html>
  );
}
