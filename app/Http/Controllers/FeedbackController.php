<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\FeedbackReceived;
use App\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
    	$words = $request->get('word');

    	//Tidak Boleh Mengandung Tanda Koma (,)
    	if (strpos($words, ",") !== false) {
    		return response()->json([
    			"message" => "Kata Tidak Boleh Mengandung Tanda Koma (,)",
    			], 400);
    	}

    	$words = explode(" ", $words);

    	//Tidak Boleh Lebih Dari 3 Kata
    	if (count($words) > 3) {
    		return response()->json([
    			"message" => "Kata Tidak Boleh Lebih Dari 3 Kata",
    		], 400);
    	}

    	foreach ($words as $key => $word)
    	{
    		$this->createOrIncrement($word);
    	}

        //Broadcast With New Data
        $data = json_decode($this->getData());
        broadcast(new FeedbackReceived($data));

    	return response()->json("OK");
    }

    public function dashboard()
    {
    	return view('dashboard');
    }

    public function dashboardData()
    {
    	return response()->json($this->getData());
    }

    protected function getData()
    {
    	$top_ten = Feedback::orderBy('count', 'DESC')->get()->take(10);
    	return $top_ten;
    }

    public function input()
    {
    	return view('input');
    }

    protected function createOrIncrement(String $word)
    {
    	//Dijadikan LowerCase
    	$word = strtolower($word);
    	$feedback = Feedback::where('word', $word)->first();

    	if($feedback) {
    		$feedback->increment('count');
    	} else {
    		Feedback::create([
    			"word" => $word,
    			"count" => 1,
    		]);
    	}

    }
}
