<?php
namespace App\Services;

// use App\Models\Salon;

use App\Models\Category;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\User;
use App\Services\DistanceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Termwind\Components\Dd;

class UserService
{
    public $distanceService;
    public $servicessss;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function getNearbyProfessionals($userLatitude, $userLongitude, $radius = 10)
    {
        
        $professionals = User::with('salon.salon_services.category')->where('role_type', 'PROFESSIONAL')
            ->select('id', 'name', 'last_name', 'address', 'latitude', 'longitude')
            ->paginate(10);

        $nearbyProfessionals = [];
        


        foreach ($professionals as $professional) {

            
            $distance = $this->distanceService->getDistance(
                $userLatitude,
                $userLongitude,
                $professional->latitude,
                $professional->longitude
            );
            // dump($distance);
            if ($distance <= $radius) {
                $nearbyProfessionals[] = $professional;
                
            }
        }
        // dd($nearbyProfessionals);
        return $nearbyProfessionals;
    }

    //getNearbyProfessionalsByCategory make this function to get nearby professionals by category
    public function getNearbyProfessionalsByCategory($userLatitude, $userLongitude, $radius = 20, $category)
    {
        
        $nearByProf = $this->getNearbyProfessionals($userLatitude, $userLongitude, $radius);

        $nearByProfsss = collect($nearByProf)->filter(function ($professional) use ($category) {
            return SalonService::where('category_id', $category)->get();
        });





        // $perPage = 10;
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // $currentPageItems = $nearByProf->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // $paginatedProfessionals = new LengthAwarePaginator(
        //     $currentPageItems,
        //     $filteredProfessionals->count(),
        //     $perPage,
        //     $currentPage,
        //     ['path' => LengthAwarePaginator::resolveCurrentPath()]
        // );

        // return $paginatedProfessionals;
        return $nearByProfsss;
        
    }
}