# 🚀 Backend Setup & Database Connection Guide

## Complete Implementation for Making Your Site Interactive & Dynamic

This guide provides step-by-step instructions to:
1. Set up a Node.js backend server
2. Connect to a MySQL database
3. Create API endpoints that your JavaScript connects to
4. Make your frontend fully interactive with real data

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Prerequisites & Installation](#prerequisites--installation)
3. [Database Setup (MySQL)](#database-setup-mysql)
4. [Backend Server Setup (Node.js + Express)](#backend-server-setup-nodejs--express)
5. [API Endpoints Implementation](#api-endpoints-implementation)
6. [Authentication System](#authentication-system)
7. [Testing the Integration](#testing-the-integration)
8. [Deployment](#deployment)

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONTEND (Browser)                       │
│  HTML + CSS + JavaScript (common.js)                       │
│  • All pages work with API calls                           │
│  • fetch() sends/receives JSON                             │
└────────────────────┬────────────────────────────────────────┘
                     │
          HTTP Requests (JSON)
                     │
                     ↓
┌─────────────────────────────────────────────────────────────┐
│              BACKEND (Node.js + Express)                    │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ API Routes & Controllers                             │  │
│  │ • GET    /api/jobs                 → Fetch jobs      │  │
│  │ • POST   /api/jobs                 → Create job      │  │
│  │ • POST   /api/proposals            → Submit proposal │  │
│  │ • POST   /api/auth/login           → Authenticate    │  │
│  │ • POST   /api/auth/register        → Create user     │  │
│  │ • GET    /api/providers/{id}       → Get profile     │  │
│  │ • GET    /api/jobs/{id}/reviews    → Get reviews     │  │
│  └─────────────┬──────────────────────────────────────────┘  │
│                │                                              │
│    Business Logic, Validation, Authentication                │
│                │                                              │
│                ↓                                              │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ Database Connection (MySQL Connection Pool)          │  │
│  │ • Execute SQL queries                                │  │
│  │ • Return data to API routes                          │  │
│  └────────────────┬──────────────────────────────────────┘  │
└─────────────────────┼─────────────────────────────────────────┘
                      │
                      ↓
        ┌─────────────────────────────────┐
        │     MySQL DATABASE              │
        │  • Users                        │
        │  • Jobs                         │
        │  • Proposals                    │
        │  • Reviews                      │
        │  • Providers                    │
        │  • Transactions                 │
        └─────────────────────────────────┘
```

---

## Prerequisites & Installation

### Step 1: Install Required Software

#### Windows:

1. **Node.js** (includes npm)
   - Download: https://nodejs.org/ (LTS version)
   - Install and verify:
   ```powershell
   node --version      # Should show v18.x.x or higher
   npm --version       # Should show 9.x.x or higher
   ```

2. **MySQL Server**
   - Download: https://dev.mysql.com/downloads/mysql/
   - Install with MySQL Workbench for easy database management

3. **Git** (optional but recommended)
   - Download: https://git-scm.com/

4. **VS Code Extensions** (optional)
   - MySQL (cweijan.vscode-mysql-client2)
   - REST Client (humao.rest-client)

### Step 2: Create Backend Project Folder

```powershell
# Create project structure
mkdir freelance-marketplace-backend
cd freelance-marketplace-backend

# Initialize npm
npm init -y

# Install dependencies
npm install express mysql2/promise dotenv cors bcryptjs jsonwebtoken nodemailer
npm install --save-dev nodemon
```

### Step 3: Project Structure

```
freelance-marketplace-backend/
├── server.js                    # Main server file
├── .env                         # Environment variables
├── .gitignore                   # Git ignore file
├── package.json                 # Dependencies
├── config/
│   └── database.js              # Database connection
├── routes/
│   ├── auth.js                  # Authentication routes
│   ├── jobs.js                  # Job routes
│   ├── proposals.js             # Proposal routes
│   ├── providers.js             # Provider routes
│   └── reviews.js               # Review routes
├── controllers/
│   ├── authController.js        # Auth logic
│   ├── jobController.js         # Job logic
│   ├── proposalController.js    # Proposal logic
│   ├── providerController.js    # Provider logic
│   └── reviewController.js      # Review logic
├── middleware/
│   ├── auth.js                  # Authentication middleware
│   ├── errorHandler.js          # Error handling
│   └── validation.js            # Input validation
└── database/
    └── schema.sql               # Database schema
```

---

## Database Setup (MySQL)

### Step 1: Create Database

```sql
-- Create database
CREATE DATABASE IF NOT EXISTS freelance_marketplace;
USE freelance_marketplace;

-- ============================================================================
-- USERS TABLE
-- ============================================================================

CREATE TABLE Users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  address TEXT,
  city VARCHAR(100),
  avatar_url VARCHAR(255),
  role ENUM('Client', 'Provider', 'Admin') DEFAULT 'Client',
  status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_email (email),
  INDEX idx_role (role),
  INDEX idx_created_at (created_at)
);

-- ============================================================================
-- PROVIDER PROFILES TABLE
-- ============================================================================

CREATE TABLE Provider_Profiles (
  profile_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  service_category VARCHAR(100) NOT NULL,
  bio TEXT,
  years_of_experience INT,
  hourly_rate DECIMAL(10, 2),
  response_time INT DEFAULT 24,
  availability_status ENUM('Available', 'Busy', 'On_Vacation') DEFAULT 'Available',
  average_rating DECIMAL(3, 2) DEFAULT 0,
  total_reviews INT DEFAULT 0,
  verified BOOLEAN DEFAULT FALSE,
  verification_date DATETIME,
  total_earnings DECIMAL(15, 2) DEFAULT 0,
  completed_jobs INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  INDEX idx_service_category (service_category),
  INDEX idx_average_rating (average_rating),
  INDEX idx_verified (verified)
);

-- ============================================================================
-- PROVIDER SKILLS TABLE
-- ============================================================================

CREATE TABLE Provider_Skills (
  skill_id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  skill_name VARCHAR(100) NOT NULL,
  proficiency_level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') DEFAULT 'Intermediate',
  verified BOOLEAN DEFAULT FALSE,
  added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (profile_id) REFERENCES Provider_Profiles(profile_id) ON DELETE CASCADE,
  UNIQUE KEY unique_skill (profile_id, skill_name),
  INDEX idx_skill_name (skill_name)
);

-- ============================================================================
-- PROVIDER CERTIFICATIONS TABLE
-- ============================================================================

CREATE TABLE Provider_Certifications (
  certification_id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  certification_name VARCHAR(255) NOT NULL,
  issuing_body VARCHAR(255) NOT NULL,
  issue_date DATE NOT NULL,
  expiry_date DATE,
  certificate_url VARCHAR(255),
  verified BOOLEAN DEFAULT FALSE,
  added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (profile_id) REFERENCES Provider_Profiles(profile_id) ON DELETE CASCADE,
  INDEX idx_verified (verified)
);

-- ============================================================================
-- JOBS TABLE
-- ============================================================================

CREATE TABLE Jobs (
  job_id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description LONGTEXT NOT NULL,
  service_category VARCHAR(100) NOT NULL,
  budget_min DECIMAL(10, 2) NOT NULL,
  budget_max DECIMAL(10, 2) NOT NULL,
  location VARCHAR(255),
  latitude DECIMAL(10, 8),
  longitude DECIMAL(11, 8),
  timeline_days INT,
  skills_required JSON,
  status ENUM('Open', 'In_Progress', 'Completed', 'Cancelled', 'Accepted') DEFAULT 'Open',
  hired_freelancer_id INT,
  proposal_count INT DEFAULT 0,
  posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (hired_freelancer_id) REFERENCES Users(user_id),
  INDEX idx_status (status),
  INDEX idx_service_category (service_category),
  INDEX idx_client_id (client_id),
  INDEX idx_posted_date (posted_date)
);

-- ============================================================================
-- PROPOSALS TABLE
-- ============================================================================

CREATE TABLE Proposals (
  proposal_id INT AUTO_INCREMENT PRIMARY KEY,
  job_id INT NOT NULL,
  provider_id INT NOT NULL,
  proposal_text LONGTEXT NOT NULL,
  bid_amount DECIMAL(10, 2) NOT NULL,
  timeline_days INT,
  start_date DATE,
  submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Pending', 'Approved', 'Rejected', 'Accepted', 'Withdrawn') DEFAULT 'Pending',
  
  FOREIGN KEY (job_id) REFERENCES Jobs(job_id) ON DELETE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  UNIQUE KEY unique_proposal (job_id, provider_id),
  INDEX idx_status (status),
  INDEX idx_provider_id (provider_id),
  INDEX idx_submission_date (submission_date)
);

-- ============================================================================
-- REVIEWS TABLE
-- ============================================================================

CREATE TABLE Reviews (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  job_id INT NOT NULL,
  client_id INT NOT NULL,
  provider_id INT NOT NULL,
  rating INT CHECK (rating >= 1 AND rating <= 5) NOT NULL,
  review_text LONGTEXT,
  provider_response LONGTEXT,
  helpful_count INT DEFAULT 0,
  created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (job_id) REFERENCES Jobs(job_id) ON DELETE CASCADE,
  FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  UNIQUE KEY unique_review (job_id, client_id),
  INDEX idx_provider_id (provider_id),
  INDEX idx_rating (rating)
);

-- ============================================================================
-- TRANSACTIONS TABLE
-- ============================================================================

CREATE TABLE Transactions (
  transaction_id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT,
  provider_id INT NOT NULL,
  client_id INT NOT NULL,
  amount DECIMAL(15, 2) NOT NULL,
  payment_method VARCHAR(50),
  status ENUM('Pending', 'Processing', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
  transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  completed_date DATETIME,
  
  FOREIGN KEY (provider_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  INDEX idx_provider_id (provider_id),
  INDEX idx_status (status),
  INDEX idx_transaction_date (transaction_date)
);

-- ============================================================================
-- PROJECTS TABLE
-- ============================================================================

CREATE TABLE Projects (
  project_id INT AUTO_INCREMENT PRIMARY KEY,
  job_id INT NOT NULL,
  client_id INT NOT NULL,
  provider_id INT NOT NULL,
  status ENUM('Active', 'Paused', 'Completed', 'Cancelled') DEFAULT 'Active',
  start_date DATE,
  due_date DATE,
  budget DECIMAL(10, 2),
  progress_percentage INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (job_id) REFERENCES Jobs(job_id) ON DELETE CASCADE,
  FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  INDEX idx_status (status),
  INDEX idx_provider_id (provider_id)
);

-- ============================================================================
-- MESSAGES TABLE
-- ============================================================================

CREATE TABLE Messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  channel_id INT,
  sender_id INT NOT NULL,
  recipient_id INT NOT NULL,
  message_text LONGTEXT,
  attachment_url VARCHAR(255),
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (recipient_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  INDEX idx_channel_id (channel_id),
  INDEX idx_is_read (is_read),
  INDEX idx_created_at (created_at)
);

-- ============================================================================
-- MESSAGE CHANNELS TABLE
-- ============================================================================

CREATE TABLE Message_Channels (
  channel_id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  provider_id INT NOT NULL,
  job_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES Jobs(job_id),
  UNIQUE KEY unique_channel (client_id, provider_id, job_id),
  INDEX idx_job_id (job_id)
);
```

### Step 2: Save and Execute Schema

1. Save the SQL above as `database/schema.sql`
2. Execute in MySQL:

```powershell
# Using MySQL Command Line
mysql -u root -p < database/schema.sql

# Or in MySQL Workbench:
# 1. Open Workbench
# 2. File → Open SQL Script
# 3. Select schema.sql
# 4. Execute (Cmd+Enter)
```

---

## Backend Server Setup (Node.js + Express)

### Step 1: Create Main Server File

**File: `server.js`**

```javascript
// ============================================================================
// MAIN SERVER FILE
// ============================================================================

const express = require('express');
const cors = require('cors');
require('dotenv').config();

const app = express();

// ============================================================================
// MIDDLEWARE
// ============================================================================

// CORS - Allow frontend to communicate with backend
app.use(cors({
  origin: ['http://localhost:3001', 'http://localhost:8000', '*'],
  credentials: true
}));

// JSON parser
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ limit: '10mb', extended: true }));

// Logging middleware
app.use((req, res, next) => {
  console.log(`${new Date().toISOString()} - ${req.method} ${req.path}`);
  next();
});

// ============================================================================
// IMPORT ROUTES
// ============================================================================

const authRoutes = require('./routes/auth');
const jobRoutes = require('./routes/jobs');
const proposalRoutes = require('./routes/proposals');
const providerRoutes = require('./routes/providers');
const reviewRoutes = require('./routes/reviews');

// ============================================================================
// SETUP ROUTES
// ============================================================================

app.use('/api/auth', authRoutes);
app.use('/api/jobs', jobRoutes);
app.use('/api/proposals', proposalRoutes);
app.use('/api/providers', providerRoutes);
app.use('/api/reviews', reviewRoutes);

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({ status: 'Server is running', timestamp: new Date() });
});

// ============================================================================
// ERROR HANDLING
// ============================================================================

app.use((err, req, res, next) => {
  console.error('Error:', err);
  res.status(err.status || 500).json({
    success: false,
    error: err.message || 'Internal server error'
  });
});

// ============================================================================
// START SERVER
// ============================================================================

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
  console.log(`\n🚀 Server is running on http://localhost:${PORT}`);
  console.log(`📡 API endpoints available at http://localhost:${PORT}/api`);
  console.log(`🏥 Health check: http://localhost:${PORT}/health\n`);
});
```

### Step 2: Create Environment Configuration

**File: `.env`**

```env
# Server Configuration
PORT=3000
NODE_ENV=development

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=your_mysql_password
DB_NAME=freelance_marketplace

# JWT Configuration
JWT_SECRET=your_super_secret_jwt_key_change_this_in_production
JWT_EXPIRE=7d

# Email Configuration (optional)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASSWORD=your_app_password

# Frontend URL
FRONTEND_URL=http://localhost:8000
```

### Step 3: Create Database Connection

**File: `config/database.js`**

```javascript
// ============================================================================
// DATABASE CONNECTION CONFIGURATION
// ============================================================================

const mysql = require('mysql2/promise');
require('dotenv').config();

// Create connection pool for better performance
const pool = mysql.createPool({
  host: process.env.DB_HOST,
  port: process.env.DB_PORT,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Test connection
pool.getConnection()
  .then(conn => {
    console.log('✅ Database connected successfully');
    conn.release();
  })
  .catch(err => {
    console.error('❌ Database connection failed:', err.message);
  });

module.exports = pool;
```

### Step 4: Create Authentication Routes

**File: `routes/auth.js`**

```javascript
// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');

// POST /api/auth/register
router.post('/register', authController.register);

// POST /api/auth/login
router.post('/login', authController.login);

// GET /api/auth/verify-token
router.get('/verify-token', authController.verifyToken);

// POST /api/auth/logout
router.post('/logout', authController.logout);

module.exports = router;
```

### Step 5: Create Auth Controller

**File: `controllers/authController.js`**

```javascript
// ============================================================================
// AUTHENTICATION CONTROLLER
// ============================================================================

const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const pool = require('../config/database');

// ============================================================================
// REGISTER
// ============================================================================

exports.register = async (req, res) => {
  try {
    const { email, password, first_name, last_name, phone, address, city, role, service_category, years_of_experience } = req.body;

    // Validation
    if (!email || !password || !first_name || !last_name) {
      return res.status(400).json({
        success: false,
        message: 'Please provide all required fields'
      });
    }

    const conn = await pool.getConnection();

    try {
      // Check if user already exists
      const [existingUser] = await conn.query(
        'SELECT user_id FROM Users WHERE email = ?',
        [email]
      );

      if (existingUser.length > 0) {
        return res.status(409).json({
          success: false,
          message: 'Email already registered'
        });
      }

      // Hash password
      const password_hash = await bcrypt.hash(password, 10);

      // Insert user
      const [userResult] = await conn.query(
        'INSERT INTO Users (email, password_hash, first_name, last_name, phone, address, city, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        [email, password_hash, first_name, last_name, phone, address, city, role || 'Client']
      );

      const user_id = userResult.insertId;

      // If Provider role, create profile
      if (role === 'Provider') {
        await conn.query(
          'INSERT INTO Provider_Profiles (user_id, service_category, years_of_experience) VALUES (?, ?, ?)',
          [user_id, service_category, years_of_experience]
        );
      }

      // Generate JWT token
      const token = jwt.sign(
        { user_id, email, role },
        process.env.JWT_SECRET,
        { expiresIn: process.env.JWT_EXPIRE || '7d' }
      );

      res.status(201).json({
        success: true,
        message: 'Registration successful',
        data: {
          user_id,
          email,
          first_name,
          last_name,
          role,
          token
        }
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    console.error('Registration error:', error);
    res.status(500).json({
      success: false,
      message: 'Registration failed',
      error: error.message
    });
  }
};

// ============================================================================
// LOGIN
// ============================================================================

exports.login = async (req, res) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({
        success: false,
        message: 'Please provide email and password'
      });
    }

    const conn = await pool.getConnection();

    try {
      // Find user
      const [users] = await conn.query(
        'SELECT * FROM Users WHERE email = ?',
        [email]
      );

      if (users.length === 0) {
        return res.status(401).json({
          success: false,
          message: 'Invalid email or password'
        });
      }

      const user = users[0];

      // Check password
      const isPasswordCorrect = await bcrypt.compare(password, user.password_hash);

      if (!isPasswordCorrect) {
        return res.status(401).json({
          success: false,
          message: 'Invalid email or password'
        });
      }

      // Generate token
      const token = jwt.sign(
        { user_id: user.user_id, email: user.email, role: user.role },
        process.env.JWT_SECRET,
        { expiresIn: process.env.JWT_EXPIRE || '7d' }
      );

      res.json({
        success: true,
        message: 'Login successful',
        data: {
          user_id: user.user_id,
          name: `${user.first_name} ${user.last_name}`,
          email: user.email,
          role: user.role,
          token
        }
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({
      success: false,
      message: 'Login failed',
      error: error.message
    });
  }
};

// ============================================================================
// VERIFY TOKEN
// ============================================================================

exports.verifyToken = async (req, res) => {
  try {
    const token = req.headers.authorization?.split(' ')[1];

    if (!token) {
      return res.status(401).json({
        success: false,
        message: 'No token provided'
      });
    }

    const decoded = jwt.verify(token, process.env.JWT_SECRET);

    res.json({
      success: true,
      data: decoded
    });
  } catch (error) {
    res.status(401).json({
      success: false,
      message: 'Invalid token',
      error: error.message
    });
  }
};

// ============================================================================
// LOGOUT
// ============================================================================

exports.logout = (req, res) => {
  // In a production app, you might invalidate the token on server side
  res.json({
    success: true,
    message: 'Logged out successfully'
  });
};

module.exports = exports;
```

### Step 6: Create Jobs Routes & Controller

**File: `routes/jobs.js`**

```javascript
const express = require('express');
const router = express.Router();
const jobController = require('../controllers/jobController');

// GET /api/jobs
router.get('/', jobController.getJobs);

// GET /api/jobs/:id
router.get('/:id', jobController.getJobById);

// POST /api/jobs
router.post('/', jobController.createJob);

// PUT /api/jobs/:id
router.put('/:id', jobController.updateJob);

// DELETE /api/jobs/:id
router.delete('/:id', jobController.deleteJob);

// GET /api/jobs/:id/proposals
router.get('/:id/proposals', jobController.getJobProposals);

module.exports = router;
```

**File: `controllers/jobController.js`**

```javascript
// ============================================================================
// JOB CONTROLLER
// ============================================================================

const pool = require('../config/database');

// ============================================================================
// GET ALL JOBS
// ============================================================================

exports.getJobs = async (req, res) => {
  try {
    const { category, location, minBudget, maxBudget, page = 1, limit = 20 } = req.query;

    let query = `
      SELECT j.*, 
             u.first_name as client_name,
             u.avatar_url,
             (SELECT AVG(rating) FROM Reviews WHERE provider_id = j.hired_freelancer_id) as client_rating
      FROM Jobs j
      JOIN Users u ON j.client_id = u.user_id
      WHERE j.status = 'Open'
    `;

    const params = [];

    // Add filters
    if (category) {
      query += ' AND j.service_category = ?';
      params.push(category);
    }

    if (location) {
      query += ' AND j.location LIKE ?';
      params.push(`%${location}%`);
    }

    if (minBudget) {
      query += ' AND j.budget_min >= ?';
      params.push(minBudget);
    }

    if (maxBudget) {
      query += ' AND j.budget_max <= ?';
      params.push(maxBudget);
    }

    // Add sorting and pagination
    query += ' ORDER BY j.posted_date DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), (page - 1) * limit);

    const conn = await pool.getConnection();

    try {
      const [jobs] = await conn.query(query, params);

      res.json({
        success: true,
        data: jobs,
        page: parseInt(page),
        limit: parseInt(limit)
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    console.error('Get jobs error:', error);
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

// ============================================================================
// GET JOB BY ID
// ============================================================================

exports.getJobById = async (req, res) => {
  try {
    const { id } = req.params;

    const conn = await pool.getConnection();

    try {
      const [jobs] = await conn.query(
        `SELECT j.*,
                u.first_name as client_name,
                u.avatar_url,
                (SELECT AVG(rating) FROM Reviews WHERE provider_id = j.hired_freelancer_id) as client_rating
         FROM Jobs j
         JOIN Users u ON j.client_id = u.user_id
         WHERE j.job_id = ?`,
        [id]
      );

      if (jobs.length === 0) {
        return res.status(404).json({
          success: false,
          message: 'Job not found'
        });
      }

      res.json({
        success: true,
        data: jobs[0]
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

// ============================================================================
// CREATE JOB
// ============================================================================

exports.createJob = async (req, res) => {
  try {
    const { title, description, service_category, budget_min, budget_max, location, timeline_days, skills_required, client_id } = req.body;

    // Validation
    if (!title || !description || !service_category || !budget_min || !budget_max) {
      return res.status(400).json({
        success: false,
        message: 'Missing required fields'
      });
    }

    const conn = await pool.getConnection();

    try {
      const [result] = await conn.query(
        `INSERT INTO Jobs (title, description, service_category, budget_min, budget_max, location, timeline_days, skills_required, client_id, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Open')`,
        [title, description, service_category, budget_min, budget_max, location, timeline_days, JSON.stringify(skills_required), client_id]
      );

      res.status(201).json({
        success: true,
        message: 'Job created successfully',
        data: {
          job_id: result.insertId,
          title,
          description,
          service_category
        }
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    console.error('Create job error:', error);
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

// ============================================================================
// UPDATE JOB
// ============================================================================

exports.updateJob = async (req, res) => {
  try {
    const { id } = req.params;
    const { title, description, status } = req.body;

    const conn = await pool.getConnection();

    try {
      await conn.query(
        'UPDATE Jobs SET title = ?, description = ?, status = ? WHERE job_id = ?',
        [title, description, status, id]
      );

      res.json({
        success: true,
        message: 'Job updated successfully'
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

// ============================================================================
// DELETE JOB
// ============================================================================

exports.deleteJob = async (req, res) => {
  try {
    const { id } = req.params;

    const conn = await pool.getConnection();

    try {
      await conn.query('DELETE FROM Jobs WHERE job_id = ?', [id]);

      res.json({
        success: true,
        message: 'Job deleted successfully'
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

// ============================================================================
// GET JOB PROPOSALS
// ============================================================================

exports.getJobProposals = async (req, res) => {
  try {
    const { id } = req.params;

    const conn = await pool.getConnection();

    try {
      const [proposals] = await conn.query(
        `SELECT p.*,
                u.first_name as provider_name,
                u.avatar_url,
                (SELECT AVG(rating) FROM Reviews WHERE provider_id = p.provider_id) as provider_rating,
                (SELECT COUNT(*) FROM Reviews WHERE provider_id = p.provider_id) as provider_reviews
         FROM Proposals p
         JOIN Users u ON p.provider_id = u.user_id
         WHERE p.job_id = ?
         ORDER BY p.submission_date DESC`,
        [id]
      );

      res.json({
        success: true,
        data: proposals
      });
    } finally {
      conn.release();
    }
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
};

module.exports = exports;
```

### Step 7: Update package.json Scripts

**File: `package.json`**

```json
{
  "name": "freelance-marketplace-backend",
  "version": "1.0.0",
  "description": "Backend for freelance marketplace",
  "main": "server.js",
  "scripts": {
    "start": "node server.js",
    "dev": "nodemon server.js",
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "keywords": [],
  "author": "",
  "license": "ISC",
  "dependencies": {
    "bcryptjs": "^2.4.3",
    "cors": "^2.8.5",
    "dotenv": "^16.0.3",
    "express": "^4.18.2",
    "jsonwebtoken": "^9.0.0",
    "mysql2": "^3.2.0",
    "nodemailer": "^6.9.1"
  },
  "devDependencies": {
    "nodemon": "^2.0.20"
  }
}
```

---

## API Endpoints Implementation

### Complete API Reference

```
# Authentication
POST   /api/auth/register              Register new user
POST   /api/auth/login                 Login user
GET    /api/auth/verify-token          Verify JWT token
POST   /api/auth/logout                Logout user

# Jobs
GET    /api/jobs                       Get all jobs (with filters)
GET    /api/jobs/:id                   Get job by ID
POST   /api/jobs                       Create new job
PUT    /api/jobs/:id                   Update job
DELETE /api/jobs/:id                   Delete job
GET    /api/jobs/:id/proposals         Get proposals for job

# Proposals
GET    /api/proposals                  Get all proposals
POST   /api/proposals                  Submit proposal
PUT    /api/proposals/:id              Update proposal
GET    /api/proposals/:id              Get proposal details
DELETE /api/proposals/:id              Withdraw proposal

# Providers
GET    /api/providers/:id/profile      Get provider profile
PUT    /api/providers/:id/profile      Update provider profile
POST   /api/providers/:id/skills       Add skill
GET    /api/providers/:id/skills       Get provider skills
POST   /api/providers/:id/certifications  Add certification
GET    /api/providers/:id/reviews      Get provider reviews

# Reviews
POST   /api/reviews                    Submit review
GET    /api/reviews/:id                Get review
PUT    /api/reviews/:id                Update review
POST   /api/reviews/:id/response       Provider response to review
```

---

## Authentication System

### JWT Token Implementation

```javascript
// Frontend: Store token in localStorage after login
localStorage.setItem('authToken', token);

// Frontend: Send token in all requests
async function apiCall(endpoint, method = 'GET', data = null) {
  const token = localStorage.getItem('authToken');
  
  const options = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`  // Send token here
    }
  };

  if (data) {
    options.body = JSON.stringify(data);
  }

  const response = await fetch(
    `http://localhost:3000/api${endpoint}`,
    options
  );

  return response.json();
}

// Backend: Verify token middleware
const authMiddleware = (req, res, next) => {
  const token = req.headers.authorization?.split(' ')[1];

  if (!token) {
    return res.status(401).json({ error: 'No token' });
  }

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    req.userId = decoded.user_id;
    next();
  } catch (error) {
    res.status(401).json({ error: 'Invalid token' });
  }
};
```

---

## Testing the Integration

### Step 1: Start Backend Server

```powershell
cd freelance-marketplace-backend

# Install dependencies first time
npm install

# Start server (development mode with auto-reload)
npm run dev

# Output should show:
# 🚀 Server is running on http://localhost:3000
# ✅ Database connected successfully
```

### Step 2: Update Frontend API Configuration

**File: `common.js` - Update API_CONFIG**

```javascript
const API_CONFIG = {
  baseURL: 'http://localhost:3000/api',  // ← Change from mock to real API
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
};
```

### Step 3: Test Login

```javascript
// Open browser console and run:
const loginResult = await FreelanceMarketplace.loginUser(
  'test@example.com',
  'Password123!'
);

console.log(loginResult);
// Expected: { success: true, user: { user_id, email, role, token } }
```

### Step 4: Test Job Fetching

```javascript
// Open browser console and run:
const jobs = await FreelanceMarketplace.fetchJobs({ category: 'Plumbing' });

console.log(jobs);
// Expected: Array of job objects from database
```

### Step 5: Use REST Client for Testing

**File: `test-api.http`**

```http
### Test 1: Health Check
GET http://localhost:3000/health

### Test 2: Register User
POST http://localhost:3000/api/auth/register
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "Password123!",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "9876543210",
  "address": "123 Main St",
  "city": "Mumbai",
  "role": "Client"
}

### Test 3: Login
POST http://localhost:3000/api/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "Password123!"
}

### Test 4: Get Jobs
GET http://localhost:3000/api/jobs?category=Plumbing&location=Mumbai

### Test 5: Create Job
POST http://localhost:3000/api/jobs
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE

{
  "title": "Fix Leaky Faucet",
  "description": "My kitchen sink has a leaky faucet",
  "service_category": "Plumbing",
  "budget_min": 500,
  "budget_max": 1000,
  "location": "Mumbai",
  "timeline_days": 2,
  "skills_required": ["plumbing", "repairs"],
  "client_id": 1
}
```

Use VS Code REST Client extension to run these tests.

---

## Deployment

### Deploy to Production

#### Option 1: Heroku (Easiest for Beginners)

```powershell
# Install Heroku CLI
npm install -g heroku

# Login to Heroku
heroku login

# Create Heroku app
heroku create your-app-name

# Add MySQL database (JawsDB on Heroku)
heroku addons:create jawsdb:kitefin

# Set environment variables
heroku config:set JWT_SECRET=your_production_secret
heroku config:set NODE_ENV=production

# Deploy
git push heroku main

# View logs
heroku logs --tail
```

#### Option 2: AWS EC2

1. Launch EC2 instance (Ubuntu)
2. Install Node.js, MySQL
3. Clone repository
4. Set environment variables
5. Use PM2 for process management
6. Set up nginx as reverse proxy

#### Option 3: DigitalOcean

1. Create Droplet (Ubuntu)
2. SSH into server
3. Install Node.js, MySQL
4. Clone repository
5. Use systemd for process management
6. Configure firewall rules

---

## Complete Checklist

- ✅ Install Node.js and npm
- ✅ Create backend project structure
- ✅ Set up MySQL database
- ✅ Create server.js with Express
- ✅ Configure database connection
- ✅ Implement authentication routes
- ✅ Create job CRUD operations
- ✅ Set up proposal system
- ✅ Implement provider profiles
- ✅ Create review system
- ✅ Add error handling
- ✅ Test API endpoints
- ✅ Deploy to production
- ✅ Set up monitoring/logging
- ✅ Configure SSL/HTTPS
- ✅ Set up automated backups

---

**Document Version:** 1.0
**Last Updated:** October 24, 2025
**Status:** Complete & Ready for Implementation

