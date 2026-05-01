import { z } from "zod";

export const contactSchema = z.object({
  name: z.string().trim().min(1).max(255),
  email: z.email(),
  phone: z.string().trim().max(30).optional().or(z.literal("")),
  message: z.string().trim().min(1),
});

export const courseInquirySchema = z.object({
  course_id: z.coerce.number().int().positive(),
  launch_id: z.coerce.number().int().positive().optional(),
  course_title: z.string().trim().min(1),
  name: z.string().trim().min(1).max(255),
  email: z.email(),
  phone: z.string().trim().max(30).optional().or(z.literal("")),
  message: z.string().trim().min(1),
  level: z.string().trim().max(50).optional().or(z.literal("")),
  launch_date: z.string().trim().optional().or(z.literal("")),
});

export const courseEnrollmentSchema = z.object({
  course_id: z.coerce.number().int().positive(),
  launch_id: z.coerce.number().int().positive().optional(),
  course_name: z.string().trim().min(1),
  name: z.string().trim().min(1).max(255),
  email: z.email(),
  phone: z.string().trim().max(30).optional().or(z.literal("")),
  message: z.string().trim().optional().or(z.literal("")),
  level: z.string().trim().max(50).optional().or(z.literal("")),
  preferred_date: z.string().trim().optional().or(z.literal("")),
  preferred_time: z.string().trim().optional().or(z.literal("")),
});

export const subscribeSchema = z.object({
  email: z.email(),
  name: z.string().trim().min(1).max(255),
});
