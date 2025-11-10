const { Router } = require('express');
const authRoutes = require('./auth');
const jobRoutes = require('./jobs');
const providerRoutes = require('./providers');

const router = Router();

router.use('/auth', authRoutes);
router.use('/jobs', jobRoutes);
router.use('/providers', providerRoutes);

module.exports = router;
