<?php

namespace App\Http\Controllers;

use App\Contracts\OrderServiceInterface;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService) {}
    public function index()
    {
        $user = Auth::user();

        // --------------
       
    //  $numbers = [10, 20, 10, 30, 20, 40, 50, 30];





        // dd('------------------------------');
        
        /*
        1.
        Apple
        Orange
        2.
        ["Apple", "Banana","Orange"]
        3. 5
        4. 
        Ravi
        Developer
        5.
        ["name" => "Ravi",
        "age" => 30,
        "role" => "Laravel Developer"
        ]
        6.
        bool(true)
        bool(false)
        7. 2
        8. in_array return boolean value. array_search return index of the value
        9. 
        bool(true)
        bool(false)
        bool(true)
        10. [10, 20, 30, 40, 50]
        11. 
        30
        [10, 20]
        12.
        10
        [20, 30]
        13. [5, 10, 20, 30]
        14. [1, 2, 3, 4, 5, 6]
        15.
        [
            "name" => "Ravi",
            "age" => 30,
            "role" => "Developer"
        ];
        16. [20, 30, 40]
        17. [10, 40, 50]. array_slice return sliced value. array_splice return balance value.
        18. [10, 20, 30, 40]
        19. 
        sort() - noraml sort for indexed array
        rsort() - reverse sort for indexed array
        asort() - normal sort for associative array (based on value)
        arsort() - reverse sort for associative array (based on value)
        ksort() - normal sort for associative array (based on key)
        krsort() - reverse sort for associative array (based on key)
        20.
         [
            "age" => 29,
            "salary" => 50000,
            "name" => "Ravi"
        ];
        

        */

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'restaurant_owner' => redirect()->route('restaurant.dashboard'),
            'delivery_agent' => redirect()->route('agent.dashboard'),
            default => view('customer.dashboard')
        };
    }
}
