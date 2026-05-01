/**
 * Normalize DB / startup errors returned to browsers on auth-facing routes.
 */
export function apiErrorResponseMessage(error: unknown, fallbackMessage: string): string {
  const rawMessage = error instanceof Error ? error.message : String(error);
  return rawMessage.includes("Missing environment variable:")
    ? `${rawMessage}. Please create/update .env at the project root and restart the dev server.`
    : fallbackMessage;
}
