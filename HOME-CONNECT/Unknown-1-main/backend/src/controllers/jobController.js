const { pool } = require('../config/db');

function buildJobFilters(query) {
  const sql = [];
  const params = [];
  if (query.status) { sql.push('status = ?'); params.push(query.status); }
  if (query.category) { sql.push('category = ?'); params.push(query.category); }
  if (query.location) { sql.push('location LIKE ?'); params.push(`%${query.location}%`); }
  if (query.q) { sql.push('(title LIKE ? OR description LIKE ?)'); params.push(`%${query.q}%`, `%${query.q}%`); }
  if (query.minBudget) { sql.push('budget_min >= ?'); params.push(Number(query.minBudget)); }
  if (query.maxBudget) { sql.push('budget_max <= ?'); params.push(Number(query.maxBudget)); }
  return { where: sql.length ? `WHERE ${sql.join(' AND ')}` : '', params };
}

async function listJobs(req, res) {
  const page = Math.max(1, Number(req.query.page || 1));
  const pageSize = Math.min(50, Math.max(1, Number(req.query.pageSize || 10)));
  const offset = (page - 1) * pageSize;
  const { where, params } = buildJobFilters(req.query);
  const conn = await pool.getConnection();
  try {
    const [rows] = await conn.query(
      `SELECT id, client_id, title, category, location, budget_min, budget_max, status, created_at
       FROM jobs ${where}
       ORDER BY created_at DESC
       LIMIT ? OFFSET ?`,
      [...params, pageSize, offset]
    );
    res.json({ page, pageSize, results: rows });
  } finally {
    conn.release();
  }
}

async function createJob(req, res) {
  const { title, description, category, location, budget_min, budget_max } = req.body || {};
  if (!title || !description) return res.status(400).json({ error: 'title and description are required' });
  const client_id = req.user?.id || null;
  const conn = await pool.getConnection();
  try {
    const [result] = await conn.query(
      `INSERT INTO jobs (client_id, title, description, category, location, budget_min, budget_max, status)
       VALUES (?, ?, ?, ?, ?, ?, ?, 'open')`,
      [client_id, title, description, category || null, location || null, budget_min || null, budget_max || null]
    );
    res.status(201).json({ id: result.insertId, title, description, category, location, budget_min, budget_max, status: 'open' });
  } finally {
    conn.release();
  }
}

// Get single job by id (public)
async function getJobById(req, res) {
  const id = Number(req.params.id);
  if (!id) return res.status(400).json({ error: 'Invalid id' });
  const conn = await pool.getConnection();
  try {
    const [rows] = await conn.query(
      `SELECT id, client_id, title, description, category, location, budget_min, budget_max, status, created_at
       FROM jobs WHERE id = ?`,
      [id]
    );
    if (!rows.length) return res.status(404).json({ error: 'Job not found' });
    res.json(rows[0]);
  } finally {
    conn.release();
  }
}

// Additional mutations
async function updateJobStatus(req, res) {
  const id = Number(req.params.id);
  const { status } = req.body || {};
  const allowed = new Set(['open','in_progress','completed','cancelled']);
  if (!allowed.has(status)) return res.status(400).json({ error: 'Invalid status' });

  const userId = req.user?.id;
  const conn = await pool.getConnection();
  try {
    // Ensure the job exists and belongs to this client
    const [rows] = await conn.query('SELECT id, client_id FROM jobs WHERE id = ?', [id]);
    if (!rows.length) return res.status(404).json({ error: 'Job not found' });
    const job = rows[0];
    if (job.client_id !== userId) return res.status(403).json({ error: 'Forbidden' });

    await conn.query('UPDATE jobs SET status = ? WHERE id = ?', [status, id]);
    res.json({ id, status });
  } finally {
    conn.release();
  }
}

module.exports = { listJobs, createJob, getJobById, updateJobStatus };

// Apply to a job (provider only)
async function applyToJob(req, res) {
  const jobId = Number(req.params.id);
  if (!jobId) return res.status(400).json({ error: 'Invalid job id' });

  const providerId = req.user?.id;
  const { cover_letter = null, bid_amount = null, timeline_days = null } = req.body || {};

  const conn = await pool.getConnection();
  try {
    // Ensure job exists and is open
    const [jobs] = await conn.query('SELECT id, status FROM jobs WHERE id = ?', [jobId]);
    if (!jobs.length) return res.status(404).json({ error: 'Job not found' });
    if (jobs[0].status !== 'open') return res.status(400).json({ error: 'Job is not open for applications' });

    // Insert proposal; enforce uniqueness per provider per job
    try {
      const [result] = await conn.query(
        `INSERT INTO proposals (job_id, provider_id, cover_letter, bid_amount, timeline_days)
         VALUES (?, ?, ?, ?, ?)`,
        [jobId, providerId, cover_letter, bid_amount != null ? Number(bid_amount) : null, timeline_days != null ? Number(timeline_days) : null]
      );
      return res.status(201).json({ id: result.insertId, job_id: jobId, provider_id: providerId, status: 'submitted' });
    } catch (e) {
      // ER_DUP_ENTRY for unique constraint
      if (e && e.code === 'ER_DUP_ENTRY') {
        return res.status(409).json({ error: 'You have already applied to this job' });
      }
      throw e;
    }
  } finally {
    conn.release();
  }
}

module.exports.applyToJob = applyToJob;
