<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShiftRuleCollection;
use Illuminate\Http\Request;
use App\Models\ShiftRule;
use App\Http\Resources\ShiftRuleResource;
use Exception;


class ShiftRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $shiftrules = ShiftRule::all(); 

            return response()->json([
                'status'    => true,
                'message'   => 'Shift Time Rule list.',
                'data'      => new ShiftRuleCollection($shiftrules) ?? []
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $name = trim($request->name) ?? "";
            $hours = (int)$request->input('hours');
            $minutes = (int)$request->input('minutes');
            
            $totalMinutes = ($hours * 60) + $minutes;

            $shiftRule = ShiftRule::where('name', $name)->first();

            if (!$shiftRule) {
                return response()->json([
                    'status' => false,
                    'message' => 'Shift rule not found for the provided name.'
                ], 404);
            }

            $shiftRule->update([
                'time_in_minutes' => $totalMinutes
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Shift updated successfully.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {

        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;

        try {

            $shift_rule = ShiftRule::select('id', 'name', 'time_in_minutes', 'created_at', 'updated_at')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Shift Rule list.',
                'data'      =>  $shift_rule ?? [],
            ], 200);
            
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }
}
