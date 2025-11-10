const { pool } = require('../config/db');

async function getMyProposals(req, res) {
  const providerId = req.user?.id;
  const conn = await pool.getConnection();
  try {
    const [rows] = await conn.query(
      `SELECT 
         p.id AS proposal_id,
         p.status AS proposal_status,
         p.bid_amount,
         p.timeline_days,
         p.created_at AS proposal_created_at,
         j.id AS job_id,
         j.title,
         j.category,
         j.location,
         j.budget_min,
         j.budget_max,
         j.status AS job_status
       FROM proposals p
       JOIN jobs j ON j.id = p.job_id
       WHERE p.provider_id = ?
       ORDER BY p.created_at DESC`,
      [providerId]
    );
    res.json(rows);
  } finally {
    conn.release();
  }
}

async function getMyActiveProjects(req, res) {
  const providerId = req.user?.id;
  const conn = await pool.getConnection();
  try {
    const [rows] = await conn.query(
      `SELECT 
         p.id AS proposal_id,
         p.status AS proposal_status,
         p.bid_amount,
         p.timeline_days,
         p.created_at AS proposal_created_at,
         j.id AS job_id,
         j.title,
         j.category,
         j.location,
         j.budget_min,
         j.budget_max,
         j.status AS job_status
       FROM proposals p
       JOIN jobs j ON j.id = p.job_id
       WHERE p.provider_id = ? AND p.status = 'accepted'
       ORDER BY p.created_at DESC`,
      [providerId]
    );
    res.json(rows);
  } finally {
    conn.release();
  }
}

module.exports = { getMyProposals, getMyActiveProjects };
