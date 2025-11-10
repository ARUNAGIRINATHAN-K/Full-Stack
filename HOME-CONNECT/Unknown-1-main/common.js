/* ============================================================================
   COMMON.JS - Global JavaScript Functions
   ============================================================================
   
   Features:
   - Preloader Animation
   - Back-to-Top Button
   - Fun-Fact Counters (Animated)
   - Geolocation API Integration
   - Multilevel Dropdown Menu
   - Utility Functions
   
   ========================================================================== */

// ============================================================================
// PRELOADER
// ============================================================================

document.addEventListener('DOMContentLoaded', function() {
  // Hide preloader when page is fully loaded
  setTimeout(() => {
    const preloader = document.querySelector('.preloader');
    if (preloader) {
      preloader.classList.add('hidden');
    }
  }, 800);
});

// ============================================================================
// BACK-TO-TOP BUTTON
// ============================================================================

const backToTopButton = document.querySelector('.back-to-top');

if (backToTopButton) {
  window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
      backToTopButton.classList.add('show');
    } else {
      backToTopButton.classList.remove('show');
    }
  });

  backToTopButton.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}

// ============================================================================
// FUN-FACT COUNTERS WITH ANIMATION
// ============================================================================

function animateCounter(element, target, duration = 2000) {
  const start = 0;
  const increment = target / (duration / 16); // 60fps
  let current = start;

  const updateCounter = () => {
    current += increment;
    if (current < target) {
      element.textContent = Math.floor(current) + (element.dataset.suffix || '');
      requestAnimationFrame(updateCounter);
    } else {
      element.textContent = target + (element.dataset.suffix || '');
    }
  };

  updateCounter();
}

function initializeFunFactCounters() {
  const counters = document.querySelectorAll('.fun-fact-number');
  let hasAnimated = false;

  const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !hasAnimated) {
        counters.forEach(counter => {
          const target = parseInt(counter.getAttribute('data-target')) || 0;
          const suffix = counter.getAttribute('data-suffix') || '';
          counter.dataset.suffix = suffix;
          animateCounter(counter, target);
        });
        hasAnimated = true;
        observer.disconnect();
      }
    });
  }, observerOptions);

  const funFactsSection = document.querySelector('.fun-facts');
  if (funFactsSection) {
    observer.observe(funFactsSection);
  }
}

// Initialize on page load
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeFunFactCounters);
} else {
  initializeFunFactCounters();
}

// ============================================================================
// GEOLOCATION API
// ============================================================================

function getUserLocation(callback) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude, accuracy } = position.coords;
        callback(null, {
          latitude,
          longitude,
          accuracy,
          success: true
        });
      },
      (error) => {
        console.error('Geolocation error:', error);
        callback(error, null);
      },
      {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
      }
    );
  } else {
    callback(new Error('Geolocation not supported'), null);
  }
}

// Setup geolocation buttons
function initializeGeolocationButtons() {
  const geoButtons = document.querySelectorAll('[data-action="get-location"]');

  geoButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const targetInput = this.getAttribute('data-target');
      const inputElement = document.querySelector(targetInput);

      if (!inputElement) {
        console.error('Target input element not found:', targetInput);
        return;
      }

      // Show loading state
      const originalText = this.textContent;
      this.textContent = 'Getting location...';
      this.disabled = true;

      getUserLocation((error, location) => {
        if (error) {
          alert('Unable to get your location. Please enable geolocation services.');
          console.error('Geolocation error:', error);
        } else {
          // Store coordinates in hidden inputs or display them
          inputElement.value = `Lat: ${location.latitude.toFixed(4)}, Lng: ${location.longitude.toFixed(4)}`;
          
          // Store raw values if there are separate fields
          const latInput = document.querySelector(targetInput + '-lat');
          const lngInput = document.querySelector(targetInput + '-lng');
          
          if (latInput) latInput.value = location.latitude;
          if (lngInput) lngInput.value = location.longitude;

          console.log('Location obtained:', location);
        }

        // Restore button state
        this.textContent = originalText;
        this.disabled = false;
      });
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeGeolocationButtons);
} else {
  initializeGeolocationButtons();
}

// ============================================================================
// MULTILEVEL DROPDOWN MENU
// ============================================================================

function initializeMultilevelDropdowns() {
  const dropdowns = document.querySelectorAll('.dropdown-submenu');

  dropdowns.forEach(dropdown => {
    dropdown.addEventListener('mouseenter', function() {
      const submenu = this.querySelector('.dropdown-menu');
      if (submenu) {
        submenu.style.display = 'block';
      }
    });

    dropdown.addEventListener('mouseleave', function() {
      const submenu = this.querySelector('.dropdown-menu');
      if (submenu) {
        submenu.style.display = 'none';
      }
    });
  });

  // Mobile/touch support
  const dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      if (window.innerWidth < 768) {
        e.preventDefault();
        e.stopPropagation();
        
        const parent = this.closest('.dropdown') || this.closest('.nav-item');
        if (parent) {
          const menu = parent.querySelector('.dropdown-menu');
          if (menu) {
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
          }
        }
      }
    });
  });

  // Close dropdowns on click outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown') && !e.target.closest('[data-bs-toggle="dropdown"]')) {
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.style.display = 'none';
      });
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeMultilevelDropdowns);
} else {
  initializeMultilevelDropdowns();
}

// ============================================================================
// FORM VALIDATION
// ============================================================================

function validateForm(formElement) {
  const inputs = formElement.querySelectorAll('input, textarea, select');
  let isValid = true;

  inputs.forEach(input => {
    if (!input.value.trim()) {
      input.classList.add('is-invalid');
      isValid = false;
    } else {
      input.classList.remove('is-invalid');
    }

    // Email validation
    if (input.type === 'email' && input.value.trim()) {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(input.value)) {
        input.classList.add('is-invalid');
        isValid = false;
      }
    }
  });

  return isValid;
}

// ============================================================================
// SETUP FORM HANDLERS
// ============================================================================

function initializeFormHandlers() {
  const forms = document.querySelectorAll('form[data-validate="true"]');

  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      if (!validateForm(this)) {
        e.preventDefault();
        alert('Please fill in all required fields correctly.');
      }
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeFormHandlers);
} else {
  initializeFormHandlers();
}

// ============================================================================
// PAGINATION
// ============================================================================

function setupPagination(totalItems, itemsPerPage, onPageChange) {
  const totalPages = Math.ceil(totalItems / itemsPerPage);
  const paginationContainer = document.querySelector('[data-pagination-container]');

  if (!paginationContainer) return;

  const createPaginationHTML = (currentPage = 1) => {
    let html = '<nav aria-label="Pagination"><ul class="pagination justify-content-center">';

    // Previous button
    if (currentPage > 1) {
      html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;
    } else {
      html += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      if (i === currentPage) {
        html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
      } else {
        html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
      }
    }

    // Next button
    if (currentPage < totalPages) {
      html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
    } else {
      html += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    html += '</ul></nav>';
    return html;
  };

  paginationContainer.innerHTML = createPaginationHTML(1);

  paginationContainer.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && e.target.hasAttribute('data-page')) {
      e.preventDefault();
      const page = parseInt(e.target.getAttribute('data-page'));
      paginationContainer.innerHTML = createPaginationHTML(page);
      onPageChange(page);
    }
  });
}

// ============================================================================
// SEARCH & FILTER
// ============================================================================

function filterItems(items, searchTerm, filterKey) {
  return items.filter(item => {
    if (!searchTerm) return true;
    return item[filterKey]?.toLowerCase().includes(searchTerm.toLowerCase());
  });
}

function setupSearchFilter(searchInputSelector, itemsSelector, filterKey = 'title') {
  const searchInput = document.querySelector(searchInputSelector);
  const items = document.querySelectorAll(itemsSelector);

  if (!searchInput) return;

  searchInput.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();

    items.forEach(item => {
      const text = item.textContent.toLowerCase();
      if (text.includes(searchTerm)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });

    // Show "no results" message if needed
    const visibleItems = Array.from(items).filter(item => item.style.display !== 'none');
    if (visibleItems.length === 0) {
      console.log('No items match the search term');
    }
  });
}

// ============================================================================
// TAB FUNCTIONALITY
// ============================================================================

function initializeTabsWithContent() {
  const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');

  tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(e) {
      const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
      if (targetPane) {
        targetPane.classList.add('animate-fade-in');
      }
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeTabsWithContent);
} else {
  initializeTabsWithContent();
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

// Format currency
function formatCurrency(amount, currency = 'USD') {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currency
  }).format(amount);
}

// Format date
function formatDate(date) {
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  }).format(new Date(date));
}

// Debounce function
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Throttle function
function throttle(func, limit) {
  let inThrottle;
  return function(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
}

// Add active class to current nav item
function setActiveNavItem() {
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  const navLinks = document.querySelectorAll('.nav-link');

  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', setActiveNavItem);
} else {
  setActiveNavItem();
}

// ============================================================================
// API COMMUNICATION LAYER
// ============================================================================
// Service categories (used across nav + forms)
const SERVICE_CATEGORIES = [
  'House Cleaning', 'Cooking & Catering', 'Laundry & Ironing', 'Appliance Repair',
  'Home Tutoring', 'Childcare & Babysitting', 'Elderly Care', 'Interior Decoration',
  'Electricians & Plumbers', 'Carpenters & Painters', 'Gardening & Landscaping',
  'Packers & Movers', 'Vehicle Cleaning & Maintenance', 'Pet Grooming & Sitting',
  'Tailoring & Alterations', 'Event Support'
];

function populateServicesCategories() {
  const menu = document.getElementById('services-categories-menu');
  if (menu) {
    menu.innerHTML = SERVICE_CATEGORIES.map(c => `<li><a class="dropdown-item" href="jobs-list.html?category=${encodeURIComponent(c)}">${c}</a></li>`).join('');
  }
  const projectCategory = document.getElementById('projectCategory');
  if (projectCategory && projectCategory.tagName === 'SELECT') {
    if (!projectCategory.dataset.populated) {
      projectCategory.innerHTML = '<option selected disabled>Select a category...</option>' +
        SERVICE_CATEGORIES.map(c => `<option value="${c}">${c}</option>`).join('');
      projectCategory.dataset.populated = 'true';
    }
  }
}

// ===== Provider-specific API helpers =====
async function fetchProviderProposals() {
  const res = await apiCall('/providers/me/proposals', 'GET');
  return res.success ? res.data : [];
}

async function fetchProviderActiveProjects() {
  const res = await apiCall('/providers/me/active-projects', 'GET');
  return res.success ? res.data : [];
}

async function fetchOpenJobs(filters = {}) {
  const q = { ...filters, status: 'open' };
  return await fetchJobs(q);
}
// This section demonstrates how JavaScript communicates with the backend:
// 1. SENDING DATA: Captures form data and sends it to backend API as JSON
// 2. RECEIVING DATA: Gets JSON response and dynamically builds HTML

/**
 * Base API Configuration
 * @description Central point for all API calls
 */
const API_CONFIG = {
  baseURL: 'http://localhost:3000/api', // Backend server URL
  timeout: 10000, // 10 seconds timeout
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
};

// Simple auth helpers
function getCurrentUser() {
  const token = localStorage.getItem('authToken');
  if (!token) return null;
  return {
    id: Number(localStorage.getItem('userId')) || null,
    role: localStorage.getItem('userRole') || null,
    name: localStorage.getItem('userName') || null,
    token
  };
}

function ensureRole(requiredRole, options = {}) {
  const user = getCurrentUser();
  if (!user) {
    showToast('Please sign in to continue', 'warning');
    if (options.redirect !== false) {
      setTimeout(() => window.location.href = options.redirectTo || 'auth.html', 600);
    }
    return false;
  }
  if (requiredRole && user.role !== requiredRole) {
    showToast(`This action requires a ${requiredRole} account`, 'warning');
    return false;
  }
  return true;
}

/**
 * FETCH HELPER FUNCTION
 * @description Wrapper for fetch() with error handling and timeout
 * @example
 * const response = await apiCall('/jobs', 'GET');
 * const newJob = await apiCall('/jobs', 'POST', { title: 'Fix Plumbing' });
 */
async function apiCall(endpoint, method = 'GET', data = null) {
  const url = `${API_CONFIG.baseURL}${endpoint}`;
  const headers = { ...API_CONFIG.headers };
  const token = localStorage.getItem('authToken');
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const options = {
    method,
    headers,
    timeout: API_CONFIG.timeout
  };

  // Add request body for POST, PUT, DELETE
  if (data) {
    options.body = JSON.stringify(data);
  }

  try {
    const response = await fetch(url, options);

    // Handle HTTP errors
    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || `HTTP ${response.status}: ${response.statusText}`);
    }

    // Parse JSON response
    const jsonData = await response.json();
    return {
      success: true,
      data: jsonData,
      status: response.status
    };
  } catch (error) {
    console.error('API Error:', error);
    return {
      success: false,
      error: error.message,
      status: null
    };
  }
}

// ============================================================================
// EXAMPLE 1: FETCHING JOBS LIST
// ============================================================================
// When user navigates to jobs-list.html, JS fetches data from API
// and dynamically builds job cards

/**
 * FETCH JOBS FROM DATABASE
 * @description Retrieves job listings from backend API
 * Flow: API → Database → JSON Response → Dynamic HTML
 */
async function fetchJobs(filters = {}) {
  console.log('📡 Fetching jobs from API...');
  
  // Build query string from filters
  const queryParams = new URLSearchParams();
  if (filters.category) queryParams.append('category', filters.category);
  if (filters.location) queryParams.append('location', filters.location);
  if (filters.minBudget) queryParams.append('minBudget', filters.minBudget);
  if (filters.maxBudget) queryParams.append('maxBudget', filters.maxBudget);
  if (filters.sort) queryParams.append('sort', filters.sort);

  const endpoint = `/jobs?${queryParams.toString()}`;
  const response = await apiCall(endpoint, 'GET');

  if (response.success) {
    console.log('✅ Jobs fetched successfully:', response.data);
    return response.data; // Array of job objects from database
  } else {
    console.error('❌ Failed to fetch jobs:', response.error);
    return [];
  }
}

/**
 * RENDER JOBS - DYNAMICALLY BUILD HTML
 * @description Takes job data from API and creates DOM elements
 * This is where JavaScript transforms data into user interface
 */
function renderJobs(jobs) {
  const jobsContainer = document.getElementById('jobs-container');
  
  if (!jobsContainer) {
    console.warn('Jobs container not found');
    return;
  }

  // Clear existing content
  jobsContainer.innerHTML = '';

  // If no jobs, show message
  if (!jobs || jobs.length === 0) {
    jobsContainer.innerHTML = `
      <div class="alert alert-info text-center py-5">
        <i class="fas fa-inbox"></i>
        <p class="mt-2">No jobs found matching your criteria.</p>
      </div>
    `;
    return;
  }

  // Iterate through job data and CREATE HTML for each job
  jobs.forEach(job => {
    const jobCard = createJobCard(job);
    jobsContainer.appendChild(jobCard);
  });

  console.log(`✅ Rendered ${jobs.length} job cards`);
}

/**
 * CREATE JOB CARD ELEMENT
 * @description Builds a single job card using data from database
 * Example: { job_id: 1, title: 'Fix Leaky Faucet', budget: 500, ... }
 */
function createJobCard(job) {
  const card = document.createElement('div');
  card.className = 'col-md-6 col-lg-4 mb-4';
  card.innerHTML = `
    <div class="card job-card h-100 shadow-sm hover-lift">
      <div class="card-body">
        <!-- Job Title -->
        <h5 class="card-title text-truncate">${job.title || 'Untitled Job'}</h5>
        
        <!-- Category Badge -->
        <span class="badge bg-primary mb-2">${job.category || 'General'}</span>
        
        <!-- Job Description -->
        <p class="card-text text-muted small">${job.description?.substring(0, 100) || ''}...</p>
        
        <!-- Client Info -->
        <div class="d-flex align-items-center mb-3 small">
          <i class="fas fa-user-circle me-2"></i>
          <span>${job.client_name || 'Client'}</span>
          <span class="ms-auto text-warning">
            ${'⭐'.repeat(Math.min(5, Math.ceil(job.client_rating || 0)))}
          </span>
        </div>
        
        <!-- Budget & Location -->
        <div class="d-flex justify-content-between mb-3 text-sm">
          <div>
            <strong>₹${(job.budget_min ?? 0).toLocaleString()}${job.budget_max ? ' - ₹' + job.budget_max.toLocaleString() : ''}</strong>
          </div>
          <div>
            <i class="fas fa-map-marker-alt me-1"></i>${job.location || 'N/A'}
          </div>
        </div>
        
        <!-- Proposal Count -->
        <small class="text-muted d-block mb-3">
          <i class="fas fa-file-alt me-1"></i>${job.proposal_count || 0} proposals
        </small>
        
        <!-- Action Button -->
        <button class="btn btn-sm btn-primary w-100" onclick="window.location.href='job-details.html?id=${job.id}'">
          View & Propose <i class="fas fa-arrow-right ms-1"></i>
        </button>
      </div>
    </div>
  `;
  
  return card;
}

/**
 * LOAD & DISPLAY JOBS
 * @description Main function that chains fetching and rendering
 * This demonstrates the complete flow: Database → API → JS → HTML DOM
 */
async function loadAndDisplayJobs(filters = {}) {
  console.log('🔄 Loading jobs workflow started...');
  
  // Show loading state
  const container = document.getElementById('jobs-container');
  if (container) {
    container.innerHTML = `
      <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Fetching available jobs...</p>
      </div>
    `;
  }

  // Step 1: FETCH data from API (which queries the database)
  const jobs = await fetchJobs(filters);

  // Step 2: RENDER the data as HTML (dynamic DOM manipulation)
  renderJobs(jobs);
}

// ============================================================================
// EXAMPLE 2: SENDING DATA - JOB POSTING
// ============================================================================
// When user submits a job posting form, JS sends data to API

/**
 * POST NEW JOB
 * @description Captures form data and SENDS it to backend API
 * This demonstrates: Form Data → JSON → API → Database INSERT
 */
async function submitJobPosting(formData) {
  console.log('📤 Sending job posting to API...');

  // Guard: client only
  if (!ensureRole('client')) {
    return { success: false, message: 'Sign in as a client to post jobs' };
  }

  // Prepare data object from form
  const jobData = {
    title: formData.get('projectTitle') || formData.get('jobTitle'),
    description: formData.get('projectDescription') || formData.get('jobDescription'),
    category: formData.get('projectCategory') || formData.get('serviceCategory'),
    budget_min: parseFloat(formData.get('projectBudget') || formData.get('budgetMin') || 0),
    budget_max: formData.get('budgetMax') ? parseFloat(formData.get('budgetMax')) : null,
    location: formData.get('projectLocation') || formData.get('location')
  };

  console.log('📋 Job data to be sent:', jobData);

  // SEND data to backend API
  const response = await apiCall('/jobs', 'POST', jobData);

  if (response.success) {
    console.log('✅ Job posted successfully!', response.data);
    const created = response.data;
    return {
      success: true,
      jobId: created.id,
      message: 'Your job has been posted and is now visible to service providers!'
    };
  } else {
    console.error('❌ Failed to post job:', response.error);
    return {
      success: false,
      message: response.error
    };
  }
}

/**
 * SETUP JOB FORM SUBMISSION
 * @description Attach handler to job posting form
 */
function initializeJobPostingForm() {
  const jobForm = document.getElementById('jobPostingForm');
  const altForm = document.getElementById('jobPostForm');
  const form = jobForm || altForm;
  
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!ensureRole('client')) return;

    const formData = new FormData(form);
    const result = await submitJobPosting(formData);

    if (result.success) {
      alert(result.message);
      form.reset();
      // Redirect to job details page
      window.location.href = `/job-details.html?id=${result.jobId}`;
    } else {
      showToast(result.message || 'Failed to post job', 'danger');
    }
  });
}

// Unified fetchJobs to support paginated response
// Returns array of job rows
async function fetchJobs(filters = {}) {
  const queryParams = new URLSearchParams();
  if (filters.category) queryParams.append('category', filters.category);
  if (filters.location) queryParams.append('location', filters.location);
  if (filters.minBudget) queryParams.append('minBudget', filters.minBudget);
  if (filters.maxBudget) queryParams.append('maxBudget', filters.maxBudget);
  if (filters.q) queryParams.append('q', filters.q);
  const endpoint = `/jobs?${queryParams.toString()}`;
  const response = await apiCall(endpoint, 'GET');
  if (!response.success) return [];
  const data = response.data;
  if (Array.isArray(data)) return data;
  if (data && Array.isArray(data.results)) return data.results;
  return [];
}

// Toast helper
function showToast(message, type = 'info') {
  const wrap = document.createElement('div');
  wrap.className = `position-fixed top-0 end-0 p-3`;
  wrap.style.zIndex = 1080;
  const id = `toast-${Date.now()}`;
  wrap.innerHTML = `
    <div id="${id}" class="toast align-items-center text-bg-${type}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>`;
  document.body.appendChild(wrap);
  const toastEl = wrap.querySelector('.toast');
  const bsToast = new bootstrap.Toast(toastEl);
  bsToast.show();
  toastEl.addEventListener('hidden.bs.toast', () => wrap.remove());
}

// Update job status
async function updateJobStatus(jobId, status) {
  const res = await apiCall(`/jobs/${jobId}/status`, 'PUT', { status });
  if (res.success) {
    showToast(`Job ${status}`, 'success');
    return true;
  }
  showToast(res.error || 'Failed to update job status', 'danger');
  return false;
}

// Apply to a job (provider only)
async function applyToJob(jobId, { coverLetter, bidAmount, timelineDays } = {}) {
  if (!ensureRole('provider')) return { success: false, message: 'Sign in as a provider to apply' };
  const payload = {
    cover_letter: coverLetter || null,
    bid_amount: bidAmount != null ? Number(bidAmount) : null,
    timeline_days: timelineDays != null ? Number(timelineDays) : null
  };
  const res = await apiCall(`/jobs/${jobId}/apply`, 'POST', payload);
  if (res.success) {
    showToast('Proposal submitted!', 'success');
    return { success: true, id: res.data?.id };
  }
  showToast(res.error || 'Failed to submit proposal', 'danger');
  return { success: false, message: res.error };
}

// ============================================================================
// EXAMPLE 3: FETCHING PROVIDER PROFILE
// ============================================================================
// When viewing a provider's profile, fetch their data including reviews

/**
 * FETCH PROVIDER PROFILE
 * @description Gets provider data from database through API
 * Includes: profile info, skills, reviews, certifications
 */
async function fetchProviderProfile(providerId) {
  console.log(`📡 Fetching provider profile #${providerId}...`);

  const response = await apiCall(`/providers/${providerId}/profile`, 'GET');

  if (response.success) {
    console.log('✅ Provider profile loaded:', response.data);
    return response.data;
  } else {
    console.error('❌ Failed to fetch provider profile:', response.error);
    return null;
  }
}

/**
 * RENDER PROVIDER PROFILE
 * @description Build profile HTML from database data
 */
function renderProviderProfile(provider) {
  if (!provider) {
    console.error('No provider data to render');
    return;
  }

  // Update profile header
  const profileName = document.getElementById('providerName');
  const profileRating = document.getElementById('providerRating');
  const profileCategory = document.getElementById('providerCategory');
  const profileBio = document.getElementById('providerBio');

  if (profileName) profileName.textContent = provider.name;
  if (profileRating) profileRating.innerHTML = `
    ${'⭐'.repeat(Math.floor(provider.average_rating))}
    <span class="ms-2">${provider.average_rating.toFixed(1)} (${provider.total_reviews} reviews)</span>
  `;
  if (profileCategory) profileCategory.textContent = provider.service_category;
  if (profileBio) profileBio.textContent = provider.bio;

  // Render skills
  renderProviderSkills(provider.skills);

  // Render reviews
  fetchAndRenderProviderReviews(provider.provider_id);

  // Render portfolio
  renderProviderPortfolio(provider.portfolio);
}

/**
 * RENDER PROVIDER SKILLS
 * @description Display skills from database
 */
function renderProviderSkills(skills) {
  const skillsContainer = document.getElementById('providerSkills');
  
  if (!skillsContainer || !skills) return;

  skillsContainer.innerHTML = '';

  skills.forEach(skill => {
    const skillBadge = document.createElement('span');
    skillBadge.className = 'badge bg-info me-2 mb-2';
    skillBadge.innerHTML = `
      ${skill.name}
      ${skill.verified ? '<i class="fas fa-check-circle ms-1"></i>' : ''}
    `;
    skillsContainer.appendChild(skillBadge);
  });
}

/**
 * FETCH & RENDER PROVIDER REVIEWS
 * @description Get reviews from database and display them
 */
async function fetchAndRenderProviderReviews(providerId) {
  console.log(`📡 Fetching reviews for provider #${providerId}...`);

  const response = await apiCall(`/providers/${providerId}/reviews`, 'GET');

  if (response.success) {
    console.log('✅ Reviews loaded:', response.data);
    renderReviews(response.data);
  } else {
    console.error('❌ Failed to fetch reviews:', response.error);
  }
}

/**
 * RENDER REVIEWS
 * @description Display reviews as HTML cards
 */
function renderReviews(reviews) {
  const reviewsContainer = document.getElementById('reviewsContainer');
  
  if (!reviewsContainer) return;

  reviewsContainer.innerHTML = '';

  if (!reviews || reviews.length === 0) {
    reviewsContainer.innerHTML = '<p class="text-muted">No reviews yet.</p>';
    return;
  }

  reviews.forEach(review => {
    const reviewCard = document.createElement('div');
    reviewCard.className = 'card mb-3';
    reviewCard.innerHTML = `
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="card-title mb-1">${review.client_name}</h6>
            <div class="mb-2">
              ${'⭐'.repeat(review.rating)}<span class="text-muted ms-2">${review.rating}/5</span>
            </div>
          </div>
          <small class="text-muted">${formatDate(review.created_date)}</small>
        </div>
        <p class="card-text">${review.review_text}</p>
        ${review.provider_response ? `
          <div class="alert alert-light p-2">
            <small><strong>Provider Response:</strong> ${review.provider_response}</small>
          </div>
        ` : ''}
      </div>
    `;
    reviewsContainer.appendChild(reviewCard);
  });
}

/**
 * RENDER PROVIDER PORTFOLIO
 * @description Display portfolio projects
 */
function renderProviderPortfolio(portfolio) {
  const portfolioContainer = document.getElementById('portfolioContainer');
  
  if (!portfolioContainer || !portfolio) return;

  portfolioContainer.innerHTML = '';

  portfolio.forEach(project => {
    const projectCard = document.createElement('div');
    projectCard.className = 'col-md-6 col-lg-4 mb-4';
    projectCard.innerHTML = `
      <div class="card h-100">
        <img src="${project.image_url}" class="card-img-top" alt="${project.title}" 
             style="height: 200px; object-fit: cover;">
        <div class="card-body">
          <h6 class="card-title">${project.title}</h6>
          <p class="card-text small text-muted">${project.description}</p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-warning">${'⭐'.repeat(project.rating)}</span>
            <small class="text-muted">${project.completion_date}</small>
          </div>
        </div>
      </div>
    `;
    portfolioContainer.appendChild(projectCard);
  });
}

// ============================================================================
// EXAMPLE 4: SUBMITTING PROPOSAL
// ============================================================================
// When provider submits a proposal, send data to API

/**
 * SUBMIT PROPOSAL
 * @description Send proposal data to backend API
 */
async function submitProposal(jobId, proposalData) {
  console.log('📤 Submitting proposal...', proposalData);

  const data = {
    job_id: jobId,
    provider_id: localStorage.getItem('userId'),
    proposal_text: proposalData.coverLetter,
    bid_amount: parseFloat(proposalData.bidAmount),
    timeline_days: parseInt(proposalData.timelineDays),
    start_date: proposalData.startDate,
    submission_date: new Date().toISOString(),
    status: 'Pending'
  };

  const response = await apiCall('/proposals', 'POST', data);

  if (response.success) {
    console.log('✅ Proposal submitted!', response.data);
    return {
      success: true,
      proposalId: response.data.proposal_id,
      message: 'Your proposal has been sent to the client!'
    };
  } else {
    console.error('❌ Failed to submit proposal:', response.error);
    return {
      success: false,
      message: response.error
    };
  }
}

// ============================================================================
// EXAMPLE 5: AUTHENTICATION
// ============================================================================
// Sending login credentials to API

/**
 * LOGIN USER
 * @description Send email & password to API for authentication
 */
async function loginUser(email, password) {
  console.log('📤 Sending login credentials...');

  const response = await apiCall('/auth/login', 'POST', {
    email,
    password,
    timestamp: new Date().toISOString()
  });

  if (response.success) {
    console.log('✅ Login successful!');
    // Store token and user info
    const { token, user } = response.data;
    localStorage.setItem('authToken', token);
    if (user) {
      localStorage.setItem('userId', user.id);
      localStorage.setItem('userRole', user.role);
      localStorage.setItem('userName', user.name || user.email || 'User');
    }
    
    return { success: true, user };
  } else {
    console.error('❌ Login failed:', response.error);
    return { success: false, message: response.error };
  }
}

/**
 * REGISTER NEW USER
 * @description Send registration data to API
 */
async function registerUser(userData) {
  console.log('📤 Sending registration data...');

  const response = await apiCall('/auth/register', 'POST', {
    name: `${userData.firstName || ''} ${userData.lastName || ''}`.trim() || userData.email,
    email: userData.email,
    password: userData.password,
    role: userData.role || 'client'
  });

  if (response.success) {
    console.log('✅ Registration successful!');
    const { user, token } = response.data;
    if (token) localStorage.setItem('authToken', token);
    if (user) {
      localStorage.setItem('userId', user.id);
      localStorage.setItem('userRole', user.role);
      localStorage.setItem('userName', user.name || user.email || 'User');
    }
    return { success: true, userId: user?.id };
  } else {
    console.error('❌ Registration failed:', response.error);
    return { success: false, message: response.error };
  }
}

// ============================================================================
// INITIALIZATION
// ============================================================================

// Initialize job posting form on relevant pages
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeJobPostingForm);
} else {
  initializeJobPostingForm();
}

// Export functions for use in other scripts
window.FreelanceMarketplace = {
  getUserLocation,
  formatCurrency,
  formatDate,
  debounce,
  throttle,
  validateForm,
  filterItems,
  setupSearchFilter,
  setupPagination,
  // NEW API Functions
  apiCall,
  populateServicesCategories,
  fetchJobs,
  renderJobs,
  createJobCard,
  loadAndDisplayJobs,
  submitJobPosting,
  fetchProviderProfile,
  renderProviderProfile,
  fetchAndRenderProviderReviews,
  renderReviews,
  renderProviderPortfolio,
  submitProposal,
  loginUser,
  registerUser,
  updateJobStatus,
  showToast,
  applyToJob,
  getCurrentUser,
  fetchProviderProposals,
  fetchProviderActiveProjects,
  fetchOpenJobs
};
