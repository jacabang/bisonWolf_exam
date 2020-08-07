<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

use App\SpecialRequests as SpecialRequests;
use App\Accommodations as Accommodations;
use App\ExclusionOfTime as ExclusionOfTime;
use App\Requests as Requests;

class ApiController extends Controller
{
    public function append(Request $request){

        $ids = array();

        $info = $request->info;

        foreach($request->flight as $result):

            if($result['type'] == 'roundtrip'):
                    $i = 0;
                endif;

            foreach($result['tracks'] as $result1):

                //time exclutions

                $exclusion_of_time = $result1['exclusion_of_time']['enable'] === true ? 1 : 0;

                $from_time = $result1['exclusion_of_time']['enable'] === true ? $result1['exclusion_of_time']['timeFrom'].':00' : '';

                $to_time = $result1['exclusion_of_time']['enable'] === true ? $result1['exclusion_of_time']['timeFrom'].':00' : '';

                //accomodation

                $accomodation = $info['accommodation'] != "" ? 1 : 0;
                $accomodation_data = $info['accommodation'] != "" ? $info['accommodation'] : NULL;

                //special request

                $special_request = $info['specialrequest'] != "" ? 1 : 0;
                $special_request_data = $info['specialrequest'] != "" ? 1 : 0;

                $departuredate = $result1['departuredate'];

                //build data for requests

                //initiate round trip

                if($result['type'] == 'roundtrip'):
                    if($i == 0):

                        $returndate = $result1['returndate'];

                        $i++;
                    else:
                        $departuredate = $returndate;
                    endif;
                endif;


                $data = array(
                    'origin' => $result1['origin']['airportCode'].' - '.$result1['origin']['cityName'],
                    'destination' => $result1['destination']['airportCode'].' - '.$result1['origin']['cityName'],
                    'aircraft' => $result1['aircraft']['aircraft'],
                    'passenger_count' => $result1['passengercount'],
                    'flight_date' => $departuredate,
                    'created_at' => date('Y-m-d H:i:s'),
                    'has_exclusions_of_time' => $exclusion_of_time,
                    'has_accommodations' => $accomodation,
                    'has_special_requests' => $special_request,

                );

                $requests_id = DB::connection('mysql')->table('requests')
                        ->insertGetId($data);

                    array_push($ids, $requests_id);

                    if($exclusion_of_time != 0):

                         DB::connection('mysql')->table('exclusions_of_time')
                            ->insert([
                                'from_time' => $from_time,
                                'to_time' => $to_time,
                                'requests_id' => $requests_id,
                                'created_at' => date('Y-m-d H:i:s')
                                ]);
                    endif;

                    if($accomodation != 0):

                         DB::connection('mysql')->table('accommodations')
                            ->insert([
                                'accommodations' => $accomodation_data,
                                'requests_id' => $requests_id,
                                'created_at' => date('Y-m-d H:i:s')
                                ]);
                    endif;

                    if($special_request != 0):

                         DB::connection('mysql')->table('special_requests')
                            ->insert([
                                'special_requests' => $accomodation_data,
                                'requests_id' => $requests_id,
                                'created_at' => date('Y-m-d H:i:s')
                                ]);
                    endif;

            endforeach;

        endforeach;

        return $this->fetchFlightViaIds($ids);

    }
    public function fetchFlight(){
        return Requests::with('exclution_time')
            ->with('accomodation')
            ->with('special_request')
            ->get();
    }

    public function fetchFlightViaIds($ids){
        return Requests::whereIn('id', $ids)
            ->with('exclution_time')
            ->with('accomodation')
            ->with('special_request')
            ->get();
    }

}
