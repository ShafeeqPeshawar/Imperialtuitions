function escapeHtml(value: string) {
  return value
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function nl2brSafe(value: string) {
  return escapeHtml(value).replace(/\r?\n/g, "<br />");
}

function wrapEmail(title: string, headerTitle: string, headerSubtitle: string, body: string) {
  return `
  <div style="font-family:Inter,Arial,sans-serif;background:#f8fafc;padding:24px;">
    <div style="max-width:700px;margin:0 auto;background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
      <div style="background:linear-gradient(180deg,#09515d 0%,#0a6573 100%);padding:20px;color:#fff;">
        <div style="font-size:22px;font-weight:800;margin-bottom:4px;">${headerTitle}</div>
        <div style="font-size:13px;opacity:0.9;">${headerSubtitle}</div>
      </div>
      <div style="padding:20px;color:#334155;line-height:1.65;">
        <h2 style="font-size:18px;color:#0f172a;margin:0 0 10px;">${title}</h2>
        ${body}
        <div style="margin-top:18px;padding-top:12px;border-top:1px solid #e2e8f0;font-size:13px;color:#64748b;">
          Imperial Tuitions
        </div>
      </div>
    </div>
  </div>`;
}

export function contactReceivedEmail(name: string, message: string) {
  return wrapEmail(
    "Message Received - Imperial Tuitions",
    "Message Received",
    "Thank you for reaching out - we will get back to you soon.",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>Thank you for contacting <strong>Imperial Tuitions</strong>. We have received your message and appreciate the opportunity to assist you.</p>
     <div style="border:1px solid #e2e8f0;padding:12px 14px;border-radius:8px;background:#f8fafc;margin:16px 0;">
       <div style="font-weight:700;margin-bottom:6px;color:#334155;">Your Message</div>
       <div style="white-space:pre-line;line-height:1.6;color:#0f172a;">${nl2brSafe(message)}</div>
     </div>
     <p>Our team will review your message and contact you shortly.</p>
     <p>Thank you for your interest in <strong>Imperial Tuitions</strong>. We look forward to supporting you.</p>`
  );
}

export function contactReplyEmail(name: string, replyMessage: string) {
  return wrapEmail(
    "Message Received - Imperial Tuitions",
    "Regarding Your Message",
    "Thank you for contacting Imperial Tuitions",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>Thank you for contacting <strong>Imperial Tuitions</strong>.</p>
     <div style="background:#f9f9f9;border-left:4px solid #fbbf24;padding:12px 16px;margin:16px 0;border-radius:4px;">
       ${nl2brSafe(replyMessage)}
     </div>
     <p>If you have further questions, reply to this email.</p>`
  );
}

export function inquiryReplyEmail(name: string, courseTitle: string, replyMessage: string) {
  return wrapEmail(
    "Regarding Your Message - Imperial Tuitions",
    "Regarding Your Message",
    "Thank you for contacting Imperial Tuitions",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>We received your inquiry regarding <strong>${escapeHtml(courseTitle)}</strong>.</p>
     <div style="background:#f9f9f9;border-left:4px solid #fbbf24;padding:12px 16px;margin:16px 0;border-radius:4px;">
       ${nl2brSafe(replyMessage)}
     </div>
     <p>If you have any further questions, reply to this email.</p>`
  );
}

export function inquiryReceivedEmail(name: string, courseTitle: string, level: string | null, message: string) {
  const safeLevel = level && level.trim().length > 0 ? escapeHtml(level) : "-";
  const safeMessage = nl2brSafe(message);
  return wrapEmail(
    "Inquiry Received - Imperial Tuitions",
    "Inquiry Received",
    "Thank you for reaching out - we will respond shortly",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>Thank you for contacting <strong>Imperial Tuitions</strong>. We have received your inquiry.</p>
     <table role="presentation" width="100%" style="border-collapse:collapse;margin:10px 0 12px;">
       <tr>
         <td style="font-weight:700;color:#64748b;padding:4px 0;width:120px;">Course</td>
         <td style="font-weight:600;color:#0f172a;padding:4px 0;">${escapeHtml(courseTitle)}</td>
       </tr>
       <tr>
         <td style="font-weight:700;color:#64748b;padding:4px 0;">Level</td>
         <td style="font-weight:600;color:#0f172a;padding:4px 0;">${safeLevel}</td>
       </tr>
     </table>
     <p style="font-size:14px;font-weight:700;color:#0f172a;margin:8px 0 6px;">Your Inquiry</p>
     <div style="margin:6px 0 14px;padding:12px 14px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:8px;color:#0f172a;line-height:1.6;">
       ${safeMessage}
     </div>
     <p>Our team will review this and respond within 24 hours.</p>`
  );
}

export function enrollmentReplyEmail(name: string, courseName: string, replyMessage: string) {
  return wrapEmail(
    "Enrollment Update - Imperial Tuitions",
    "Enrollment Update",
    "We are here to assist you",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>${nl2brSafe(replyMessage)}</p>
     <p>If you need help regarding your enrollment for <strong>${escapeHtml(courseName)}</strong>, reply to this email.</p>`
  );
}

export function enrollmentApprovedEmail(name: string, courseName: string, level: string | null) {
  const safeLevel = level && level.trim().length > 0 ? escapeHtml(level) : "-";
  return wrapEmail(
    "Enrollment Approved - Imperial Tuitions",
    "Enrollment Approved",
    "Thank you for your interest - your enrollment is now confirmed!",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>We're pleased to inform you that your enrollment has been approved.</p>
     <div style="background:#f9f9f9;border-left:4px solid #fbbf24;padding:12px 16px;margin:16px 0;border-radius:4px;">
       <p style="margin:0;">
         Course: ${escapeHtml(courseName)}<br />
         Level: ${safeLevel}
       </p>
     </div>
     <p>Our team will contact you shortly with further information regarding schedule, access, and payment.</p>
     <p>If you have any questions in the meantime, feel free to reply to this email.</p>`
  );
}

export function enrollmentRejectedEmail(name: string, courseName: string) {
  return wrapEmail(
    "Enrollment Update - Imperial Tuitions",
    "Enrollment Update",
    "Thank you for your interest in our training programs",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>Thank you for your interest in the <strong>${escapeHtml(courseName)}</strong> training program.</p>
     <p>After reviewing your registration, we are unable to proceed with your enrollment for this session at this time.</p>
     <p>This may be due to limited seat availability, scheduling constraints, or eligibility requirements for the selected batch.</p>
     <p>We appreciate your interest in <strong>Imperial Tuitions Training</strong> and encourage you to explore upcoming sessions.</p>
     <p>If you have any questions or would like further clarification, please feel free to reply to this email.</p>`
  );
}

export function enrollmentReceivedEmail(
  name: string,
  courseName: string,
  level: string | null,
  message: string | null | undefined
) {
  const safeLevel = level && level.trim().length > 0 ? escapeHtml(level) : "-";
  const safeMessage =
    message && message.trim().length > 0
      ? nl2brSafe(message)
      : "No additional message provided.";

  return wrapEmail(
    "Registration Received - Imperial Tuitions",
    "Registration Received",
    "Thank you for your interest - we will be in touch shortly.",
    `<p>Dear <strong>${escapeHtml(name)}</strong>,</p>
     <p>Thank you for contacting <strong>Imperial Tuitions</strong> and for your interest in our training programs.
     We have successfully received your registration.</p>
     <div style="margin:18px 0;padding:14px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
       <div style="font-weight:700;color:#0f172a;margin-bottom:8px;">Course Details</div>
       <table role="presentation" width="100%" style="border-collapse:collapse;font-size:14px;">
         <tr>
           <td style="width:120px;padding:6px 0;color:#64748b;">Course</td>
           <td style="padding:6px 0;color:#0f172a;font-weight:600;">${escapeHtml(courseName)}</td>
         </tr>
         <tr>
           <td style="padding:6px 0;color:#64748b;">Level</td>
           <td style="padding:6px 0;color:#0f172a;font-weight:600;">${safeLevel}</td>
         </tr>
       </table>
     </div>
     <p style="font-size:14px;font-weight:700;color:#0f172a;margin:8px 0 6px;">Your Message</p>
     <div style="margin:6px 0 14px;padding:12px 14px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:8px;color:#0f172a;line-height:1.6;">
       ${safeMessage}
     </div>
     <p>Our team will contact you shortly to confirm availability, schedule, and payment details.</p>
     <p>We look forward to supporting your professional development journey.</p>`
  );
}

export function subscriberBroadcastEmail(name: string, content: string) {
  return wrapEmail(
    "Message from Imperial Tuitions",
    "You Have a New Message!",
    "Stay updated with the latest from Imperial Tuitions",
    `<p>Dear ${escapeHtml(name)},</p>
     <p>We are excited to share the following message with you:</p>
     <div style="border:1px solid #ddd;padding:10px;border-radius:5px;margin-top:15px;">
       ${nl2brSafe(content)}
     </div>`
  );
}

export function subscriberWelcomeEmail(name: string) {
  return wrapEmail(
    "Welcome to Imperial Tuitions",
    "Subscription Confirmed",
    "You are now subscribed to Imperial Tuitions updates",
    `<p>Dear ${escapeHtml(name)},</p>
     <p>Thank you for subscribing to <strong>Imperial Tuitions</strong>.</p>
     <p>You will now receive updates about our latest training programs, free workshops, special offers, and technology insights.</p>
     <p>We're excited to stay connected and help you grow professionally.</p>`
  );
}

export function freeCourseLaunchEmail(courseTitle: string, launchDate: string, courseUrl: string) {
  return wrapEmail(
    "New Free Course Launched!",
    "New FREE Course Launched!",
    "A new free learning opportunity is now available",
    `<p>Great news! We are launching a brand new <strong>FREE</strong> course:</p>
     <p style="font-size:18px;font-weight:700;margin:8px 0;color:#0f172a;">${escapeHtml(courseTitle)}</p>
     <p>Launch Date: <strong>${escapeHtml(launchDate)}</strong></p>
     <p>This course is completely free and beginner-friendly.</p>
     <p style="margin-top:16px;">
       <a href="${escapeHtml(courseUrl)}" style="display:inline-block;padding:12px 20px;background:#09515D;color:#fff;text-decoration:none;border-radius:8px;">
         View Course
       </a>
     </p>`
  );
}
