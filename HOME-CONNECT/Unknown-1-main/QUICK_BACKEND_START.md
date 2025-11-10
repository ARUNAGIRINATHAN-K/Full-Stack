# Quick Start Backend Setup

## 5-Minute Quick Start Guide

This guide will get your backend running in just 5 minutes!

---

## Step 1: Create Backend Folder (1 minute)

```powershell
# Open PowerShell and run:
mkdir freelance-marketplace-backend
cd freelance-marketplace-backend

# Create folder structure
mkdir config, routes, controllers, middleware, database

# Create .gitignore
'node_modules' | Out-File .gitignore
'*.env' | Out-File -Append .gitignore
```

---

## Step 2: Initialize Node Project (1 minute)

```powershell
# Initialize npm
npm init -y

# Install all dependencies at once
npm install express mysql2/promise dotenv cors bcryptjs jsonwebtoken
npm install --save-dev nodemon
```

---

## Step 3: Create Configuration Files (2 minutes)

### Create `.env` file

```powershell
@"
PORT=3000
NODE_ENV=development
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=root
DB_NAME=freelance_marketplace
JWT_SECRET=your_super_secret_key_change_in_production
JWT_EXPIRE=7d
FRONTEND_URL=http://localhost:8000
"@ | Out-File .env
```

### Create `server.js` file

```powershell
@"
const express = require('express');
const cors = require('cors');
require('dotenv').config();

const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Routes placeholder
app.get('/health', (req, res) => {
  res.json({ status: 'Server is running!' });
});

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`✅ Server running on http://localhost:\${PORT}`);
});
"@ | Out-File server.js
```

### Create `package.json` scripts

Add this to your `package.json`:

```json
"scripts": {
  "start": "node server.js",
  "dev": "nodemon server.js"
}
```

---

## Step 4: Set Up Database (2 minutes)

### Install MySQL

1. Download from: https://dev.mysql.com/downloads/mysql/
2. Install with default settings
3. Remember password during setup

### Create Database

```powershell
# Open Command Prompt
mysql -u root -p

# Enter your MySQL password

# Run these commands:
CREATE DATABASE freelance_marketplace;
USE freelance_marketplace;

# Create Users table
CREATE TABLE Users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100),
  last_name VARCHAR(100),
  phone VARCHAR(20),
  role ENUM('Client', 'Provider') DEFAULT 'Client',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# Create Jobs table
CREATE TABLE Jobs (
  job_id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description LONGTEXT,
  service_category VARCHAR(100),
  budget_min DECIMAL(10,2),
  budget_max DECIMAL(10,2),
  status ENUM('Open', 'Completed') DEFAULT 'Open',
  posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES Users(user_id)
);

# Exit
EXIT;
```

---

## Step 5: Create Database Connection File (30 seconds)

### Create `config/database.js`

```javascript
const mysql = require('mysql2/promise');
require('dotenv').config();

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
    console.log('✅ Database connected!');
    conn.release();
  })
  .catch(err => {
    console.error('❌ Database error:', err.message);
  });

module.exports = pool;
```

---

## Step 6: Start Your Server

```powershell
# Run development server
npm run dev

# You should see:
# ✅ Server running on http://localhost:3000
# ✅ Database connected!
```

---

## Step 7: Update Frontend API URL

In your frontend `common.js`, change:

```javascript
const API_CONFIG = {
  baseURL: 'http://localhost:3000/api',  // ← Change to this
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json'
  }
};
```

---

## Test Your Setup

```powershell
# Open browser and go to:
http://localhost:3000/health

# Should see:
# { "status": "Server is running!" }
```

---

## Next Steps

1. **Create Authentication Routes** - Implement /api/auth/register and /api/auth/login
2. **Create Job Routes** - Implement /api/jobs endpoints
3. **Add User Interface** - Connect frontend to these endpoints
4. **Deploy** - Put on Heroku or AWS

See `BACKEND_SETUP_GUIDE.md` for complete implementation!

