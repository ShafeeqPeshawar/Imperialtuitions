import mysql from "mysql2/promise";

const globalForPool = globalThis as typeof globalThis & {
  __imperialMysqlPool?: mysql.Pool;
};

function requiredEnv(name: string): string {
  const value = process.env[name];
  if (!value) {
    throw new Error(`Missing environment variable: ${name}`);
  }
  return value;
}

function isTooManyConnections(err: unknown): boolean {
  const e = err as { code?: string; errno?: number; message?: string };
  return (
    e?.code === "ER_CON_COUNT_ERROR" ||
    e?.errno === 1040 ||
    (typeof e?.message === "string" && e.message.includes("Too many connections"))
  );
}

export function getDbPool() {
  if (globalForPool.__imperialMysqlPool) return globalForPool.__imperialMysqlPool;

  globalForPool.__imperialMysqlPool = mysql.createPool({
    host: requiredEnv("DB_HOST"),
    port: Number(process.env.DB_PORT ?? 3306),
    user: requiredEnv("DB_USERNAME"),
    password: process.env.DB_PASSWORD ?? "",
    database: requiredEnv("DB_DATABASE"),
    waitForConnections: true,
    connectionLimit: Number(process.env.DB_POOL_LIMIT ?? 3),
    queueLimit: 0,
  });

  return globalForPool.__imperialMysqlPool;
}

export async function dbQuery<T>(sql: string, params: unknown[] = []): Promise<T> {
  const maxAttempts = 4;
  let lastError: unknown;
  for (let attempt = 1; attempt <= maxAttempts; attempt++) {
    try {
      const [rows] = await getDbPool().query(sql, params);
      return rows as T;
    } catch (err) {
      lastError = err;
      if (isTooManyConnections(err) && attempt < maxAttempts) {
        await new Promise((r) => setTimeout(r, 300 * attempt));
        continue;
      }
      throw err;
    }
  }
  throw lastError;
}
