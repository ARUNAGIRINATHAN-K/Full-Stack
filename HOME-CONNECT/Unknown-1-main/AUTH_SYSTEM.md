# Authentication & Dashboard System Documentation

## Overview

ServiceHub implements a comprehensive role-based authentication and user management system with distinct workflows for **Clients (Hiring Talent)** and **Service Providers (Providing Services)**.

---

## 🔐 Authentication System

### 1. Authentication Pages

#### **auth.html** - Sign In / Registration Page
**Location:** `a:\My project\New folder\freelance-marketplace\auth.html`

**Features:**
- Dual-form authentication (Sign In & Sign Up)
- Role selection (Client / Service Provider)
- Real-time password strength indicator
- Comprehensive form validation
- Responsive two-column design (form + info section)

---

### 2. Sign In Flow

**File:** `auth.html`

**Validation Rules:**
- Email format: `user@domain.com` (RFC compliant)
- Password: minimum 8 characters
- Both fields required

**Form Fields:**
```
├── Email Address (required, email format)
└── Password (required, min 8 characters)
```

**Backend Integration Points:**
```
POST /api/users/login
Request: { email, password }
Response: { user_id, role, token, user_name }
Storage: localStorage.setItem('authToken', token)
```

**Post-Login Redirect:**
- If role = "Client" → `dashboard-client.html`
- If role = "Provider" → `dashboard-provider.html`

---

### 3. Sign Up Flow (Registration)

**File:** `auth.html`

#### Step 1: Role Selection
```
┌─────────────────────────────────┐
│  Select Your Role:              │
│  ☑ Client (Hiring Talent)      │
│  ☐ Service Provider            │
└─────────────────────────────────┘
```

#### Step 2: Common Fields (Both Roles)
```
├── First Name (required, min 2 chars)
├── Last Name (required, min 2 chars)
├── Email Address (required, valid format)
├── Phone Number (required, 10+ digits)
├── Password (required, 8+ chars with complexity)
├── Confirm Password (must match)
├── Address (required, min 5 chars)
└── City (required, min 2 chars)
```

#### Step 3: Role-Specific Fields

**Client Role:**
- No additional fields
- Straight to terms acceptance

**Service Provider Role:**
```
├── Service Category (required, 16 options)
│   ├── House Cleaning
│   ├── Cooking & Catering
│   ├── Laundry & Ironing
│   ├── Appliance Repair
│   ├── Home Tutoring
│   ├── Childcare & Babysitting
│   ├── Elderly Care
│   ├── Interior Decoration
│   ├── Electricians & Plumbers
│   ├── Carpenters & Painters
│   ├── Gardening & Landscaping
│   ├── Packers & Movers
│   ├── Vehicle Cleaning & Maintenance
│   ├── Pet Grooming & Sitting
│   ├── Tailoring & Alterations
│   └── Event Support
│
└── Years of Experience (optional, numeric)
```

#### Step 4: Terms & Conditions
```
☑ I agree to the Terms & Conditions and Privacy Policy
```

---

### 4. Password Strength Validation

**Requirements:**
- Minimum 8 characters
- At least 1 uppercase letter (A-Z)
- At least 1 lowercase letter (a-z)
- At least 1 number (0-9)
- At least 1 special character (!@#$%^&*)

**Strength Indicator:**
```
Strength: Weak     (0-40%)   [Red]
Strength: Fair     (40-70%)  [Orange]
Strength: Good     (70-90%)  [Yellow]
Strength: Strong   (90-100%) [Green]
```

---

### 5. Form Validation Logic

**Validation Function: `handleRegister(event)`**

```javascript
// Validate First/Last Name
- Length >= 2 characters
- Non-empty

// Validate Email
- Format: user@domain.com
- Regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/

// Validate Phone
- Minimum 10 digits
- Regex: /^[\d\s\+\-()]{10,}$/

// Validate Password
- Length >= 8
- Contains: [A-Z], [a-z], [0-9], [!@#$%^&*]

// Validate Address & City
- Address: >= 5 characters
- City: >= 2 characters

// Validate Provider Fields (if applicable)
- Service Category: Required selection
- Experience: Non-negative integer

// Validate Terms
- Must be checked: true
```

---

## 📊 Dashboard System

### 1. Client Dashboard

**File:** `dashboard-client.html`

**Purpose:** Allow clients to post jobs, review proposals, hire providers, and manage projects.

**Layout:**
```
┌─────────────────────────────────────────────┐
│ Navbar (Home, Post Job, Dashboard, Profile) │
└─────────────────────────────────────────────┘
┌──────────┬─────────────────────────────────┐
│ Sidebar  │ Main Content Area               │
│ - Menu   ├─────────────────────────────────┤
│          │ Tab 1: Overview                 │
│          │ - Stats Grid                    │
│          │ - Recent Jobs                   │
│          │ - Recent Proposals              │
│          │                                 │
│          │ Tab 2: My Jobs                  │
│          │ - Job Listings                  │
│          │                                 │
│          │ Tab 3: Proposals                │
│          │ - Pending Review                │
│          │ - Approved                      │
│          │                                 │
│          │ Tab 4: Messages                 │
│          │                                 │
│          │ Tab 5: Payments                 │
│          │ - Payment History               │
│          │                                 │
│          │ Tab 6: Reviews                  │
│          │ - Client Reviews               │
└──────────┴─────────────────────────────────┘
```

#### Tab 1: Overview
**Statistics:**
```
┌────────────────┐  ┌────────────────┐
│ 3 Active Jobs  │  │ 5 Proposals    │
├────────────────┤  ├────────────────┤
│ 🎯 Icon        │  │ ⏳ Icon        │
└────────────────┘  └────────────────┘

┌────────────────┐  ┌────────────────┐
│ 12 Completed   │  │ ₹2.5L Spent    │
├────────────────┤  ├────────────────┤
│ ✓ Icon         │  │ 💳 Icon        │
└────────────────┘  └────────────────┘
```

**Workflow Steps:**
1. **Post Job** → Job appears in listing
2. **Review Proposals** → Providers submit bids
3. **Select & Hire** → Status updates to "Hired"
4. **Communicate** → Use messaging feature
5. **Monitor Progress** → Track project status
6. **Mark Complete** → Release payment
7. **Leave Review** → Rate provider (1-5 stars)

#### Tab 2: My Jobs
- View all posted jobs
- Status: Active, Pending, Completed, Closed
- Actions: View Details, Edit Job, Close Job

#### Tab 3: Proposals
- **Pending Review:** Awaiting decision
- **Approved:** Selected providers
- **Rejected:** Not chosen

#### Tab 4: Messages
- Direct communication with hired providers
- Message history
- Real-time notifications

#### Tab 5: Payments
```
┌────────────────────────────────────────────┐
│ Payment History                            │
├──────┬──────────┬────────┬────────┬────────┤
│ Date │ Provider │ Job    │ Amount │ Status │
├──────┼──────────┼────────┼────────┼────────┤
│ Oct  │ Rajesh   │ Kitchen│ ₹85K   │ Paid   │
│ Oct  │ Priya    │ Interior│ ₹50K  │ Paid   │
│ Oct  │ Vikram   │ Garden │ ₹35K   │Pending │
└──────┴──────────┴────────┴────────┴────────┘
```

#### Tab 6: Reviews
- Reviews left by client for providers
- Star ratings (1-5)
- Written feedback

---

### 2. Service Provider Dashboard

**File:** `dashboard-provider.html`

**Purpose:** Allow providers to find jobs, submit proposals, manage projects, and track earnings.

**Layout:** Similar to Client Dashboard

#### Tab 1: Overview
**Profile Section:**
```
┌─────────────────────────────────┐
│ [Avatar] Name                   │
│ ⭐ Rating (45 reviews)          │
│ Service Category • X Years      │
│ [Edit Profile Button]           │
└─────────────────────────────────┘
```

**Statistics:**
```
┌────────────────┐  ┌────────────────┐
│ 2 Active       │  │ 3 Pending      │
│ Projects       │  │ Proposals      │
├────────────────┤  ├────────────────┤
│ 🎯 Icon        │  │ ⏳ Icon        │
└────────────────┘  └────────────────┘

┌────────────────┐  ┌────────────────┐
│ 28 Completed   │  │ ₹8.5L Earned   │
│ Projects       │  │                │
├────────────────┤  ├────────────────┤
│ ✓ Icon         │  │ 💳 Icon        │
└────────────────┘  └────────────────┘
```

**Workflow Steps:**
1. **Browse Jobs** → View available opportunities
2. **Submit Proposal** → Include bid and details
3. **Wait for Approval** → Client reviews
4. **Get Hired** → Start project
5. **Update Progress** → Track completion
6. **Complete Project** → Submit final work
7. **Receive Payment** → Money transferred
8. **Build Reputation** → Earn ratings

#### Tab 2: Available Jobs
- Browse jobs matching service category
- Filter by location, budget, posted date
- View proposal count

#### Tab 3: My Proposals
- **Pending Review:** Awaiting client decision
- **Approved:** Hired for these projects
- **Rejected:** Not selected

#### Tab 4: Active Projects
```
Project Title
├── Progress Bar (0-100%)
├── Timeline
├── Client Info
├── Current Status
└── [Update Status Button]
```

#### Tab 5: Earnings
```
┌────────────────────────────────────────────┐
│ Earnings & Payments                        │
├──────┬────────┬──────────┬────────┬────────┤
│ Date │ Project│ Client   │ Amount │ Status │
├──────┼────────┼──────────┼────────┼────────┤
│ Oct  │ Kitchen│ John     │ ₹82.5K │Received│
│ Oct  │ Wiring │ Sarah    │ ₹90K   │Received│
│ Oct  │ Repair │ Mike     │ ₹50K   │Received│
│ Oct  │Bathroom│ Emily    │ ₹45K   │Received│
└──────┴────────┴──────────┴────────┴────────┘
```

#### Tab 6: Reviews
- Reviews from clients
- Star ratings (1-5)
- Average rating calculation

---

## 🔗 Database Integration

### Backend API Endpoints

#### User Management
```
POST   /api/users/login              → Sign in
POST   /api/users/register           → Create account
GET    /api/users/{user_id}          → Get user profile
PUT    /api/users/{user_id}          → Update profile
GET    /api/users/{user_id}/role     → Get user role
```

#### Client Operations
```
POST   /api/jobs                     → Post new job
GET    /api/jobs/{job_id}            → Get job details
PUT    /api/jobs/{job_id}            → Update job
DELETE /api/jobs/{job_id}            → Close job
GET    /api/jobs/{job_id}/proposals  → Get proposals for job
POST   /api/proposals/{proposal_id}/approve → Approve proposal
POST   /api/proposals/{proposal_id}/reject  → Reject proposal
```

#### Provider Operations
```
GET    /api/jobs?category=X          → Browse jobs by category
GET    /api/jobs/search              → Search jobs
POST   /api/proposals                → Submit proposal
GET    /api/proposals/my             → Get my proposals
PUT    /api/projects/{project_id}    → Update project progress
```

#### Messaging & Payments
```
POST   /api/messages                 → Send message
GET    /api/messages/{user_id}       → Get conversation
GET    /api/payments/history         → Payment history
POST   /api/payments/process         → Process payment
```

---

## 📱 Responsive Design

### Breakpoints
```
Mobile:    < 768px   (Single column layout)
Tablet:    768px-1024px (Sidebar collapses)
Desktop:   > 1024px   (Full two-column layout)
```

### Mobile Adjustments
- Sidebar hidden in mobile view
- Collapsible menu
- Stacked forms
- Touch-friendly buttons

---

## 🎨 UI Components

### Forms
- **Input Fields:** Text, Email, Password, Tel, Number, Select
- **Validation Messages:** Real-time feedback
- **Submit Buttons:** With loading state
- **Toggle Links:** Switch between forms

### Cards
- **Job Card:** Title, Meta, Description, Actions
- **Proposal Card:** Provider info, Budget, Actions
- **Stat Card:** Icon, Number, Label

### Status Badges
```
Active    → Green background
Pending   → Yellow background
Completed → Green background
Closed    → Red background
```

---

## 🔒 Security Measures

### Password Security
- Minimum 8 characters
- Character complexity requirement
- Real-time strength indicator
- Never store plain-text passwords (hash with bcrypt)

### Form Validation
- Client-side validation (UX)
- Server-side validation (security)
- Input sanitization
- CSRF token protection

### Authentication
- JWT tokens for session management
- Token refresh mechanism
- Secure HttpOnly cookies
- Role-based access control (RBAC)

---

## 📝 Implementation Checklist

### Backend Development
- [ ] User registration with email verification
- [ ] Secure login with JWT tokens
- [ ] Password reset functionality
- [ ] Role-based access control
- [ ] Database schema implementation
- [ ] API endpoint development
- [ ] Error handling & validation
- [ ] Rate limiting for security

### Frontend Integration
- [ ] Connect auth forms to API
- [ ] Implement token storage & retrieval
- [ ] Add logout functionality
- [ ] Session timeout management
- [ ] Dashboard data loading
- [ ] Real-time updates (WebSockets)
- [ ] Error message display
- [ ] Loading states

### Testing
- [ ] Unit tests for validation functions
- [ ] Integration tests for API calls
- [ ] User flow testing
- [ ] Cross-browser compatibility
- [ ] Mobile responsiveness testing
- [ ] Security testing (OWASP)

---

## 📞 Support & Contact

For technical documentation, see:
- `DATABASE_SCHEMA.md` - Complete database structure
- `TECHNICAL_SPECS.md` - Technical specifications
- `README.md` - Project overview
- `QUICK_START.md` - Getting started guide

---

**Last Updated:** October 23, 2025
**Version:** 1.0.0
**Author:** ServiceHub Development Team
