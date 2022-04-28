<?php

return [
    "notifications" => [
        "order_status_changes" => "order number :number status changed to :status ",
        "more" => "click here to show more.",
        "new_order"=>"new order is now available",
        "new_order_from_admin" => "new order from admin is now available",
        "order_scheduling" => "order :number scheduling to  :time",
        "accept_order_by_driver" =>"order number :number accepted,
        the driver is coming to take the order from you",
        "accept_order_by_restaurant" =>"order number :number accepted ,the order is being prepared",
        "not_accepted_yet" =>"order number :number not accepted yet",
        "not_accepted_yet_by_driver" =>"order number :number not accepted yet by any driver",
        "order_ready_to_delivered" => "order number :number is order ready to delivered",
        "order_ready_to_takeaway" => "order number :number is order ready to takeaway",
        "sent" => "notification sent successfully",
        "late" => "order number :number is late for the client",
        "restaurant_status_changes" => "restaurant status changed to :status ",
        "order_ready_to_takeaway_from_driver" => "order :number ready to takeaway from driver ",
    ],
    "Address" => [
        "create" => "Address created successfully",
        "update" => "address updated successfully",
        "delete" => "address deleted successfully",
    ],
    "restaurant" => [
        "create" => "restaurant created successfully",
        "update" => "restaurant updated successfully",
        "delete" => "restaurant deleted successfully",
        "have_not_restaurant" => "You don't have a restaurant",
        "restaurant_is_close" => "restaurant is close",
        "restaurant_is_close_for_delivery" => "restaurant is closed for delivery",
        "restaurant_is_close_for_takeaway" => "restaurant is closed for takeaway",
        "restaurant_not_support_schudeling" => "restaurant not support scheduling",
        "date_must_be_after_today" => "date_must be today or after today",
        "attached" => "method payment attached to the restaurant successfully",
        "detached" => "method payment detached from the restaurant successfully",

    ],
    "restaurant_category" => [
        "create" => "category created successfully",
        "update" => "category updated successfully",
        "delete" => "category deleted successfully",
    ],
    "worktime" => [
        "create" => "worktime created successfully",
        "update" => "worktime updated successfully",
        "delete" => "worktime deleted successfully",
    ],
    "restaurant_settings" => [
        "create" => "settings created successfully",
        "update" => "settings updated successfully",
        "delete" => "settings deleted successfully",
    ],
    "restaurant_status" => [
        "update" => "restaurant status updated successfully",
    ],
    "restaurant_rating" => [
        "create" => "restaurant rating done successfully",
        "update" => "restaurant rating done successfully",
        "delete" => "restaurant rating deleted successfully",
        "order_first" => "please order first before rate our restaurant",
    ],
    "Tax" => [
        "create" => "Tax created successfully",
        "update" => "Tax updated successfully",
        "delete" => "Tax deleted successfully",
    ],

    "menu_category" => [
        "create" => "menu category created successfully",
        "update" => "menu category updated successfully",
        "delete" => "menu category deleted successfully",
    ],
    "menu_item" => [
        "create" => "menu item created successfully",
        "update" => "menu item updated successfully",
        "delete" => "menu item deleted successfully",
        "attached" => "option attached to the item successfully",
        "detached" => "option detached from the item successfully",
    ],

    "auth" => [
        "verification" => "Email verification link sent on your email address",
        "verified" => "Your Account has been verified successfully",
        "disabled" => "your account is disabled ,please contact our support",
        "verify" => "please verify your email first",
        "email_exist" => "sorry,this email already exist you can`t register with this email",
        "verification_mail" => "verification Email",
        "invalid_login" => "Invalid login details",
        "logout" => "Logged out successfully",
        "reset_send" => "An email sent to :mail with a reset code",
        "reset_title" => "Hello :name, We received a request to reset the password for your MINI PIZZA account",
        "reset_body" => "Your code is",
        "password_reset" => "Password Reset",
        "invalid_mail" => "invalid email address",
        "invalid_code" => "invalid reset code",
        "password_change" => "password changed successfully",
        "invalid_old_password" => "invalid old password",
        "notifications" => "notifications status switched",
        "account_deleted" => "your account removed successfully",
        "there_was_problem" => "There was a problem in sendgrid, try again later",
    ]

    ,
    "driver" => [
        "create" => "driver created successfully",
        "update" => "driver`s data updated successfully",
        "delete" => "driver deleted successfully",
        "status_changed" => "your status changed to :status",
        "active" => "active",
        "in_active" => "inactive",
        "have_not_driver" => "You don't have a driver",
    ],
    "general_settings" => [
        "update" => "general settings updated successfully"
    ],
    "customer" => [
        "create" => "customer created successfully",
        "update" => "customer`s data updated successfully",
        "delete" => "customer deleted successfully",
    ],
    "option_value" => [
        "create" => "a new option`s value created successfully",
        "update" => "option value updated successfully",
        "delete" => "option value deleted successfully",
    ],
    "option_template" => [
        "create" => "a new option template created successfully",
        "update" => "option template updated successfully",
        "delete" => "option template deleted successfully",
    ],

    "user" => [
        "self_update" => "your information updated successfully"
    ],
    "terms" => [
        "info_updated" => "information updated successfully"
    ],

    "restaurant_follow" => [
        "update" => "restaurant follow updated successfully",
    ],
    "item_follow" => [
        "update" => "item favourite status updated successfully",
    ],

    "question" => [
        "create" => "a new question created successfully",
        "update" => "question updated successfully",
        "delete" => "question deleted successfully",
    ],

    "rate_order" => [
        "already" => "you already rated this order",
        "still" => "you can not rate this order yet",
        "wait" => "you can not rate this order now , you have to wait for :time minutes",
        "done" => "order rated successfully",
        "time_over" => "sorry,you can`t rate an order after 24 hours of delivery",
    ],

    "order" => [
        "create" => "a new order created successfully",
        "update" => "order updated successfully",
        "delete" => "order deleted successfully",
        "reject" => "order rejected successfully",
        "change_status" => "order change status to :status successfully",
        "accepted" => "order accepted successfully",
        "accepted_by_another" => "order accepted  by another driver",
        "restaurant_is_not_available_in_your_location" => "order has been cancel. restaurant is not available
        in your location .",
        "distance__too_far" => "distance too far",
        "your_account_not_available" => "your account not available , change your account to available",
        "not_paid" => "The order cannot be accepted, the order is not paid",

    ],
    "contact" => [
        "create" => "created successfully, thank you for contacting us",
        "update" => "contact`s data updated successfully",
        "delete" => "contact deleted successfully",
    ],

    "feedback" => [
        "send" => "your feedback is sent successfully , thanks you for contacting us",
        "delete" => "feedback deleted successfully",
        "already" => "you already replayed to this message",
        "replay" => "replay sent successfully",
    ],
    "discount_code" => [
        "create" => "discount code created successfully",
        "update" => "discount code updated successfully",
        "delete" => "discount code deleted successfully",
        "sms_rate" => "hello  :name , a discount code :code has been sent to you with :amount discount valid from :start to :end on orders
        with minimum price  :min_price and you can use it :times times",
        "invalid_code" => "this code is no longer valid",
        "invalid_for_this_order" => "you can`t use this code with this restaurant",
        "min_order_price" => "your order price is less than minimum code order limit",
    ],
    "admin" => [
        "create" => "account admin created successfully",
        "update" => "account admin updated successfully",
        "delete" => "account admin deleted successfully",
        "attached" => "permission attached to the admin successfully",
        "detached" => "permission detached from the admin successfully",
    ],
    "create" => "creating process completed",
    "update" => "updating process completed",
    "delete" => "deleting process done successfully",
    "failed" => "Oops , something went wrong",
    "success" => "operation done successfully",
    "not_available" => "some options are unavailable ,please reselect your options again",
    "you_can_not_reorder" => "Sorry, some items have been modified. You can make a new order",
    "server_unavailable"=>"The server is temporarily unavailable"

];
