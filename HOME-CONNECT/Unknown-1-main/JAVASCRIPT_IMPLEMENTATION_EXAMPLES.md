# 📝 JavaScript Implementation Examples

## Practical Code Examples for Specific Pages

This document shows real, production-ready code snippets for common scenarios in your freelance marketplace.

---

## Example 1: jobs-list.html Page

### HTML Structure
```html
<!-- Page: jobs-list.html -->
<!DOCTYPE html>
<html>
<head>
  <title>Available Jobs</title>
  <link rel="stylesheet" href="common.css">
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar">...</nav>

  <!-- Filter Section -->
  <div class="container mt-4">
    <div class="row mb-4">
      <div class="col-md-3">
        <input type="text" id="categoryFilter" placeholder="Filter by category">
      </div>
      <div class="col-md-3">
        <input type="text" id="locationFilter" placeholder="Filter by location">
      </div>
      <div class="col-md-3">
        <input type="number" id="minBudgetFilter" placeholder="Min budget">
      </div>
      <div class="col-md-3">
        <button onclick="applyFilters()" class="btn btn-primary w-100">
          Search Jobs
        </button>
      </div>
    </div>
  </div>

  <!-- Jobs Display Container -->
  <div class="container">
    <div id="jobs-container" class="row">
      <!-- Jobs will be populated here by JavaScript -->
    </div>
  </div>

  <!-- Pagination -->
  <nav data-pagination-container class="mt-4"></nav>

  <script src="common.js"></script>
  <script src="pages/jobs-list.js"></script>
</body>
</html>
```

### JavaScript Implementation (pages/jobs-list.js)
```javascript
// ============================================================================
// JOBS LIST PAGE - JavaScript Implementation
// File: pages/jobs-list.js
// ============================================================================

console.log('🚀 Jobs List Page Loaded');

// Track current filters
let currentFilters = {};

// ============================================================================
// INITIALIZE PAGE
// ============================================================================

document.addEventListener('DOMContentLoaded', async () => {
  console.log('📄 DOM Content Loaded - Initializing jobs list...');
  
  // Set up filter input listeners
  setupFilterListeners();
  
  // Load initial jobs (no filters)
  await loadAndDisplayJobs();
});

// ============================================================================
// SETUP FILTER LISTENERS
// ============================================================================

function setupFilterListeners() {
  const categoryInput = document.getElementById('categoryFilter');
  const locationInput = document.getElementById('locationFilter');
  const minBudgetInput = document.getElementById('minBudgetFilter');

  // Real-time search as user types
  categoryInput?.addEventListener('input', (e) => {
    currentFilters.category = e.target.value;
  });

  locationInput?.addEventListener('input', (e) => {
    currentFilters.location = e.target.value;
  });

  minBudgetInput?.addEventListener('input', (e) => {
    currentFilters.minBudget = e.target.value;
  });
}

// ============================================================================
// APPLY FILTERS AND LOAD JOBS
// ============================================================================

async function applyFilters() {
  console.log('🔍 Applying filters:', currentFilters);
  
  // Show loading state
  const container = document.getElementById('jobs-container');
  container.innerHTML = `
    <div class="col-12 text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-3">Searching for jobs...</p>
    </div>
  `;

  // Fetch filtered jobs
  const jobs = await fetchJobs(currentFilters);

  // Render results
  if (jobs && jobs.length > 0) {
    renderJobs(jobs);
  } else {
    container.innerHTML = `
      <div class="col-12 alert alert-info text-center">
        <i class="fas fa-search"></i>
        <p class="mt-2">No jobs found matching your criteria.</p>
        <small>Try adjusting your filters</small>
      </div>
    `;
  }
}

// ============================================================================
// HANDLE JOB CARD CLICKS
// ============================================================================

window.viewJobDetails = function(jobId) {
  console.log(`👀 Viewing job #${jobId}`);
  
  // Store job ID for the detail page
  sessionStorage.setItem('selectedJobId', jobId);
  
  // Navigate to job details page
  window.location.href = `/job-details.html?id=${jobId}`;
};

// ============================================================================
// PAGINATION HANDLER
// ============================================================================

function setupPaginationWithFilters(totalJobs, jobsPerPage = 20) {
  setupPagination(totalJobs, jobsPerPage, async (pageNumber) => {
    // Add page to current filters
    const filtersWithPage = {
      ...currentFilters,
      page: pageNumber
    };

    // Fetch jobs for this page
    const jobs = await fetchJobs(filtersWithPage);
    renderJobs(jobs);

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// ============================================================================
// EXPORT FUNCTIONS
// ============================================================================

window.JobsList = {
  applyFilters,
  viewJobDetails,
  setupPaginationWithFilters
};

console.log('✅ Jobs List Page Ready');
```

---

## Example 2: job-details.html Page

### HTML Structure
```html
<!-- Page: job-details.html -->
<!DOCTYPE html>
<html>
<head>
  <title>Job Details</title>
</head>
<body>
  <!-- Job Header -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-8">
        <div id="jobHeader">
          <!-- Will be populated by JavaScript -->
          <h1 id="jobTitle">Loading...</h1>
          <div id="jobMeta" class="mt-3"></div>
          <div id="jobDescription" class="mt-4"></div>
        </div>

        <!-- Proposal History -->
        <div id="proposalSection" class="mt-5">
          <h4>Proposals Submitted (<span id="proposalCount">0</span>)</h4>
          <div id="proposalsList"></div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Sidebar: Submit Proposal Form -->
        <div class="card sticky-top">
          <div class="card-body">
            <h5>Submit Your Proposal</h5>
            <form id="proposalForm">
              <div class="mb-3">
                <label>Cover Letter</label>
                <textarea name="coverLetter" class="form-control" rows="4" 
                  placeholder="Why are you the best fit for this job?" required></textarea>
              </div>

              <div class="mb-3">
                <label>Your Bid Amount (₹)</label>
                <input type="number" name="bidAmount" class="form-control" 
                  placeholder="Your quote" required>
              </div>

              <div class="mb-3">
                <label>Timeline (days)</label>
                <input type="number" name="timelineDays" class="form-control" 
                  placeholder="How many days?" required>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i> Submit Proposal
              </button>
            </form>

            <div id="formMessage" class="alert mt-3 d-none"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="common.js"></script>
  <script src="pages/job-details.js"></script>
</body>
</html>
```

### JavaScript Implementation (pages/job-details.js)
```javascript
// ============================================================================
// JOB DETAILS PAGE - JavaScript Implementation
// File: pages/job-details.js
// ============================================================================

console.log('🚀 Job Details Page Loaded');

let currentJobId = null;
let currentJob = null;

// ============================================================================
// INITIALIZE PAGE
// ============================================================================

document.addEventListener('DOMContentLoaded', async () => {
  console.log('📄 DOM Content Loaded');
  
  // Get job ID from URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  currentJobId = urlParams.get('id');

  if (!currentJobId) {
    // Try to get from session storage
    currentJobId = sessionStorage.getItem('selectedJobId');
  }

  if (!currentJobId) {
    showErrorMessage('No job selected. Redirecting...');
    setTimeout(() => { window.location.href = '/jobs-list.html'; }, 2000);
    return;
  }

  console.log(`📌 Loading job #${currentJobId}`);

  // Fetch and display job details
  await loadJobDetails();

  // Set up proposal form
  setupProposalForm();

  // Load existing proposals
  await loadProposals();
});

// ============================================================================
// LOAD JOB DETAILS
// ============================================================================

async function loadJobDetails() {
  try {
    console.log('📡 Fetching job details...');

    // Show loading state
    document.getElementById('jobHeader').innerHTML = `
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    `;

    // Fetch job from API
    const response = await apiCall(`/jobs/${currentJobId}`, 'GET');

    if (!response.success) {
      throw new Error(response.error || 'Failed to load job');
    }

    currentJob = response.data;
    console.log('✅ Job loaded:', currentJob);

    // Render job details
    renderJobHeader();
  } catch (error) {
    console.error('❌ Error loading job:', error);
    showErrorMessage(`Failed to load job: ${error.message}`);
  }
}

// ============================================================================
// RENDER JOB HEADER
// ============================================================================

function renderJobHeader() {
  if (!currentJob) return;

  // Update title
  document.getElementById('jobTitle').textContent = currentJob.title;

  // Update metadata
  document.getElementById('jobMeta').innerHTML = `
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <span class="badge bg-primary">${currentJob.service_category}</span>
        <span class="badge bg-secondary ms-2">
          <i class="fas fa-map-marker-alt"></i> ${currentJob.location}
        </span>
      </div>
      <div class="text-end">
        <p class="mb-0"><strong>Budget:</strong></p>
        <p class="text-primary"><strong>₹${currentJob.budget_min.toLocaleString()} - ₹${currentJob.budget_max.toLocaleString()}</strong></p>
      </div>
    </div>

    <div class="row mt-3 border-top pt-3">
      <div class="col-md-4">
        <small class="text-muted">Client</small>
        <p><strong>${currentJob.client_name}</strong></p>
      </div>
      <div class="col-md-4">
        <small class="text-muted">Client Rating</small>
        <p class="text-warning">${'⭐'.repeat(Math.ceil(currentJob.client_rating))}</p>
      </div>
      <div class="col-md-4">
        <small class="text-muted">Posted</small>
        <p>${FreelanceMarketplace.formatDate(currentJob.posted_date)}</p>
      </div>
    </div>
  `;

  // Update description
  document.getElementById('jobDescription').innerHTML = `
    <h4>Project Details</h4>
    <p>${currentJob.description}</p>
    
    <h5 class="mt-4">Required Skills</h5>
    <div>
      ${(currentJob.skills_required || []).map(skill => 
        `<span class="badge bg-light text-dark me-2 mb-2">${skill}</span>`
      ).join('')}
    </div>

    <div class="row mt-5">
      <div class="col-md-6">
        <strong>Timeline:</strong> ${currentJob.timeline_days} days
      </div>
      <div class="col-md-6">
        <strong>Proposals:</strong> ${currentJob.proposal_count} submitted
      </div>
    </div>
  `;
}

// ============================================================================
// SETUP PROPOSAL FORM
// ============================================================================

function setupProposalForm() {
  const form = document.getElementById('proposalForm');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Check if user is authenticated
    const userId = localStorage.getItem('userId');
    if (!userId) {
      showFormMessage('Please log in to submit a proposal', 'warning');
      setTimeout(() => {
        window.location.href = '/auth.html';
      }, 2000);
      return;
    }

    // Get form data
    const formData = new FormData(form);
    const proposalData = {
      job_id: currentJobId,
      provider_id: userId,
      coverLetter: formData.get('coverLetter'),
      bidAmount: parseFloat(formData.get('bidAmount')),
      timelineDays: parseInt(formData.get('timelineDays')),
      startDate: new Date().toISOString().split('T')[0]
    };

    console.log('📤 Submitting proposal:', proposalData);

    // Show loading state
    const button = form.querySelector('button[type="submit"]');
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Submitting...';

    try {
      // Submit proposal
      const response = await apiCall('/proposals', 'POST', proposalData);

      if (response.success) {
        showFormMessage('Proposal submitted successfully!', 'success');
        form.reset();

        // Reload proposals list
        await loadProposals();

        // Scroll to proposals section
        document.getElementById('proposalSection').scrollIntoView({ behavior: 'smooth' });
      } else {
        showFormMessage(`Error: ${response.error}`, 'danger');
      }
    } catch (error) {
      console.error('Error:', error);
      showFormMessage(`Failed to submit proposal: ${error.message}`, 'danger');
    } finally {
      button.disabled = false;
      button.textContent = originalText;
    }
  });
}

// ============================================================================
// LOAD PROPOSALS
// ============================================================================

async function loadProposals() {
  try {
    console.log('📡 Fetching proposals...');

    const response = await apiCall(`/jobs/${currentJobId}/proposals`, 'GET');

    if (response.success) {
      const proposals = response.data;
      console.log('✅ Proposals loaded:', proposals);

      // Update count
      document.getElementById('proposalCount').textContent = proposals.length;

      // Render proposals
      renderProposals(proposals);
    }
  } catch (error) {
    console.error('❌ Error loading proposals:', error);
  }
}

// ============================================================================
// RENDER PROPOSALS
// ============================================================================

function renderProposals(proposals) {
  const container = document.getElementById('proposalsList');

  if (!proposals || proposals.length === 0) {
    container.innerHTML = '<p class="text-muted">No proposals yet.</p>';
    return;
  }

  container.innerHTML = proposals.map(proposal => `
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div>
            <h6 class="card-title">${proposal.provider_name}</h6>
            <small class="text-muted">
              <i class="fas fa-star text-warning"></i>
              ${proposal.provider_rating.toFixed(1)} (${proposal.provider_reviews} reviews)
            </small>
          </div>
          <span class="badge bg-success">₹${proposal.bid_amount.toLocaleString()}</span>
        </div>

        <p class="card-text mt-3">${proposal.proposal_text}</p>

        <div class="row mt-3 text-muted small">
          <div class="col-6">
            <i class="fas fa-calendar"></i> ${proposal.timeline_days} days
          </div>
          <div class="col-6">
            <i class="fas fa-clock"></i> ${FreelanceMarketplace.formatDate(proposal.submission_date)}
          </div>
        </div>

        <div class="mt-3">
          <span class="badge ${getStatusBadgeClass(proposal.status)}">
            ${proposal.status}
          </span>
        </div>
      </div>
    </div>
  `).join('');
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function getStatusBadgeClass(status) {
  const statusMap = {
    'Pending': 'bg-warning',
    'Approved': 'bg-success',
    'Rejected': 'bg-danger',
    'Accepted': 'bg-primary'
  };
  return statusMap[status] || 'bg-secondary';
}

function showFormMessage(message, type = 'info') {
  const messageDiv = document.getElementById('formMessage');
  messageDiv.textContent = message;
  messageDiv.className = `alert alert-${type}`;
  messageDiv.classList.remove('d-none');

  // Auto-hide after 5 seconds
  setTimeout(() => {
    messageDiv.classList.add('d-none');
  }, 5000);
}

function showErrorMessage(message) {
  const container = document.getElementById('jobHeader');
  container.innerHTML = `
    <div class="alert alert-danger">
      <i class="fas fa-exclamation-triangle"></i>
      ${message}
    </div>
  `;
}

// ============================================================================
// EXPORT FUNCTIONS
// ============================================================================

window.JobDetails = {
  loadJobDetails,
  loadProposals
};

console.log('✅ Job Details Page Ready');
```

---

## Example 3: profile-provider.html Page

### JavaScript Implementation (pages/profile-provider.js)
```javascript
// ============================================================================
// PROVIDER PROFILE PAGE - JavaScript Implementation
// File: pages/profile-provider.js
// ============================================================================

console.log('🚀 Provider Profile Page Loaded');

let currentProviderId = null;
let currentProfile = null;

// ============================================================================
// INITIALIZE PAGE
// ============================================================================

document.addEventListener('DOMContentLoaded', async () => {
  console.log('📄 DOM Content Loaded');
  
  // Get provider ID from URL
  const urlParams = new URLSearchParams(window.location.search);
  currentProviderId = urlParams.get('id') || localStorage.getItem('userId');

  if (!currentProviderId) {
    showError('No provider specified');
    return;
  }

  // Load profile and all related data
  await loadProviderProfile();
});

// ============================================================================
// LOAD PROVIDER PROFILE
// ============================================================================

async function loadProviderProfile() {
  try {
    console.log(`📡 Loading provider #${currentProviderId} profile...`);

    // Show loading state
    const container = document.getElementById('profileContainer');
    if (container) {
      container.innerHTML = `
        <div class="text-center py-5">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-3">Loading provider profile...</p>
        </div>
      `;
    }

    // Fetch profile
    const response = await apiCall(`/providers/${currentProviderId}/profile`, 'GET');

    if (!response.success) {
      throw new Error(response.error);
    }

    currentProfile = response.data;
    console.log('✅ Profile loaded:', currentProfile);

    // Render all profile sections
    await renderProfileSections();
  } catch (error) {
    console.error('❌ Error loading profile:', error);
    showError(`Failed to load profile: ${error.message}`);
  }
}

// ============================================================================
// RENDER PROFILE SECTIONS
// ============================================================================

async function renderProfileSections() {
  if (!currentProfile) return;

  // 1. Profile Header
  renderProfileHeader();

  // 2. About Section
  renderAboutSection();

  // 3. Skills
  renderSkills();

  // 4. Portfolio
  renderPortfolio();

  // 5. Reviews (fetch separately)
  await renderReviews();

  // 6. Certifications
  renderCertifications();

  // 7. Languages
  renderLanguages();

  console.log('✅ All profile sections rendered');
}

// ============================================================================
// RENDER PROFILE HEADER
// ============================================================================

function renderProfileHeader() {
  const header = document.getElementById('profileHeader');

  if (!header) return;

  header.innerHTML = `
    <div class="card">
      <div class="card-body text-center">
        <img src="${currentProfile.avatar_url || '/assets/default-avatar.png'}" 
          alt="${currentProfile.name}" class="rounded-circle" width="120">

        <h2 class="mt-3">${currentProfile.name}</h2>

        <div class="mt-2">
          <span class="text-warning">
            ${'⭐'.repeat(Math.floor(currentProfile.average_rating))}
            <span class="text-muted">${currentProfile.average_rating.toFixed(1)}</span>
          </span>
          <span class="text-muted ms-2">(${currentProfile.total_reviews} reviews)</span>
        </div>

        <p class="text-muted mt-2">
          <i class="fas fa-briefcase"></i> ${currentProfile.service_category}
        </p>

        <p class="text-muted">
          <i class="fas fa-map-marker-alt"></i> ${currentProfile.location}
        </p>

        <p class="text-muted">
          <i class="fas fa-clock"></i> Response time: ${currentProfile.response_time} hours
        </p>

        ${currentProfile.verified ? `
          <span class="badge bg-success">
            <i class="fas fa-check-circle"></i> Verified
          </span>
        ` : ''}

        <div class="mt-4">
          <button class="btn btn-primary" onclick="contactProvider()">
            <i class="fas fa-envelope"></i> Contact
          </button>
          <button class="btn btn-outline-secondary ms-2" onclick="shareProfile()">
            <i class="fas fa-share"></i> Share
          </button>
        </div>
      </div>
    </div>
  `;
}

// ============================================================================
// RENDER ABOUT SECTION
// ============================================================================

function renderAboutSection() {
  const aboutSection = document.getElementById('aboutSection');

  if (!aboutSection) return;

  aboutSection.innerHTML = `
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">About</h5>
      </div>
      <div class="card-body">
        <p>${currentProfile.bio || 'No bio provided'}</p>

        <div class="row mt-4">
          <div class="col-md-4">
            <small class="text-muted">EXPERIENCE</small>
            <p class="h5">${currentProfile.years_of_experience} years</p>
          </div>
          <div class="col-md-4">
            <small class="text-muted">HOURLY RATE</small>
            <p class="h5">₹${currentProfile.hourly_rate || 'Negotiable'}</p>
          </div>
          <div class="col-md-4">
            <small class="text-muted">MEMBER SINCE</small>
            <p class="h5">${FreelanceMarketplace.formatDate(currentProfile.created_at)}</p>
          </div>
        </div>
      </div>
    </div>
  `;
}

// ============================================================================
// RENDER SKILLS
// ============================================================================

function renderSkills() {
  const skillsSection = document.getElementById('skillsSection');

  if (!skillsSection || !currentProfile.skills) return;

  skillsSection.innerHTML = `
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Skills & Expertise</h5>
      </div>
      <div class="card-body">
        <div>
          ${currentProfile.skills.map(skill => `
            <span class="badge bg-primary me-2 mb-2" style="padding: 8px 12px; font-size: 14px;">
              ${skill.name}
              ${skill.verified ? '<i class="fas fa-check-circle ms-1"></i>' : ''}
            </span>
          `).join('')}
        </div>
      </div>
    </div>
  `;
}

// ============================================================================
// RENDER PORTFOLIO
// ============================================================================

function renderPortfolio() {
  const portfolioSection = document.getElementById('portfolioSection');

  if (!portfolioSection || !currentProfile.portfolio) return;

  portfolioSection.innerHTML = `
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Portfolio (${currentProfile.portfolio.length} projects)</h5>
      </div>
      <div class="card-body">
        <div class="row">
          ${currentProfile.portfolio.map(project => `
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="card h-100">
                <img src="${project.image_url}" class="card-img-top" alt="${project.title}"
                  style="height: 200px; object-fit: cover;">
                <div class="card-body">
                  <h6 class="card-title">${project.title}</h6>
                  <p class="card-text small">${project.description}</p>
                  <div class="d-flex justify-content-between">
                    <span class="text-warning">${'⭐'.repeat(project.rating)}</span>
                    <small class="text-muted">${FreelanceMarketplace.formatDate(project.completion_date)}</small>
                  </div>
                </div>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    </div>
  `;
}

// ============================================================================
// RENDER REVIEWS
// ============================================================================

async function renderReviews() {
  const reviewsSection = document.getElementById('reviewsSection');

  if (!reviewsSection) return;

  try {
    // Fetch reviews
    const response = await apiCall(`/providers/${currentProviderId}/reviews`, 'GET');

    if (!response.success) {
      throw new Error(response.error);
    }

    const reviews = response.data;

    reviewsSection.innerHTML = `
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Reviews (${reviews.length})</h5>
        </div>
        <div class="card-body">
          ${reviews.map(review => `
            <div class="border-bottom pb-3 mb-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h6>${review.client_name}</h6>
                  <small class="text-warning">${'⭐'.repeat(review.rating)}</small>
                </div>
                <small class="text-muted">${FreelanceMarketplace.formatDate(review.created_date)}</small>
              </div>
              <p class="mt-2 mb-0">${review.review_text}</p>
              ${review.provider_response ? `
                <div class="alert alert-light mt-2 p-2">
                  <small><strong>Response:</strong> ${review.provider_response}</small>
                </div>
              ` : ''}
            </div>
          `).join('')}
        </div>
      </div>
    `;
  } catch (error) {
    console.error('Error loading reviews:', error);
    reviewsSection.innerHTML = `
      <div class="alert alert-danger">Failed to load reviews</div>
    `;
  }
}

// ============================================================================
// RENDER CERTIFICATIONS
// ============================================================================

function renderCertifications() {
  const certsSection = document.getElementById('certificationsSection');

  if (!certsSection || !currentProfile.certifications) return;

  certsSection.innerHTML = `
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Certifications</h5>
      </div>
      <div class="card-body">
        ${currentProfile.certifications.length > 0 ? `
          <div class="list-group">
            ${currentProfile.certifications.map(cert => `
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-1">
                      <i class="fas fa-certificate text-success"></i> ${cert.certification_name}
                    </h6>
                    <small class="text-muted">${cert.issuing_body}</small>
                  </div>
                  <small class="text-muted">${FreelanceMarketplace.formatDate(cert.issue_date)}</small>
                </div>
              </div>
            `).join('')}
          </div>
        ` : `
          <p class="text-muted">No certifications added</p>
        `}
      </div>
    </div>
  `;
}

// ============================================================================
// RENDER LANGUAGES
// ============================================================================

function renderLanguages() {
  const langsSection = document.getElementById('languagesSection');

  if (!langsSection || !currentProfile.languages) return;

  langsSection.innerHTML = `
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Languages</h5>
      </div>
      <div class="card-body">
        ${currentProfile.languages.map(lang => `
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span>${lang.language_name}</span>
            <small class="badge bg-secondary">${lang.proficiency_level}</small>
          </div>
        `).join('')}
      </div>
    </div>
  `;
}

// ============================================================================
// ACTION FUNCTIONS
// ============================================================================

function contactProvider() {
  console.log('💬 Contacting provider...');
  // Navigate to messaging page
  window.location.href = `/messaging.html?provider=${currentProviderId}`;
}

function shareProfile() {
  console.log('📤 Sharing profile...');

  const url = window.location.href;
  if (navigator.share) {
    navigator.share({
      title: `${currentProfile.name} - ServiceHub Provider`,
      url: url
    });
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(url);
    alert('Profile link copied to clipboard!');
  }
}

function showError(message) {
  const container = document.getElementById('profileContainer');
  if (container) {
    container.innerHTML = `
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> ${message}
      </div>
    `;
  }
}

// ============================================================================
// EXPORT FUNCTIONS
// ============================================================================

window.ProviderProfile = {
  loadProviderProfile,
  contactProvider,
  shareProfile
};

console.log('✅ Provider Profile Page Ready');
```

---

## Example 4: Authentication Flow

### JavaScript Implementation (pages/auth-flow.js)
```javascript
// ============================================================================
// AUTHENTICATION FLOW - JavaScript Implementation
// File: pages/auth-flow.js
// ============================================================================

console.log('🔐 Authentication Module Loaded');

// ============================================================================
// LOGIN
// ============================================================================

async function handleLogin(event) {
  event.preventDefault();

  const email = document.getElementById('loginEmail').value;
  const password = document.getElementById('loginPassword').value;

  console.log('📤 Attempting login...');

  // Show loading state
  const button = event.target.querySelector('button[type="submit"]');
  const originalText = button.textContent;
  button.disabled = true;
  button.textContent = 'Logging in...';

  try {
    // Send credentials to API
    const response = await apiCall('/auth/login', 'POST', {
      email,
      password,
      timestamp: new Date().toISOString()
    });

    if (response.success) {
      console.log('✅ Login successful!');

      // Store authentication data
      const token = response.data.token;
      const userId = response.data.user_id;
      const userRole = response.data.role;
      const userName = response.data.name;

      localStorage.setItem('authToken', token);
      localStorage.setItem('userId', userId);
      localStorage.setItem('userRole', userRole);
      localStorage.setItem('userName', userName);

      // Show success message
      showMessage('Login successful! Redirecting...', 'success');

      // Redirect based on role
      setTimeout(() => {
        if (userRole === 'Client') {
          window.location.href = '/dashboard-client.html';
        } else if (userRole === 'Provider') {
          window.location.href = '/dashboard-provider.html';
        } else {
          window.location.href = '/';
        }
      }, 1500);
    } else {
      console.error('❌ Login failed:', response.error);
      showMessage(`Login failed: ${response.error}`, 'danger');
    }
  } catch (error) {
    console.error('Error:', error);
    showMessage(`Error: ${error.message}`, 'danger');
  } finally {
    button.disabled = false;
    button.textContent = originalText;
  }
}

// ============================================================================
// REGISTRATION
// ============================================================================

async function handleRegistration(event) {
  event.preventDefault();

  // Get form data
  const firstName = document.getElementById('regFirstName').value;
  const lastName = document.getElementById('regLastName').value;
  const email = document.getElementById('regEmail').value;
  const phone = document.getElementById('regPhone').value;
  const password = document.getElementById('regPassword').value;
  const address = document.getElementById('regAddress').value;
  const city = document.getElementById('regCity').value;
  const role = document.querySelector('input[name="role"]:checked').value;

  console.log('📤 Attempting registration...');

  // Show loading state
  const button = event.target.querySelector('button[type="submit"]');
  const originalText = button.textContent;
  button.disabled = true;
  button.textContent = 'Creating account...';

  try {
    const userData = {
      first_name: firstName,
      last_name: lastName,
      email,
      phone,
      password,
      address,
      city,
      role,
      created_at: new Date().toISOString()
    };

    // Add provider-specific fields
    if (role === 'Provider') {
      userData.service_category = document.getElementById('serviceCategory').value;
      userData.years_of_experience = document.getElementById('experience').value;
    }

    // Send registration data to API
    const response = await apiCall('/auth/register', 'POST', userData);

    if (response.success) {
      console.log('✅ Registration successful!');

      showMessage('Account created successfully! Logging you in...', 'success');

      // Auto-login after registration
      setTimeout(() => {
        handleLogin({
          preventDefault: () => {},
          target: {
            querySelector: () => ({ textContent: '', disabled: false })
          }
        });
      }, 2000);
    } else {
      console.error('❌ Registration failed:', response.error);
      showMessage(`Registration failed: ${response.error}`, 'danger');
    }
  } catch (error) {
    console.error('Error:', error);
    showMessage(`Error: ${error.message}`, 'danger');
  } finally {
    button.disabled = false;
    button.textContent = originalText;
  }
}

// ============================================================================
// LOGOUT
// ============================================================================

function handleLogout() {
  console.log('🚪 Logging out...');

  // Clear stored data
  localStorage.removeItem('authToken');
  localStorage.removeItem('userId');
  localStorage.removeItem('userRole');
  localStorage.removeItem('userName');

  // Redirect to home
  window.location.href = '/';
}

// ============================================================================
// CHECK AUTHENTICATION
// ============================================================================

function isAuthenticated() {
  return !!localStorage.getItem('authToken');
}

function redirectIfNotAuthenticated() {
  if (!isAuthenticated()) {
    window.location.href = '/auth.html';
  }
}

function redirectIfAuthenticated() {
  if (isAuthenticated()) {
    const role = localStorage.getItem('userRole');
    if (role === 'Client') {
      window.location.href = '/dashboard-client.html';
    } else {
      window.location.href = '/dashboard-provider.html';
    }
  }
}

// ============================================================================
// MESSAGE DISPLAY
// ============================================================================

function showMessage(message, type = 'info') {
  const messageDiv = document.getElementById('authMessage') || createMessageDiv();
  messageDiv.textContent = message;
  messageDiv.className = `alert alert-${type} alert-dismissible fade show`;
  messageDiv.innerHTML += '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';

  // Auto-hide after 5 seconds
  setTimeout(() => {
    messageDiv.classList.remove('show');
  }, 5000);
}

function createMessageDiv() {
  const div = document.createElement('div');
  div.id = 'authMessage';
  div.style.position = 'fixed';
  div.style.top = '20px';
  div.style.right = '20px';
  div.style.zIndex = '9999';
  div.style.minWidth = '300px';
  document.body.appendChild(div);
  return div;
}

// ============================================================================
// EXPORT FUNCTIONS
// ============================================================================

window.AuthFlow = {
  handleLogin,
  handleRegistration,
  handleLogout,
  isAuthenticated,
  redirectIfNotAuthenticated,
  redirectIfAuthenticated,
  showMessage
};

console.log('✅ Authentication Module Ready');
```

---

## Summary

These examples show:

1. **Jobs List Page** - Fetching and rendering jobs with filters
2. **Job Details Page** - Loading job details and submitting proposals
3. **Provider Profile Page** - Displaying comprehensive provider information
4. **Authentication** - Login, registration, and logout flows

**Key Patterns:**
- Fetch data from API
- Convert to JavaScript objects
- Create DOM elements
- Insert into page
- Handle errors and loading states
- Store user data in localStorage
- Redirect based on authentication

---

**Document Version:** 1.0
**Last Updated:** October 24, 2025
**Status:** Complete & Ready for Implementation

