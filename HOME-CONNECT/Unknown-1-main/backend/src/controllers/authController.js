const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { pool } = require('../config/db');

function signToken(user) {
  const payload = { id: user.id, email: user.email, role: user.role };
  const secret = process.env.JWT_SECRET || 'dev_secret';
  const expiresIn = process.env.JWT_EXPIRES_IN || '7d';
  return jwt.sign(payload, secret, { expiresIn });
}

async function register(req, res) {
  const { name, email, password, role = 'client' } = req.body || {};
  if (!name || !email || !password) {
    return res.status(400).json({ error: 'name, email and password are required' });
  }
  if (!['client', 'provider'].includes(role)) {
    return res.status(400).json({ error: 'role must be client or provider' });
  }
  const conn = await pool.getConnection();
  try {
    const [existing] = await conn.query('SELECT id FROM users WHERE email = ?', [email]);
    if (existing.length) return res.status(409).json({ error: 'Email already registered' });

    const password_hash = await bcrypt.hash(password, 10);
    const [result] = await conn.query(
      'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)',
      [name, email, password_hash, role]
    );
    const user = { id: result.insertId, name, email, role };
    const token = signToken(user);
    res.status(201).json({ user, token });
  } finally {
    conn.release();
  }
}

async function login(req, res) {
  const { email, password } = req.body || {};
  if (!email || !password) return res.status(400).json({ error: 'email and password are required' });
  const conn = await pool.getConnection();
  try {
    const [rows] = await conn.query('SELECT id, name, email, password_hash, role FROM users WHERE email = ?', [email]);
    if (!rows.length) return res.status(401).json({ error: 'Invalid credentials' });
    const user = rows[0];
    const ok = await bcrypt.compare(password, user.password_hash);
    if (!ok) return res.status(401).json({ error: 'Invalid credentials' });
    delete user.password_hash;
    const token = signToken(user);
    res.json({ user, token });
  } finally {
    conn.release();
  }
}

async function verifyToken(req, res) {
  // auth middleware populates req.user
  res.json({ user: req.user });
}

module.exports = { register, login, verifyToken };
