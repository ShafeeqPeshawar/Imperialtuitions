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

export function contactReplyEmail(name: string, replyMessage: string) {
  return wrapEmail(
    "Message Received - Imperial Tuitions",
    "Regarding Your Message",
    "Thank you for contacting Imperial Tuitions",
    `<p>Dear <strong>${name}</strong>,</p>
     <p>Thank you for contacting <strong>Imperial Tuitions</strong>.</p>
     <div style="background:#f9f9f9;border-left:4px solid #fbbf24;padding:12px 16px;margin:16px 0;border-radius:4px;">
       ${replyMessage}
     </div>
     <p>If you have further questions, reply to this email.</p>`
  );
}

export function inquiryReplyEmail(name: string, courseTitle: string, replyMessage: string) {
  return wrapEmail(
    "Regarding Your Message - Imperial Tuitions",
    "Regarding Your Message",
    "Thank you for contacting Imperial Tuitions",
    `<p>Dear <strong>${name}</strong>,</p>
     <p>We received your inquiry regarding <strong>${courseTitle}</strong>.</p>
     <div style="background:#f9f9f9;border-left:4px solid #fbbf24;padding:12px 16px;margin:16px 0;border-radius:4px;">
       ${replyMessage}
     </div>
     <p>If you have any further questions, reply to this email.</p>`
  );
}

export function enrollmentReplyEmail(name: string, courseName: string, replyMessage: string) {
  return wrapEmail(
    "Enrollment Update - Imperial Tuitions",
    "Enrollment Update",
    "We are here to assist you",
    `<p>Dear <strong>${name}</strong>,</p>
     <p>${replyMessage}</p>
     <p>If you need help regarding your enrollment for <strong>${courseName}</strong>, reply to this email.</p>`
  );
}

export function subscriberBroadcastEmail(name: string, content: string) {
  return wrapEmail(
    "Message from Imperial Tuitions",
    "You Have a New Message!",
    "Stay updated with the latest from Imperial Tuitions",
    `<p>Dear ${name},</p>
     <p>We are excited to share the following message with you:</p>
     <div style="border:1px solid #ddd;padding:10px;border-radius:5px;margin-top:15px;">
       ${content}
     </div>`
  );
}
