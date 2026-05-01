import nodemailer from "nodemailer";

function env(name: string, fallback = "") {
  return process.env[name] ?? fallback;
}

const hasMailerConfig =
  Boolean(process.env.MAIL_HOST) &&
  Boolean(process.env.MAIL_PORT) &&
  Boolean(process.env.MAIL_USERNAME) &&
  Boolean(process.env.MAIL_PASSWORD);

let transporter: nodemailer.Transporter | null = null;

function getTransporter() {
  if (!hasMailerConfig) return null;
  if (transporter) return transporter;

  transporter = nodemailer.createTransport({
    host: env("MAIL_HOST"),
    port: Number(env("MAIL_PORT", "587")),
    secure: false,
    auth: {
      user: env("MAIL_USERNAME"),
      pass: env("MAIL_PASSWORD"),
    },
  });

  return transporter;
}

export async function sendMail(input: {
  to: string;
  subject: string;
  html: string;
}) {
  const mailer = getTransporter();
  if (!mailer) return false;

  const fromAddress = env("MAIL_FROM_ADDRESS", env("MAIL_USERNAME"));
  /** Display name shown in recipients' mail clients ("From") */
  const fromName = env("MAIL_FROM_NAME", "Imperial Tuitions");

  await mailer.sendMail({
    from: {
      name: fromName,
      address: fromAddress,
    },
    to: input.to,
    subject: input.subject,
    html: input.html,
  });
  return true;
}

export function isMailerConfigured() {
  return hasMailerConfig;
}
