<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('terms')->insertOrIgnore([
            "type" => "terms_and_conditions",
            "value" => json_encode([
                "en" => "A Terms and Conditions agreement acts as legal contracts between you (the company) who has the website or mobile app, and the user who accesses your website/app.
Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
You can think of your Terms and Conditions agreement as the legal agreement where you maintain your rights to exclude users from your app in the event that they abuse your app, where you maintain your legal rights against potential app abusers, and so on.",
                "sv" => "Ett villkoravtal fungerar som juridiska avtal mellan dig (företaget) som har webbplatsen eller mobilappen, och användaren som kommer åt din webbplats/app.
Att ha ett villkor är helt frivilligt. Inga lagar kräver att du har en. Inte ens den superstrikta och vidsträckta allmänna dataskyddsförordningen (GDPR).
Ditt villkoravtal blir unikt ditt. Även om vissa klausuler är vanliga och vanligt förekommande i i stort sett alla villkor, är det upp till dig att ange de regler och riktlinjer som användaren måste godkänna.
Du kan tänka på ditt användarvillkoravtal som det juridiska avtalet där du behåller dina rättigheter att utesluta användare från din app om de missbrukar din app, där du behåller dina juridiska rättigheter mot potentiella appmissbrukare och så vidare.",
            ])
        ]);

        DB::table('terms')->insertOrIgnore([
            "type" => "about_us",
            "value" => json_encode([
                "en" => "Our mission is to be the most sustainable restaurant in the world by sourcing our ingredients locally, supplementing produce with herbs grown on our rooftop garden, and giving back to the community through urban farming education.",
                "sv" => "Vårt uppdrag är att vara den mest hållbara restaurangen i världen genom att köpa våra ingredienser lokalt, komplettera produkter med örter som odlas på vår takterrass och ge tillbaka till samhället genom stadsodling.",
            ])
        ]);
    }
}
