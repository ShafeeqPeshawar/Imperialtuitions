import { notFound } from "next/navigation";
import { dbQuery } from "@/lib/db";
import { CourseDetailsClient } from "@/components/course-details-client";

type Props = {
  params: Promise<{ id: string }>;
};

type CourseRow = {
  id: number;
  title: string;
  description: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
  training_category_id: number | null;
};

type TopicRow = {
  id: number;
  title: string;
  description: string | null;
};

type RelatedRow = {
  id: number;
  title: string;
  description: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
};

export const dynamic = "force-dynamic";

export default async function CourseDetailsPage({ params }: Props) {
  const { id } = await params;

  const courses = await dbQuery<CourseRow[]>(
    `SELECT id, title, description, level, duration, price, training_category_id
     FROM courses
     WHERE id = ? AND is_active = 1
     LIMIT 1`,
    [id]
  );

  if (courses.length === 0) {
    notFound();
  }

  const course = courses[0];

  const topics = await dbQuery<TopicRow[]>(
    `SELECT id, title, description
     FROM course_topics
     WHERE course_id = ? AND is_active = 1
     ORDER BY sort_order ASC, id ASC`,
    [course.id]
  );

  const relatedCourses = await dbQuery<RelatedRow[]>(
    `SELECT id, title, description, level, duration, price
     FROM courses
     WHERE is_active = 1
       AND id != ?
       AND training_category_id <=> ?
     ORDER BY sort_order ASC, id ASC
     LIMIT 12`,
    [course.id, course.training_category_id]
  );

  return <CourseDetailsClient course={{ ...course, topics }} relatedCourses={relatedCourses} />;
}
