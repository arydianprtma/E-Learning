<?php

require_once 'Config/config.php';
require_once 'Core/App.php';
require_once 'Core/Controller.php';
require_once 'Core/Database.php';

// Set Timezone
date_default_timezone_set('Asia/Jakarta');

// Simple Autoloader for Models/Controllers if needed, but manual require in Controller base handles models.
// Core classes are loaded here.
