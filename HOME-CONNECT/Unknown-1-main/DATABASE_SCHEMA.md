# ServiceHub - Database Schema & Backend Integration Guide

## Overview

This document outlines the complete database structure for the ServiceHub local service marketplace platform. The database will store all user data, service listings, proposals, reviews, and transactions. The frontend (HTML/CSS/JS) communicates with the database through a Backend API.

---

## Database Architecture

### System Flow
```
Frontend (HTML/CSS/JS) 
    ↓ API Requests (JSON)
Backend API (Node.js, Python, Java, etc.)
    ↓ Database Queries
Database (SQL: PostgreSQL/MySQL or NoSQL: MongoDB)
    ↓ API Response (JSON)
Frontend (Display & Update)
```

### Database Choice Recommendation
- **SQL (PostgreSQL/MySQL):** Better for structured data with clear relationships
- **NoSQL (MongoDB):** Better for flexible schema and real-time applications

*This guide uses SQL syntax but can be adapted for NoSQL*

---

## Core Data Tables/Collections

### 1. **Users Table**
Stores all user accounts (both Clients and Service Providers)

```sql
CREATE TABLE users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,  -- Must be hashed (bcrypt)
  phone VARCHAR(20),
  role ENUM('Client', 'Provider', 'Admin') NOT NULL,
  profile_picture_url VARCHAR(255),
  bio TEXT,
  rating DECIMAL(3,2) DEFAULT 0.00,  -- 0-5 stars
  total_reviews INT DEFAULT 0,
  account_status ENUM('Active', 'Suspended', 'Deleted') DEFAULT 'Active',
  registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_login TIMESTAMP,
  location_city VARCHAR(100),
  location_latitude DECIMAL(10,8),
  location_longitude DECIMAL(11,8),
  is_verified BOOLEAN DEFAULT FALSE,
  verification_email_sent_at TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Front-end Features Using This Table:**
- `login.html` - Authentication
- `register.html` - Account creation
- `profile-provider.html` - Profile display
- `dashboard-client.html` - User dashboard

**Related Endpoints:**
- `POST /api/users/register` - Create new user
- `POST /api/users/login` - User authentication
- `GET /api/users/{user_id}` - Get user profile
- `PUT /api/users/{user_id}` - Update user profile

---

### 2. **Provider Profiles Table**
Extends user information for service providers

```sql
CREATE TABLE provider_profiles (
  profile_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT UNIQUE NOT NULL,
  hourly_rate DECIMAL(10,2),
  title VARCHAR(150),  -- e.g., "Expert Electrician with 10 years experience"
  skills TEXT,  -- JSON format: ["Wiring", "Installation", "Repair"]
  service_categories JSON,  -- ["Electricians & Plumbers", "Appliance Repair"]
  years_of_experience INT,
  certifications JSON,  -- Array of certification names and dates
  portfolio_items INT DEFAULT 0,  -- Count of portfolio projects
  completed_projects INT DEFAULT 0,
  total_earnings DECIMAL(12,2) DEFAULT 0,
  response_time_hours INT,  -- Average response time
  is_top_rated BOOLEAN DEFAULT FALSE,
  availability_status ENUM('Available', 'Busy', 'Unavailable') DEFAULT 'Available',
  work_schedule JSON,  -- {"Monday": {"start": "09:00", "end": "17:00"}, ...}
  service_area_radius_km INT DEFAULT 10,  -- Service coverage radius
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

**Front-end Features Using This Table:**
- `profile-provider.html` - Display provider profile
- `jobs-list.html` - Filter by provider skills/rating

**Related Endpoints:**
- `GET /api/providers/{user_id}` - Get provider profile
- `PUT /api/providers/{user_id}` - Update provider profile
- `GET /api/providers/search` - Search providers by skills/category

---

### 3. **Job Postings Table**
Core table for job/service listings

```sql
CREATE TABLE job_postings (
  job_id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  service_category VARCHAR(100) NOT NULL,
  service_subcategory VARCHAR(100),
  required_skills JSON,  -- Array of required skills
  budget_min DECIMAL(10,2),
  budget_max DECIMAL(10,2),
  payment_type ENUM('Fixed', 'Hourly', 'Milestone') DEFAULT 'Fixed',
  experience_level ENUM('Beginner', 'Intermediate', 'Expert') DEFAULT 'Intermediate',
  status ENUM('Open', 'In_Progress', 'Completed', 'Cancelled') DEFAULT 'Open',
  priority ENUM('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Medium',
  attachment_urls JSON,  -- Array of file URLs
  location_city VARCHAR(100),
  location_latitude DECIMAL(10,8),
  location_longitude DECIMAL(11,8),
  deadline DATE,
  posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  view_count INT DEFAULT 0,
  proposal_count INT DEFAULT 0,
  hired_provider_id INT,
  completion_date TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (hired_provider_id) REFERENCES users(user_id)
);
```

**Front-end Features Using This Table:**
- `post-job.html` - Create new job posting
- `jobs-list.html` - Display filtered job listings
- `job-details.html` - Show job details
- `dashboard-client.html` - Track posted jobs

**Related Endpoints:**
- `POST /api/jobs` - Create job posting
- `GET /api/jobs` - List jobs (with filtering)
- `GET /api/jobs/{job_id}` - Get job details
- `PUT /api/jobs/{job_id}` - Update job posting
- `DELETE /api/jobs/{job_id}` - Delete job posting

---

### 4. **Proposals/Bids Table**
Stores provider proposals on job postings

```sql
CREATE TABLE proposals (
  proposal_id INT PRIMARY KEY AUTO_INCREMENT,
  job_id INT NOT NULL,
  provider_id INT NOT NULL,
  bid_amount DECIMAL(10,2) NOT NULL,
  cover_letter TEXT NOT NULL,
  estimated_delivery_days INT,
  experience_level VARCHAR(50),
  attachments JSON,  -- Portfolio samples, certifications
  status ENUM('Pending', 'Accepted', 'Rejected', 'Withdrawn') DEFAULT 'Pending',
  acceptance_date TIMESTAMP,
  rejection_reason VARCHAR(255),
  date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (job_id) REFERENCES job_postings(job_id) ON DELETE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES users(user_id) ON DELETE CASCADE,
  UNIQUE KEY unique_proposal (job_id, provider_id)
);
```

**Front-end Features Using This Table:**
- `job-details.html` - Submit proposal form
- `dashboard-client.html` - Review received proposals
- `dashboard-provider.html` - View submitted proposals

**Related Endpoints:**
- `POST /api/proposals` - Submit new proposal
- `GET /api/proposals/job/{job_id}` - Get all proposals for a job
- `GET /api/proposals/provider/{provider_id}` - Get provider's proposals
- `PUT /api/proposals/{proposal_id}` - Update proposal status
- `DELETE /api/proposals/{proposal_id}` - Withdraw proposal

---

### 5. **Reviews Table**
Stores ratings and reviews for completed jobs

```sql
CREATE TABLE reviews (
  review_id INT PRIMARY KEY AUTO_INCREMENT,
  job_id INT NOT NULL,
  reviewer_id INT NOT NULL,  -- Client or Provider
  reviewed_id INT NOT NULL,  -- The person being reviewed
  rating INT NOT NULL,  -- 1-5 stars
  comment TEXT,
  rating_professionalism INT,  -- 1-5
  rating_communication INT,  -- 1-5
  rating_timeliness INT,  -- 1-5
  would_recommend BOOLEAN DEFAULT TRUE,
  review_type ENUM('Client_Review', 'Provider_Review') NOT NULL,
  is_public BOOLEAN DEFAULT TRUE,
  review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (job_id) REFERENCES job_postings(job_id) ON DELETE CASCADE,
  FOREIGN KEY (reviewer_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (reviewed_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

**Front-end Features Using This Table:**
- `profile-provider.html` - Display testimonials
- `index.html` - Testimonial carousel (high-rated reviews)
- `dashboard-client.html` - View reviews received

**Related Endpoints:**
- `POST /api/reviews` - Submit review
- `GET /api/reviews/user/{user_id}` - Get user reviews
- `GET /api/reviews/job/{job_id}` - Get reviews for job
- `PUT /api/reviews/{review_id}` - Update review

---

### 6. **Service Categories Table**
Reference table for all available service categories

```sql
CREATE TABLE service_categories (
  category_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  icon_name VARCHAR(50),  -- Font Awesome icon name
  display_order INT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Sample Data:**
```sql
INSERT INTO service_categories (name, description, icon_name, display_order) VALUES
('House Cleaning', 'Professional home cleaning services', 'fa-broom', 1),
('Cooking & Catering', 'Expert culinary services for any occasion', 'fa-utensils', 2),
('Laundry & Ironing', 'Professional laundry and garment care', 'fa-shirt', 3),
('Appliance Repair', 'Quick and reliable appliance fixes', 'fa-wrench', 4),
('Home Tutoring', 'Expert tutors for all subjects and levels', 'fa-book', 5),
('Childcare & Babysitting', 'Trusted caregivers for your children', 'fa-baby', 6),
('Elderly Care', 'Compassionate care for seniors', 'fa-heart', 7),
('Interior Decoration', 'Creative interior design services', 'fa-palette', 8),
('Electricians & Plumbers', 'Expert electrical and plumbing work', 'fa-bolt', 9),
('Carpenters & Painters', 'Professional carpentry and painting', 'fa-hammer', 10),
('Gardening & Landscaping', 'Beautiful outdoor space management', 'fa-leaf', 11),
('Packers & Movers', 'Safe and professional moving services', 'fa-box', 12),
('Vehicle Cleaning & Maintenance', 'Expert car care and detailing', 'fa-car', 13),
('Pet Grooming & Sitting', 'Professional pet care services', 'fa-paw', 14),
('Tailoring & Alterations', 'Expert clothing customization', 'fa-scissors', 15),
('Event Support', 'Professional event planning and support', 'fa-party-horn', 16);
```

**Front-end Features Using This Table:**
- `index.html` - Service categories grid
- `jobs-list.html` - Filter dropdown
- `post-job.html` - Category selection
- Navigation dropdown menus

**Related Endpoints:**
- `GET /api/categories` - Get all categories

---

### 7. **Payments Table** (Optional but Recommended)
Tracks financial transactions

```sql
CREATE TABLE payments (
  payment_id INT PRIMARY KEY AUTO_INCREMENT,
  job_id INT NOT NULL,
  client_id INT NOT NULL,
  provider_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  currency VARCHAR(3) DEFAULT 'USD',
  payment_method ENUM('Credit_Card', 'Debit_Card', 'Bank_Transfer', 'Wallet') DEFAULT 'Credit_Card',
  status ENUM('Pending', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
  transaction_id VARCHAR(100) UNIQUE,
  payment_date TIMESTAMP,
  due_date DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (job_id) REFERENCES job_postings(job_id) ON DELETE CASCADE,
  FOREIGN KEY (client_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

**Front-end Features Using This Table:**
- `dashboard-client.html` - Payment history tab
- `dashboard-provider.html` - Earnings tracking

**Related Endpoints:**
- `POST /api/payments` - Process payment
- `GET /api/payments/user/{user_id}` - Get user transactions
- `GET /api/payments/{payment_id}` - Get payment details

---

### 8. **Messages Table** (Optional - Real-time Chat)
For direct messaging between users

```sql
CREATE TABLE messages (
  message_id INT PRIMARY KEY AUTO_INCREMENT,
  sender_id INT NOT NULL,
  recipient_id INT NOT NULL,
  job_id INT,
  subject VARCHAR(255),
  content TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  read_at TIMESTAMP,
  message_type ENUM('Direct', 'Job_Related') DEFAULT 'Direct',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (recipient_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES job_postings(job_id) ON DELETE CASCADE
);
```

**Related Endpoints:**
- `POST /api/messages` - Send message
- `GET /api/messages/{user_id}` - Get user messages
- `PUT /api/messages/{message_id}` - Mark as read

---

## Database Relationships Diagram

```
┌─────────────────┐
│     USERS       │
│                 │
│ - user_id (PK)  │
│ - email         │
│ - role          │
│ - rating        │
└────────┬────────┘
         │
         ├──────────────┬──────────────┬──────────────┐
         │              │              │              │
         ▼              ▼              ▼              ▼
   ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
   │   PROVIDER   │ │     JOBS     │ │  PROPOSALS   │ │   REVIEWS    │
   │  PROFILES    │ │  POSTINGS    │ │              │ │              │
   │              │ │              │ │ - proposal_id│ │ - review_id  │
   │ - user_id(FK)│ │ - client_id  │ │ - job_id(FK) │ │ - job_id(FK) │
   │ - skills     │ │ - title      │ │ - provider_id│ │ - reviewer_id│
   │ - rate       │ │ - budget     │ │ - bid_amount │ │ - rating     │
   └──────────────┘ │ - status     │ │ - status     │ └──────────────┘
                    │ - location   │ │ - date_submit│
                    └──────────────┘ └──────────────┘
                           │
                           │
                    ┌──────▼──────┐
                    │  PAYMENTS   │
                    │             │
                    │- payment_id │
                    │- job_id(FK) │
                    │- amount     │
                    │- status     │
                    └─────────────┘

                    ┌──────────────────────┐
                    │  SERVICE_CATEGORIES  │
                    │                      │
                    │- category_id (PK)    │
                    │- name                │
                    │- icon_name           │
                    └──────────────────────┘
```

---

## Frontend-Backend Interaction Map

### 1. **Homepage (index.html)**

#### Fun-Fact Counters
```javascript
// Frontend
fetch('/api/dashboard/stats')
  .then(response => response.json())
  .then(data => {
    updateCounter('completed-projects', data.completed_projects);
    updateCounter('active-providers', data.active_providers);
    updateCounter('satisfaction-rate', data.satisfaction_rate);
  });

// Backend
GET /api/dashboard/stats
Response: {
  "completed_projects": 2500,
  "active_providers": 1200,
  "satisfaction_rate": 98
}

// SQL Query
SELECT 
  COUNT(DISTINCT job_id) as completed_projects
FROM job_postings WHERE status = 'Completed';

SELECT COUNT(*) as active_providers 
FROM users WHERE role = 'Provider' AND account_status = 'Active';
```

#### Testimonial Carousel
```javascript
// Frontend
fetch('/api/reviews/featured?limit=5')
  .then(response => response.json())
  .then(reviews => populateCarousel(reviews));

// Backend
GET /api/reviews/featured?limit=5
Response: [
  {
    "review_id": 1,
    "reviewer_name": "John Doe",
    "reviewed_user_name": "Jane Smith",
    "rating": 5,
    "comment": "Excellent service!",
    "avatar_url": "..."
  }
]

// SQL Query
SELECT r.*, u.name, u.profile_picture_url
FROM reviews r
JOIN users u ON r.reviewed_id = u.user_id
WHERE r.is_public = TRUE AND r.rating >= 4
ORDER BY r.review_date DESC
LIMIT 5;
```

#### Service Categories
```javascript
// Frontend
fetch('/api/categories')
  .then(response => response.json())
  .then(categories => displayCategories(categories));

// Backend
GET /api/categories
Response: [
  {
    "category_id": 1,
    "name": "House Cleaning",
    "icon_name": "fa-broom",
    "description": "Professional home cleaning services"
  },
  ...
]

// SQL Query
SELECT * FROM service_categories 
WHERE is_active = TRUE 
ORDER BY display_order;
```

---

### 2. **Job Listing (jobs-list.html)**

#### Search & Filter Jobs
```javascript
// Frontend
const filters = {
  service_category: 'House Cleaning',
  budget_min: 50,
  budget_max: 500,
  latitude: 40.7128,
  longitude: -74.0060,
  radius_km: 10
};

fetch(`/api/jobs?${new URLSearchParams(filters)}`)
  .then(response => response.json())
  .then(jobs => displayJobs(jobs));

// Backend
GET /api/jobs?service_category=House%20Cleaning&budget_min=50&budget_max=500&latitude=40.7128&longitude=-74.0060&radius_km=10
Response: [
  {
    "job_id": 1,
    "title": "House cleaning needed",
    "budget": 150,
    "client_name": "Alice Johnson",
    "proposals_count": 5,
    "location_distance_km": 2.3
  },
  ...
]

// SQL Query
SELECT jp.*, u.name as client_name,
  ROUND(6371 * acos(cos(radians(?)) * cos(radians(jp.location_latitude)) * 
        cos(radians(jp.location_longitude) - radians(?)) + 
        sin(radians(?)) * sin(radians(jp.location_latitude))), 2) AS distance_km
FROM job_postings jp
JOIN users u ON jp.client_id = u.user_id
WHERE jp.service_category = ? 
  AND jp.budget_max >= ? AND jp.budget_min <= ?
  AND jp.status = 'Open'
HAVING distance_km <= ?
ORDER BY distance_km ASC;
```

---

### 3. **Post Job (post-job.html)**

#### Submit Job Posting
```javascript
// Frontend (Form Submission)
const jobData = {
  title: "Emergency kitchen plumbing repair",
  description: "Leaking sink needs immediate repair...",
  service_category: "Electricians & Plumbers",
  service_subcategory: "Plumbing",
  required_skills: ["Plumbing", "Emergency Response"],
  budget_min: 100,
  budget_max: 300,
  payment_type: "Fixed",
  experience_level: "Intermediate",
  deadline: "2025-10-30",
  location_latitude: 40.7128,
  location_longitude: -74.0060,
  attachment_urls: ["url1", "url2"]
};

fetch('/api/jobs', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(jobData)
})
.then(response => response.json())
.then(data => alert('Job posted! ID: ' + data.job_id));

// Backend
POST /api/jobs
Body: { ...jobData, client_id: 123 }
Response: {
  "success": true,
  "job_id": 456,
  "message": "Job posted successfully"
}

// SQL Query
INSERT INTO job_postings (
  client_id, title, description, service_category, 
  required_skills, budget_min, budget_max, deadline, 
  location_latitude, location_longitude
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
```

---

### 4. **Job Details (job-details.html)**

#### Get Job Details
```javascript
// Frontend
const jobId = 456;
fetch(`/api/jobs/${jobId}`)
  .then(response => response.json())
  .then(job => displayJobDetails(job));

// Backend
GET /api/jobs/456
Response: {
  "job_id": 456,
  "title": "House cleaning needed",
  "description": "...",
  "budget_min": 100,
  "budget_max": 300,
  "client_name": "Alice Johnson",
  "client_rating": 4.8,
  "client_reviews_count": 45,
  "proposals_count": 8,
  "posted_date": "2025-10-20",
  "deadline": "2025-10-30",
  "required_skills": ["Cleaning", "Organization"],
  "status": "Open",
  "similar_jobs": [...]
}

// SQL Query
SELECT jp.*, u.name as client_name, u.rating as client_rating, 
       COUNT(DISTINCT p.proposal_id) as proposals_count
FROM job_postings jp
JOIN users u ON jp.client_id = u.user_id
LEFT JOIN proposals p ON jp.job_id = p.job_id
WHERE jp.job_id = ?
GROUP BY jp.job_id;
```

#### Submit Proposal
```javascript
// Frontend
const proposalData = {
  job_id: 456,
  bid_amount: 250,
  cover_letter: "I have 5 years of professional cleaning experience...",
  estimated_delivery_days: 2,
  experience_level: "Expert"
};

fetch('/api/proposals', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(proposalData)
})
.then(response => response.json())
.then(data => alert('Proposal submitted!'));

// Backend
POST /api/proposals
Body: { ...proposalData, provider_id: 789 }
Response: {
  "success": true,
  "proposal_id": 999,
  "message": "Proposal submitted successfully"
}

// SQL Query
INSERT INTO proposals (
  job_id, provider_id, bid_amount, cover_letter, 
  estimated_delivery_days, experience_level
) VALUES (?, ?, ?, ?, ?, ?);

UPDATE job_postings SET proposal_count = proposal_count + 1 
WHERE job_id = ?;
```

---

### 5. **Client Dashboard (dashboard-client.html)**

#### Get Active Jobs
```javascript
// Frontend
fetch('/api/dashboard/jobs?status=Active')
  .then(response => response.json())
  .then(jobs => displayActiveJobs(jobs));

// Backend
GET /api/dashboard/jobs?status=Active
Response: [
  {
    "job_id": 456,
    "title": "House cleaning",
    "hired_provider_name": "John Smith",
    "progress_percentage": 60,
    "budget": 250,
    "deadline": "2025-10-30"
  }
]

// SQL Query
SELECT jp.*, u.name as hired_provider_name
FROM job_postings jp
LEFT JOIN users u ON jp.hired_provider_id = u.user_id
WHERE jp.client_id = ? AND jp.status = 'In_Progress';
```

#### Get Received Proposals
```javascript
// Frontend
fetch('/api/dashboard/proposals')
  .then(response => response.json())
  .then(proposals => displayProposals(proposals));

// Backend
GET /api/dashboard/proposals
Response: [
  {
    "proposal_id": 999,
    "job_id": 456,
    "provider_name": "Jane Doe",
    "provider_rating": 4.9,
    "bid_amount": 200,
    "cover_letter": "I can do this...",
    "date_submitted": "2025-10-22",
    "status": "Pending"
  }
]

// SQL Query
SELECT p.*, u.name as provider_name, u.rating as provider_rating
FROM proposals p
JOIN users u ON p.provider_id = u.user_id
WHERE p.job_id IN (
  SELECT job_id FROM job_postings WHERE client_id = ?
)
ORDER BY p.date_submitted DESC;
```

#### Get Payment History
```javascript
// Frontend
fetch('/api/payments/user')
  .then(response => response.json())
  .then(payments => displayPayments(payments));

// Backend
GET /api/payments/user
Response: [
  {
    "payment_id": 1,
    "job_id": 456,
    "provider_name": "John Smith",
    "amount": 250,
    "status": "Completed",
    "date": "2025-10-25"
  }
]

// SQL Query
SELECT p.*, u.name as provider_name, jp.title as job_title
FROM payments p
JOIN users u ON p.provider_id = u.user_id
JOIN job_postings jp ON p.job_id = jp.job_id
WHERE p.client_id = ?
ORDER BY p.created_at DESC;
```

---

### 6. **Provider Profile (profile-provider.html)**

#### Get Provider Profile
```javascript
// Frontend
const providerId = 789;
fetch(`/api/providers/${providerId}`)
  .then(response => response.json())
  .then(profile => displayProfile(profile));

// Backend
GET /api/providers/789
Response: {
  "user_id": 789,
  "name": "John Smith",
  "rating": 4.9,
  "reviews_count": 42,
  "bio": "Expert electrician with 10 years experience",
  "skills": ["Wiring", "Installation", "Repair"],
  "service_categories": ["Electricians & Plumbers", "Appliance Repair"],
  "hourly_rate": 75,
  "completed_projects": 158,
  "response_time_hours": 2,
  "certifications": [
    { "name": "Electrician License", "year": 2015 },
    { "name": "Safety Certification", "year": 2023 }
  ],
  "portfolio_items": 12,
  "social_media": { "facebook": "...", "instagram": "..." }
}

// SQL Query
SELECT u.*, pp.* FROM users u
LEFT JOIN provider_profiles pp ON u.user_id = pp.user_id
WHERE u.user_id = ?;
```

#### Get Provider Reviews
```javascript
// Frontend
fetch(`/api/reviews/user/${providerId}`)
  .then(response => response.json())
  .then(reviews => displayReviews(reviews));

// Backend
GET /api/reviews/user/789
Response: [
  {
    "review_id": 1,
    "reviewer_name": "Alice Johnson",
    "rating": 5,
    "rating_professionalism": 5,
    "rating_communication": 5,
    "rating_timeliness": 4,
    "comment": "Excellent work! Very professional.",
    "review_date": "2025-10-20"
  }
]

// SQL Query
SELECT r.*, u.name as reviewer_name, u.profile_picture_url
FROM reviews r
JOIN users u ON r.reviewer_id = u.user_id
WHERE r.reviewed_id = ? AND r.is_public = TRUE
ORDER BY r.review_date DESC;
```

---

## API Endpoint Reference

### User Authentication
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/users/register` | Register new user |
| POST | `/api/users/login` | User login |
| POST | `/api/users/logout` | User logout |
| GET | `/api/users/{user_id}` | Get user profile |
| PUT | `/api/users/{user_id}` | Update user profile |
| POST | `/api/users/password-reset` | Password reset request |

### Jobs Management
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/jobs` | Create new job posting |
| GET | `/api/jobs` | List jobs with filters |
| GET | `/api/jobs/{job_id}` | Get job details |
| PUT | `/api/jobs/{job_id}` | Update job posting |
| DELETE | `/api/jobs/{job_id}` | Delete job posting |
| GET | `/api/jobs/{job_id}/similar` | Get similar jobs |

### Proposals
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/proposals` | Submit new proposal |
| GET | `/api/proposals/job/{job_id}` | Get all proposals for job |
| GET | `/api/proposals/provider/{provider_id}` | Get provider's proposals |
| PUT | `/api/proposals/{proposal_id}` | Update proposal status |
| DELETE | `/api/proposals/{proposal_id}` | Withdraw proposal |

### Reviews
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/reviews` | Submit review |
| GET | `/api/reviews/user/{user_id}` | Get user reviews |
| GET | `/api/reviews/job/{job_id}` | Get job reviews |
| GET | `/api/reviews/featured` | Get featured reviews |
| PUT | `/api/reviews/{review_id}` | Update review |

### Providers
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/providers/{user_id}` | Get provider profile |
| PUT | `/api/providers/{user_id}` | Update provider profile |
| GET | `/api/providers/search` | Search providers |
| GET | `/api/providers/{user_id}/portfolio` | Get provider portfolio |

### Dashboard & Statistics
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/dashboard/stats` | Get platform statistics |
| GET | `/api/dashboard/jobs` | Get user's jobs |
| GET | `/api/dashboard/proposals` | Get user's proposals |
| GET | `/api/dashboard/earnings` | Get provider earnings |

### Payments
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/payments` | Process payment |
| GET | `/api/payments/user` | Get payment history |
| GET | `/api/payments/{payment_id}` | Get payment details |
| PUT | `/api/payments/{payment_id}` | Update payment status |

### Categories & System
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/categories` | Get all categories |
| GET | `/api/categories/{category_id}` | Get category details |
| POST | `/api/messages` | Send message |
| GET | `/api/messages/{user_id}` | Get user messages |

---

## Data Validation Rules

### Users Table
```javascript
{
  email: { required: true, unique: true, format: 'email' },
  password: { required: true, minLength: 8, regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/ },
  phone: { format: 'phone' },
  rating: { min: 0, max: 5 },
  location_latitude: { min: -90, max: 90 },
  location_longitude: { min: -180, max: 180 }
}
```

### Job Postings
```javascript
{
  title: { required: true, minLength: 10, maxLength: 255 },
  description: { required: true, minLength: 50, maxLength: 5000 },
  budget_min: { required: true, min: 0 },
  budget_max: { required: true, min: 0, greaterThan: 'budget_min' },
  service_category: { required: true, enum: ['validCategories'] },
  deadline: { required: true, date: true, afterToday: true }
}
```

### Proposals
```javascript
{
  bid_amount: { required: true, min: 0, max: 999999 },
  cover_letter: { required: true, minLength: 20, maxLength: 5000 },
  estimated_delivery_days: { required: true, min: 1 },
  job_id: { required: true, exists: true }
}
```

### Reviews
```javascript
{
  rating: { required: true, min: 1, max: 5, integer: true },
  comment: { minLength: 10, maxLength: 1000 },
  reviewed_id: { required: true, notEqual: 'reviewer_id' }
}
```

---

## Security Considerations

### Authentication & Authorization
- Use JWT (JSON Web Tokens) or session-based authentication
- Implement role-based access control (RBAC):
  - **Admin**: Full system access
  - **Client**: Can post jobs, hire providers, pay
  - **Provider**: Can browse jobs, submit proposals, receive payments

### Data Protection
- **Passwords**: Hash using bcrypt with salt rounds ≥ 12
- **Sensitive Data**: Encrypt credit card information, SSNs
- **API Keys**: Store securely, rotate regularly
- **HTTPS/TLS**: All API communications encrypted

### Database Security
- Use parameterized queries to prevent SQL injection
- Implement database-level access controls
- Regular backups and disaster recovery plans
- Audit logging for sensitive operations

### Rate Limiting
- Limit API requests per IP/user to prevent abuse
- Implement CAPTCHA for registration/login
- Monitor for suspicious activity

---

## Recommended Technology Stack

### Backend Options
1. **Node.js + Express** (JavaScript)
   - Easy integration with MongoDB
   - Fast development
   - Good for real-time features

2. **Python + Django/Flask**
   - Strong ORM support
   - Excellent documentation
   - Good for rapid development

3. **Java + Spring Boot**
   - Enterprise-grade
   - High performance
   - Scalable

### Database Options
1. **PostgreSQL** (SQL)
   - Reliable, open-source
   - Strong ACID compliance
   - Advanced features

2. **MySQL** (SQL)
   - Popular, easy to set up
   - Good performance
   - Wide hosting support

3. **MongoDB** (NoSQL)
   - Flexible schema
   - Good for real-time data
   - Natural JSON integration

### Authentication
- Firebase Authentication (quick setup)
- Auth0 (enterprise features)
- JWT with custom backend

### Hosting & Deployment
- AWS (EC2, RDS, S3)
- DigitalOcean (affordable, simple)
- Heroku (easy deployment)
- Google Cloud Platform

---

## Implementation Roadmap

### Phase 1: MVP (Weeks 1-2)
- [ ] Set up database schema
- [ ] Implement user registration/login
- [ ] Create job posting API
- [ ] Implement job listing API
- [ ] Basic proposal system

### Phase 2: Core Features (Weeks 3-4)
- [ ] Connect frontend forms to APIs
- [ ] Implement filtering and search
- [ ] Add dashboard functionality
- [ ] Provider profile system
- [ ] Basic review system

### Phase 3: Enhanced Features (Weeks 5-6)
- [ ] Payment integration
- [ ] Messaging system
- [ ] Geolocation-based filtering
- [ ] Admin dashboard
- [ ] Performance optimization

### Phase 4: Polish & Testing (Week 7+)
- [ ] Security audit
- [ ] Load testing
- [ ] User acceptance testing
- [ ] Bug fixes
- [ ] Production deployment

---

## Testing Checklist

- [ ] Unit tests for all API endpoints
- [ ] Integration tests for database operations
- [ ] Test all CRUD operations
- [ ] Test filtering and search
- [ ] Test authentication and authorization
- [ ] Test payment processing
- [ ] Load testing (100+ concurrent users)
- [ ] Security testing (SQL injection, XSS, CSRF)
- [ ] End-to-end testing of user workflows

---

## Troubleshooting Guide

### Common Issues

**Issue:** API returns 500 error
- Check backend logs
- Verify database connection
- Test with curl command

**Issue:** Data not saving to database
- Check INSERT query syntax
- Verify foreign key constraints
- Check data type mismatches

**Issue:** Geolocation not filtering correctly
- Verify latitude/longitude format
- Check distance calculation formula
- Test with known coordinates

**Issue:** Performance is slow
- Add database indexes
- Implement query caching
- Use pagination
- Monitor slow queries

---

## Next Steps

1. **Choose your tech stack** - Decide on backend language and database
2. **Set up development environment** - Local database, API testing tools
3. **Implement authentication** - User registration and login
4. **Build APIs incrementally** - Start with job posting API
5. **Connect frontend to backend** - Update JavaScript fetch calls
6. **Test thoroughly** - Unit tests, integration tests, E2E tests
7. **Deploy to production** - Use CI/CD pipeline

---

## Resources & References

- **Frontend Documentation:** See TECHNICAL_SPECS.md
- **API Documentation:** Use tools like Swagger/OpenAPI
- **Database Design:** Database normalization guide
- **Security:** OWASP Top 10 Prevention Guide
- **Testing:** Jest, Mocha, Pytest documentation

---

**Version:** 1.0.0  
**Last Updated:** October 23, 2025  
**Status:** Complete
