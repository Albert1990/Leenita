<?php

namespace App\Http\Api\V1\Controllers;

use App\Brand;
use App\Http\Helpers;
use App\Http\Enums\MediaTypes;
use App\Offer;
use App\OfferMedia;
use App\Product;
use App\Transformers\OfferTransformer;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class OffersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location=null)
    {
        $users=User::all();
        $brands=Brand::all();
        $products=Product::all();
        $offers=[];

//        for($i=0;$i<count($products);$i++)
//        {
//            $offer1=new Offer();
//            $offer1->id=$i+1;
//            $offer1->user=$users[Helpers::getRandomInteger(0,1)];
//            $offer1->type=1;
//            $offer1->discount=($i+2)*5;
//            $offer1->description="it a great offer for ".$users[Helpers::getRandomInteger(0,1)]->name;
//            $offer1->product=$products[$i];
//            array_push($offers,$offer1);
//        }

        $offer1=new Offer();
        $offer1->id=1;
        $offer1->brand=$brands[0];
        $offer1->type=1;
        $offer1->discount=20;
        $offer1->description="With purchase of a Samsung Galaxy S7 or Galaxy S7 edge on AT&T Next®. You must be either an existing AT&T customer who adds a line or upgrades an existing line and signs up for DIRECTV or U-verse, an existing DIRECTV or U-verse customer who signs up for a new AT&T";
        $offer1->product=$products[0];
        $offer1->media=[
            new OfferMedia(1,1,MediaTypes::IMAGE,'images/offers/Samsung_Curved__main.jpg',0),
            new OfferMedia(2,1,MediaTypes::IMAGE,'images/offers/samsung-uhdtv-inline.jpg',0),
        ];
        array_push($offers,$offer1);

        $offer1=new Offer();
        $offer1->id=2;
        $offer1->brand=$brands[0];
        $offer1->type=1;
        $offer1->discount=38;
        $offer1->description="Only at Verizon. Get a $200 rebate when you buy a Galaxy S6 edge or a Galaxy S6 edge+ and submit a valid rebate claim.";
        $offer1->product=$products[1];
        $offer1->media=[
            new OfferMedia(3,2,MediaTypes::IMAGE,'images/offers/galaxy-s7-5-reasons-3.jpg',0),
            new OfferMedia(4,2,MediaTypes::IMAGE,'images/offers/samsung4.png',0),
        ];
        array_push($offers,$offer1);

        $offer1=new Offer();
        $offer1->id=3;
        $offer1->brand=$brands[0];
        $offer1->type=1;
        $offer1->discount=19;
        $offer1->description="Purchase a Samsung Galaxy S7 at StraightTalk.com or walmart.com and receive a Gear VR powered by Oculus– an estimated $100* value. Limited time only, while supplies last.";
        $offer1->product=$products[2];
        $offer1->media=[
            new OfferMedia(5,3,MediaTypes::IMAGE,'images/offers/samsung6.png',0),
            new OfferMedia(6,3,MediaTypes::IMAGE,'images/offers/samsung7.png',0),
        ];
        array_push($offers,$offer1);

        $offer1=new Offer();
        $offer1->id=4;
        $offer1->brand=$brands[1];
        $offer1->type=1;
        $offer1->discount=50;
        $offer1->description="new offers on perfume for ".$users[0]->name;
        $offer1->product=$products[6];
        $offer1->media=[
            new OfferMedia(7,4,MediaTypes::IMAGE,'images/offers/adidas-Impossible-is-Nothing.png',0),
            new OfferMedia(8,4,MediaTypes::IMAGE,'images/offers/sport5.jpg',0),
        ];
        array_push($offers,$offer1);

        $offer1=new Offer();
        $offer1->id=5;
        $offer1->brand=$brands[1];
        $offer1->type=1;
        $offer1->discount=33;
        $offer1->description="At adidas, we create products that help you perform better, play better and feel better. Whether you’re using our shoes on the street or the court, or our apparel in the studio or on the field, we want you to be satisfied with your purchase.";
        $offer1->product=$products[7];
        $offer1->media=[
            new OfferMedia(9,5,MediaTypes::IMAGE,'images/offers/sport4.jpg',0),
            new OfferMedia(10,5,MediaTypes::IMAGE,'images/offers/sport2.jpg',0),
        ];
        array_push($offers,$offer1);

        $offer1=new Offer();
        $offer1->id=6;
        $offer1->brand=$brands[2];
        $offer1->type=1;
        $offer1->discount=30;
        $offer1->description="Moulinex is a manufacturer of small household appliances including food processors, kitchen machines, mixers, choppers, meat mincers, kettles, toasters, contact grills, coffee makers, juice extractors, blenders, bread makers, fryers, etc. ";
        $offer1->product=$products[8];
        $offer1->media=[
            new OfferMedia(11,6,MediaTypes::IMAGE,'images/offers/mou1.jpg',0),
            new OfferMedia(12,6,MediaTypes::IMAGE,'images/offers/mou2.jpg',0),
        ];
        array_push($offers,$offer1);

        $offer1=new Offer();
        $offer1->id=7;
        $offer1->brand=$brands[2];
        $offer1->type=1;
        $offer1->discount=80;
        $offer1->description="For more than 80 years, Moulinex has been producing carefully designed and easy-to-use products that make cooking a pleasure rather than a chore. It has helped liberate women and now aims to liberate people’s desire to cook.";
        $offer1->product=$products[9];
        $offer1->media=[
            new OfferMedia(13,7,MediaTypes::IMAGE,'images/offers/mou3.jpg',0),
            new OfferMedia(14,7,MediaTypes::IMAGE,'images/offers/mou4.jpg',0),
        ];
        array_push($offers,$offer1);


        $transformedOffers=Helpers::transformArray($offers,new OfferTransformer());
        return $this->respond($transformedOffers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
}
