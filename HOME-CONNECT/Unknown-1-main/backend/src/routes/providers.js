const { Router } = require('express');
const asyncHandler = require('../utils/asyncHandler');
const { authRequired, requireRole } = require('../middleware/auth');
const { getMyProposals, getMyActiveProjects } = require('../controllers/providerController');

const router = Router();

router.get('/me/proposals', authRequired, requireRole('provider'), asyncHandler(getMyProposals));
router.get('/me/active-projects', authRequired, requireRole('provider'), asyncHandler(getMyActiveProjects));

module.exports = router;
