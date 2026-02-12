<?php

/**
 * Base controller with common functionality for all Caronte controllers.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Http\Controllers
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Ometra\Caronte\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;

/**
 * Provides shared view rendering logic for Caronte controllers.
 *
 * Handles rendering either Inertia or Blade views based on configuration.
 */
class BaseController extends Controller
{
    /**
     * Renders a view using either Inertia or Blade based on config.
     *
     * @param  string                 $viewPath  View path or name (for Blade).
     * @param  mixed                  $data      Data to pass to view.
     * @return View|InertiaResponse              Rendered view response.
     */
    protected function toView(string $viewPath, mixed $data): View | InertiaResponse
    {
        if (config('caronte.USE_INERTIA')) {
            $inertiaPath = str_replace('.', '/', $viewPath);

            return inertia($inertiaPath, $data);
        } else {
            return view('caronte::' . $viewPath)
                ->with($data);
        }
    }
}
