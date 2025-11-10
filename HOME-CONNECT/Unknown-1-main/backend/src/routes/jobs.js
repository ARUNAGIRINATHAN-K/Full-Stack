const { Router } = require('express');
const asyncHandler = require('../utils/asyncHandler');
const { authRequired, requireRole } = require('../middleware/auth');
const { listJobs, createJob, getJobById, updateJobStatus, applyToJob } = require('../controllers/jobController');

const router = Router();

// Public list with filters
router.get('/', asyncHandler(listJobs));

// Create a new job (client only)
router.post('/', authRequired, requireRole('client'), asyncHandler(createJob));

// Update status (client only, owns job)
router.put('/:id/status', authRequired, requireRole('client'), asyncHandler(updateJobStatus));

// Apply to a job (provider only)
router.post('/:id/apply', authRequired, requireRole('provider'), asyncHandler(applyToJob));

// Get single job (public)
router.get('/:id', asyncHandler(getJobById));

module.exports = router;
