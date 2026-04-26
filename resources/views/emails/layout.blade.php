<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="color-scheme" content="light dark" />
  <meta name="supported-color-schemes" content="light dark" />
  <title>@yield('title', 'Imperial Tuitions')</title>
  <style>
    html, body { margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important; }
    img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
    a { text-decoration: none; }
    table { border-collapse: collapse !important; }

    .email-page { background: #f4f6f8; }
    .email-outer-cell { padding: 28px 12px; }
    .email-container { width: 600px; max-width: 600px; }
    .email-header-wrap { padding: 0 0 12px 0; }
    .email-header-table { background: #0b1220; border-radius: 14px 14px 0 0; }
    .email-header-cell { padding: 22px 26px; }

    /* UPDATED FONT SIZES */
    .email-brand {
      font-size: 30px !important;
      letter-spacing: .3px;
      color: #cbd5e1;
      font-weight: 600 !important;
      margin-bottom: 6px;
    }
    .email-title {
      margin-top: 6px;
      font-size: 19px !important;
      color: #ffffff;
      font-weight: 600 !important;
      line-height: 1.25;
      margin-bottom: 6px;
    }
    .email-subtitle {
      margin-top: 8px;
      font-size: 14px !important;
      color: #cbd5e1;
      line-height: 1.5;
      margin-bottom: 20px;
    }

    .email-body-table { background: #ffffff; border-radius: 0 0 14px 14px; box-shadow: 0 10px 30px rgba(2,6,23,.08); }
    .email-body-cell { padding: 26px; }
    .email-body-text { font-size: 15px; color: #0f172a; line-height: 1.75; }
    .email-body-text p { margin: 0 0 14px 0; }

    .email-signature { margin-top: 22px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
    .email-signature-line1 { font-size: 14px; color: #0f172a; font-weight: 600; }
    .email-signature-line2 { margin-top: 6px; font-size: 14px; color: #0f172a; font-weight: 700; }
    .email-signature-line3 { margin-top: 2px; font-size: 13px; color: #475569; }

    .email-details-box { margin: 18px 0; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; }
    .email-details-label { font-size: 13px; color: #334155; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .email-details-table { margin-top: 10px; }
    .email-details-key { width: 140px; padding: 6px 0; color: #64748b; font-size: 14px; }
    .email-details-val { padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; }

    .email-message-box { margin: 18px 0; }
    .email-message-label { font-size: 13px; color: #334155; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .email-message-content { margin-top: 10px; padding: 14px 16px; border-left: 4px solid #0b1220; background: #f8fafc; border-radius: 10px; white-space: pre-line; font-size: 14px; color: #0f172a; line-height: 1.7; }

    .email-footer-note { font-size: 12px; color: #64748b; line-height: 1.6; margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
    .email-copyright-cell { padding: 14px 10px; text-align: center; }
    .email-copyright { font-size: 12px; color: #94a3b8; }

    @media (max-width: 600px) {
      .email-container { width: 100% !important; max-width: 100% !important; }
      .email-px { padding-left: 16px !important; padding-right: 16px !important; }
      .email-stack { display: block !important; width: 100% !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; background:#f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">

  @hasSection('preheader')
  <div style="display:none; font-size:5px; color:#f4f6f8; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
    @yield('preheader')
  </div>
  @endif

  <table role="presentation" width="100%" class="email-page">
    <tr>
      <td align="center" class="email-outer-cell">
        <table role="presentation" class="email-container" width="600" style="width:600px; max-width:600px;">
          <!-- Header (dark bar) -->
          <tr>
            <td class="email-header-wrap">
              <table role="presentation" width="100%" class="email-header-table">
                <tr>
                  <td class="email-header-cell email-px" style="padding: 22px 26px;">
                    <div class="email-brand">Imperial Tuitions</div>
                    <div class="email-title">@yield('header_title')</div>
                    <div class="email-subtitle">@yield('header_subtitle')</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Body card (white) -->
          <tr>
            <td>
              <table role="presentation" width="100%" class="email-body-table">
                <tr>
                  <td class="email-body-cell email-px" style="padding: 26px;">
                    <div class="email-body-text">
                      @yield('body')
                    </div>

                    @hasSection('footer_note')
                    <div class="email-footer-note">@yield('footer_note')</div>
                    @endif
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Copyright -->
          <tr>
            <td align="center" class="email-copyright-cell">
              <div class="email-copyright">© {{ date('Y') }} Imperial Tuitions. All rights reserved.</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

</body>
</html>