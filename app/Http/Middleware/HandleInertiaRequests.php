<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        
        $menus = $this->getUserMenu($request->user());
        

        return [
            ...parent::share($request),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' =>  fn() => $request->session()->get('error'),
                'warning' => fn()=> $request->session()->get('warning'),
                'info'=> fn()=> $request->session()->get('info'),
                'datas'=> fn()=> $request->session()->get("datas"),
            ],

            'main_menu' => $menus,
        ];
    }


    function getUserMenu(?User $user) {
        if (!$user) {
            return [];
        }

        $menu = [
            [
                'label' => 'Tableau de bord',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => '',
            ],
            [
                'label' => 'Clients',
                'route' => 'user.customers.index', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => '',
            ],
            [
                'label' => 'Transactions',
                'route' => 'user.transactions.index', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => '',
            ],
            [
                'label' => 'Remboursements',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => '',
            ],
            [
                'label' => 'Retenues',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => '',
            ],
            [
                'label' => 'Balaances',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => '',
            ],

            [
                'label' => 'Api',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => 'Développeur',
            ],
            [
                'label' => 'Evènements',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => 'Développeur',
            ],
            [
                'label' => 'Logs',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => 'Développeur',
            ],
            [
                'label' => 'Webhooks',
                'route' => 'dashboard', // Default dashboard, specific dashboards will be handled by permissions
                'params' => [],
                'permissions' => ['view-any-appointment', 'view-any-order', 'view-any-product', 'view-any-queue', 'view-any-user'], // Example: if user has any of these, they can see dashboard
                'category' => 'Développeur',
            ],
            
            // [
            //     'label' => 'Conversations',
            //     'route' => 'conversations.index',
            //     'params' => [],
            //     'permissions' => [], // No specific permission for now
            //     'category' => 'Messagerie',
            // ],

        ];

        $filteredMenu = array_filter($menu, function ($item) use ($user) {
            // if (empty($item['permissions'])) {
            //     return true;
            // }
            // foreach ($item['permissions'] as $permission) {
            //     if ($user->hasPermissionTo($permission)) {
            //         return true;
            //     }
            // }
            // return false;


            return true;
        });

        $categorizedMenu = [];
        foreach ($filteredMenu as $item) {
            $category = $item['category'] ?? 'Uncategorized';
            if (!isset($categorizedMenu[$category])) {
                $categorizedMenu[$category] = [];
            }
            $categorizedMenu[$category][] = $item;
        }

        return $categorizedMenu;
    }
}
