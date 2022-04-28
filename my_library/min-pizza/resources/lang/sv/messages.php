<?php

return [
    "notifications" => [
        "order_status_changes" => "ordernummer :number status ändrad till :status ",
        "more" => "klicka här för att visa mer.",
        "new_order"=>"ny beställning är nu tillgänglig",
        "new_order_from_admin" => "ny beställning från admin är nu tillgänglig",
        "order_scheduling" => "beställa :number schemaläggning till  :time",
        "accept_order_by_driver" =>"ordernummer :number accepterad,
        chauffören kommer för att ta beställningen från dig",
        "accept_order_by_restaurant" =>"ordernummer :number accepterad ,
        beställningen förbereds",
        "not_accepted_yet" =>"ordernummer :number inte accepterat ännu",
        "not_accepted_yet_by_driver" =>"ordernummer :number accepteras ännu inte av någon förare",
        "order_ready_to_delivered" => "ordernummer :number är order redo att levereras",
        "order_ready_to_takeaway" => "ordernummer :number är order redo att hämtmat",
        "sent" => "meddelande har skickats",
        "late" => "ordernummer :number är sent för kunden",
        "restaurant_status_changes" => "restaurangstatus ändrad till :status ",
        "order_ready_to_takeaway_from_driver" => "ordernummer :number redo att ta av föraren ",
    ],
    "Address" => [
        "create" => "Adress skapad",
        "update" => "adressen har uppdaterats",
        "delete" => "adressen har tagits bort",
    ],
    "restaurant" => [
        "create" => "restaurangen skapades framgångsrikt",
        "update" => "restaurang uppdaterad framgångsrikt",
        "delete" => "restaurangen har tagits bort",
        "have_not_restaurant" => "Du har ingen restaurang",
        "restaurant_is_close" => "restaurangen är nära",
        "restaurant_is_close_for_delivery" => "Restaurangen är stängd för leverans",
        "restaurant_is_close_for_takeaway" => "Restaurangen är stängd för takeaway",
        "restaurant_not_support_schudeling" => "restaurang stöder inte schemaläggning",
        "date_must_be_after_today" => "restaurang stöder inte schemaläggning",
        "attached" => "metod betalning kopplad till restaurangen framgångsrikt",
        "detached" => "betalningsmetoden har kopplats bort från restaurangen",
    ],
    "restaurant_category" => [
        "create" => "kategorin skapades framgångsrikt",
        "update" => "kategorin har uppdaterats",
        "delete" => "kategorin har tagits bort",
    ],
    "worktime" =>[
        "create" => "arbetstid skapad",
        "update" => "arbetstiden har uppdaterats",
        "delete" => "arbetstiden har tagits bort",
    ],
    "restaurant_settings" =>[
        "create" => "inställningar skapade",
        "update" => "inställningarna har uppdaterats",
        "delete" => "inställningarna har tagits bort",
    ],
    "restaurant_status" =>[
        "update" => "restaurangstatus uppdaterad",
    ],
    "restaurant_rating" =>[
        "create" => "restaurangbetyget har gjorts framgångsrikt",
        "update" => "restaurangbetyget har gjorts framgångsrikt",
        "delete" => "restaurangbetyg har tagits bort",
        "order_first" => "vänligen beställ först innan betygsätt vår restaurang",
    ],
    "Tax" => [
        "create" => "Skatt skapad",
        "update" => "skatten har uppdaterats",
        "delete" => "skatten borttagen",
    ],

    "menu_category" => [
        "create" => "menykategorin skapades",
        "update" => "menykategorin har uppdaterats",
        "delete" => "menykategorin har tagits bort",
    ],
    "menu_item" => [
        "create" => "menyalternativet skapades",
        "update" => "menyalternativet har uppdaterats",
        "delete" => "menyalternativet har tagits bort",
        "attached" => "Alternativet har bifogats Artikel",
        "detached" => "Länken mellan Artikel och alternativet har rensats",
    ],
    "auth" => [
        "verification" => "E -postverifieringslänk skickas till din e -postadress",
        "verification_mail" => "verifieringsmail",
        "verified" => "Ditt konto har verifierats framgångsrikt",
        "email_exist" => "förlåt, detta e-postmeddelande finns redan användare en annan",
        "disabled" => "ditt konto är inaktiverat, kontakta vår support",
        "verify" => "verifiera din e -post först",
        "invalid_login" => "Ogiltiga inloggnings uppgifter",
        "logout" => "Loggade ut framgångsrikt",
        "reset_send" => "Ett e -postmeddelande skickat till :mail  med en återställningskod",
        "reset_title" => "hej :name, Vi fick en begäran om att återställa lösenordet för ditt MINI PIZZA-konto",
        "reset_body" => "din kod är ",
        "password_reset" => "Lösenordsåterställning",
        "invalid_mail" => "Ogiltig e-postadress",
        "invalid_code" => "ogiltig kod",
        "password_change" => "lösenordet har ändrats",
        "invalid_old_password" => "ogiltigt gammalt lösenord",
        "account_deleted" => "ditt konto har tagits bort",
        "notifications" => "meddelandestatus ändrad",
        "there_was_problem" => "Det uppstod ett problem i sendgrid, försök igen senare",
    ],

    "option_value" => [
        "create" => "Nytt alternativvärde har skapats",
        "update" => "Alternativets värde har ändrats",
        "delete" => "Alternativet har tagits bort",
    ],

    "driver" => [
        "create" => "drivrutin skapad framgångsrik",
        "update" => "förardata uppdaterades",
        "delete" => "drivrutinen raderad lyckades",
        "status_changed" => "din status ändrades till :status",
        "active" => "aktiva",
        "in_active" => "inaktiv",
        "have_not_driver" => "Du har ingen förare",
    ],
    "customer" => [
        "create" => "kund skapad framgångsrikt",
        "update" => "kunden har uppdaterats",
        "delete" => "kunden har tagits bort",
    ],
    "general_settings" => [
        "update" => "allmänna inställningar har uppdaterats"
    ],
    "option_template" => [
        "create" => "en ny alternativmall har skapats",
        "update" => "alternativmallen har uppdaterats",
        "delete" => "alternativmallen har tagits bort",
    ],
    "user" => [
        "self_update" => "din information har uppdaterats"
    ],

    "terms" => [
        "info_updated" => "informationen har uppdaterats"
    ],
    "question" => [
        "create" => "en ny fråga skapad med framgång",
        "update" => "frågan har uppdaterats",
        "delete" => "frågan har tagits bort",
    ],
    "order" => [
        "create" => "en ny order skapades framgångsrikt",
        "update" => "ordern har uppdaterats",
        "delete" => "ordern har tagits bort",
        "reject" => "beställningen avvisades",
        "change_status" => "beställningsändringsstatus till :status framgångsrikt",
        "accepted" => "beställning godkänd",
        "accepted_by_another" => "order accepteras av en annan förare",
        "restaurant_is_not_available_in_your_location" => "ordern har avbrutits. restaurang finns inte
        på din plats.",
        "distance__too_far" => "avstånd för långt",
        "your_account_not_available" => "ditt konto inte tillgängligt, ändra ditt konto till tillgängligt",
        "not_paid" => "Beställningen kan inte accepteras, beställningen är inte betald",

    ],

    "restaurant_follow" => [
        "update" => "restaurang följ uppdaterad framgångsrikt",
    ],
    "item_follow" => [
        "update" => "favoritstatus uppdaterad",
    ],

    "rate_order" => [
        "already" => "du har redan betygsatt denna order",
        "still" => "du kan inte betygsätta denna order ännu",
        "wait" => "du kan inte betygsätta denna beställning nu, du måste vänta i :time minuter",
        "done" => "Din begäran har utvärderats",
        "time_over" => "tyvärr, du kan inte betygsätta en beställning efter 24 timmars leverans",
    ],
    "contact" => [
        "create" => "skapat framgångsrikt, tack för att du kontaktade oss",
        "update" => "kontaktens data har uppdaterats framgångsrikt",
        "delete" => "kontakt har tagits bort",
    ],

    "feedback" => [
        "send" => "din feedback har skickats, tack för att du kontaktade oss",
        "delete" => "feedback har tagits bort",
        "already" => "du har redan spelat upp det här meddelandet",
        "replay" => "repris har skickats",
    ],

    "discount_code" => [
        "create" => "rabattkod skapad framgångsrikt",
        "update" => "rabattkoden har uppdaterats framgångsrikt",
        "delete" => "rabattkoden raderades framgångsrikt",
        "sms_rate" => "hej :name , en rabattkod :code har skickats till dig med :amount % rabatt giltig från :start till :end på beställningar med minimipris
        :min_price och du kan använda den :times gånger",
        "invalid_code" => "denna kod är inte längre giltig",
        "invalid_for_this_order" => "du kan inte använda den här koden med denna restuarant",
        "min_order_price" => "ditt beställningspris är lägre än den lägsta beställningsgränsen för kod",
    ],
    "admin" => [
        "create" => "kontoadministratören har skapats",
        "update" => "kontoadministratören har uppdaterats",
        "delete" => "kontoadministratören raderades",
        "attached" => "behörighet kopplad till administratören",
        "detached" => "behörigheten har kopplats bort från administratören",
    ],
    "create" => "skapandeprocessen avslutad",
    "update" => "uppdateringsprocessen slutförd",
    "delete" => "borttagningsprocessen klar",
    "failed" => "Oops! Något gick fel",
    "success" => "operationen genomförd framgångsrikt",
    "not_available" => "vissa alternativ är inte tillgängliga, vänligen välj dina alternativ igen",
    "you_can_not_reorder" => "Tyvärr, vissa objekt har ändrats. Du kan göra en ny beställning",
    "server_unavailable"=>"Servern är tillfälligt otillgänglig"

];
