<?php declare( strict_types=1 );

namespace App;

use App\Core\Dashboard;
use App\Core\Debug;
use App\Core\Security;

// prevent direct access
if (! defined( 'ABSPATH' ) ) {
    return null;
}

class App
{
    public Debug $debug;

    public function __construct()
    {
        include_once 'Core/Debug.php';
        $this->debug = new Debug();

        include_once 'Core/Security.php';
        new Security();

        include_once 'Core/Dashboard.php';
        (new Dashboard())->init();
    }
}
