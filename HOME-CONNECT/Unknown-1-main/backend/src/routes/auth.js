const { Router } = require('express');
const asyncHandler = require('../utils/asyncHandler');
const { authRequired } = require('../middleware/auth');
const { register, login, verifyToken } = require('../controllers/authController');

const router = Router();

router.post('/register', asyncHandler(register));
router.post('/login', asyncHandler(login));
router.get('/verify-token', authRequired, asyncHandler(verifyToken));

module.exports = router;
