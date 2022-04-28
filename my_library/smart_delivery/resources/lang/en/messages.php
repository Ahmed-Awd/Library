<?php

return [
    "notifications" => [
        "order_status_changes" => "order number :number status changed to :status ",
        "more" => "click here to show more.",
        "new_order"=>"new order is now available",
        "update_order"=>"The current order has been modified",
        "accept_order" =>"click here to accept it",
        "not_arrived_yet" => "order number :number , driver did not reach the store yet",
        "driver_coming" => "driver :driver is coming to take the order from you",
        "not_accepted_yet" => "order number :number is not accepted yet",
        "special_order_change" => ":number numaralı siparişi teslim etmekle görevlendirildiniz, almak için mağazaya gidin"
    ],
    "auth" => [
        "invalid" => "Invalid account details",
        "verify" => "please verify your account",
        "disabled" => "your user has been disabled,please contact the admin",
        "logout" => "Logged out successfully",
        "created" => "your account created successfully",
        "reg" => "your signup success,an activation code is sent to your phone",
        "driver_verify" => "account verified successfully, please wait for admin detail confirmation",
        "generate_code" => "generate code successfully",
        "try" => "try again",
        "mail_code" => "An email sent to your email with a reset code",
        "phone_code" => "a reset code sent to your phone number",
        "wrong" => "something went wrong",
        "code" => "invalid code",
        "password_changed" => "password changed successfully",
        "already" => "your account is already activated",
        "code_expired" => "reset code time expired,try resend the code",
        "invalid_old_password" => "incorrect old password",
        "your_account" => "your account is now :statue",
    ],
    "charge" => [
        "invalid" => "please enter a valid code",
        "already" => "this code is already used,please enter another code",
        "success" => "charging your balance success",
    ],
    "error" => [
        "error" => "error"
    ],
    "driver_order" => [
        "not_enough_balance" => "You don`t have enough balance!",
        "have_order" => "You  already have order ,please finish it first",
        "accepted_success" => "you accepted order successfully",
        "already_accepted" => "order is accepted by another driver",
        "taken_success" => "you received order successfully",
        "qr_code_wrong" => "qr code does not match",
        "delivered_success" => "you delivered order successfully",
        "not_in_range" => "your current location is not near enough from the customer",
    ],
    "process" => [
        "create" => "creating process done successfully",
        "update" => "updating process done successfully",
        "delete" => "deleting completed",
        "save" => "save completed",
    ],
    "record" => [
        "create" => "creating record done successfully",
        "update" => "updating record done successfully",
        "delete" => "deleting record  completed",
        "save" => "save record  completed",
    ],
    "lang" => [
        "change" => "your current language changed"
    ],
    "store_order"=>[
        "no_store" => "Does not have store",
        "no_order" => "Does not have order",
        "rated" => "order :id rated successfully",
        "suspended" => "Order has been suspended. Balance is not enough.",
        "reordered" => "Reordered done successfully"
    ],
    "place" => [
        "out_of_range" => "this place is out of delivery range"
    ],
    "create" => "creating process completed",
    "update" => "updating process completed",
    "delete" => "deleting process done successfully",
    "failed" => "Oops , something went wrong",
    "cancel" => "cancel process done successfully",
    "success" => "operation done successfully",
    "archived" => "your record(s) got archived",
    "wait_for_admin" => "please wait for the admin to confirm",
    "wait_for_accepted" => "please wait for accepted",
    "please_verify_phone" => "please verify your new phone number , a code has been sent to you via message",
    "max_code_usage" => "you used your maximum tries for this code 5 tries",
    "can_not_cancel" => "You can't cancel order because the order status is :status",

];
