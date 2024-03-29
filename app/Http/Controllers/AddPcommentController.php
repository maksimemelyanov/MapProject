<?php

namespace App\Http\Controllers;
use App\Models\Pcomment;
use Illuminate\Http\Request;
use App\Models\Point;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class AddPcommentController extends Controller
{
    public function AddPcomment(Request $request)
    {
        $validateFields = $request->validate([
            'rating'=> 'required',
            'text' => 'required',
        ]);
        $validateFields['creatorid'] = $_SESSION['User']->id;
        $validateFields['pointid'] = $_SESSION['CurrentPoint']->id;
        $pcomment = Pcomment::create($validateFields);
        $rate = $this->RatingCalculate();

        return redirect()->intended('/point=' . $_SESSION['CurrentPoint']->id);



    }
    public function RatingCalculate()
    {
        $getrating = DB::table('pcomments')
            ->select('rating')
            ->where('pointid', $_SESSION['CurrentPoint']->id)
            ->get();
        $currentrating = 0;
        foreach ($getrating as $rating) {
            $currentrating += $rating->rating;
        }
        $currentrating = $currentrating / Count($getrating);
        $rating = Point::where('id', $_SESSION['CurrentPoint']->id)->update(['rating' =>$currentrating]);


    }
}
