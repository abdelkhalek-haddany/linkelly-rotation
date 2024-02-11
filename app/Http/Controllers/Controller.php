<?php

namespace App\Http\Controllers;

use App\Models\Distination;
use App\Models\Link;
use App\Models\Stats;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function index()
    {
        $userInfo = session('user_information');
        return $userInfo;
    }
    public function rotation(string $slug)
    {
        // try {
        // $slug;
        $link = Link::where('slug', '=', $slug)->first();

        // Check if the link exists
        if (!$link) {
            return redirect()->back()->with(["error" => "no link found with id " . $slug]);
        }

        if ($link->status == '0') {
            // Get all destinations for the link
            $destinations = Distination::where('link_id', $link->id)->get();

            // Check if there are destinations for the link
            if ($destinations->isEmpty()) {
                return "no destination found for link with id " . $link->id;
            }

            // Calculate total percentage
            $totalPercentage = $destinations->sum('percentage');

            // Generate a random number between 1 and the total percentage
            $randomNumber = rand(1, $totalPercentage);


            $userInfo = session('user_information');

            // dd($userInfo);
            // Find the destination based on the random number
            $currentPercentage = 0;


            foreach ($destinations as $destination) {
                $currentPercentage += $destination->percentage;
                if ($randomNumber <= $currentPercentage) {
                    $stats = new Stats();
                    $stats->country = $userInfo['country'];
                    $stats->city = $userInfo['city'];
                    $stats->latitude = $userInfo['latitude'];
                    $stats->longitude = $userInfo['longitude'];
                    $stats->browser = $userInfo['browser'];
                    $stats->browser_version = $userInfo['browser_version'];
                    $stats->device = $userInfo['device'];
                    $stats->platform = $userInfo['platform'];
                    $stats->is_mobile = $userInfo['is_mobile'];
                    $stats->is_tablet = $userInfo['is_tablet'];
                    $stats->is_robot = $userInfo['is_robot'];
                    $stats->languages = $userInfo['languages'][0];
                    $stats->is_desktop = $userInfo['is_desktop'];
                    $stats->distination_id = $destination->id;
                    $stats->save();
                    // Redirect to the selected destination
                    return redirect($destination->distination);
                }
            }
        }
        // If no destination is found, redirect to a default URL or handle accordingly
        // return redirect()->back()->with(['error' => 'No destination found for the link']);
        echo "Ooops! there is no destination" . $slug;
        // } catch (Exception $e) {
        //     echo "Ooops! an error";
        //     // return redirect()->back()->with(['error' => 'Oops! An error occurred']);
        // }
    }
}
