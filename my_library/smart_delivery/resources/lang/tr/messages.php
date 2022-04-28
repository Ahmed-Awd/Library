<?php

return [
    "notifications" => [
        "order_status_changes" => "sipariş numarası :number durumu :status olarak değiştirildi ",
        "more" => "daha fazlasını göstermek için buraya tıklayın.",
        "new_order" => "yeni sipariş hazır",
        "update_order"=>"Mevcut sipariş değiştirildi",
        "accept_order" => "kabul etmek için burayı tıklayın",
        "not_arrived_yet" => ":number numaralı sipariş, sürücü henüz mağazaya ulaşmadı",
        "driver_coming" => "şoför :driver sizden sipariş almaya geliyor",
        "not_accepted_yet" => ":number numaralı sipariş henüz kabul edilmedi",
        "special_order_change" => "Sipariş numarası :number teslim etmekle görevlendirildiniz, almak için mağazaya gidin"
    ],
    "auth" => [
        "invalid" => "Geçersiz hesap bilgileri",
        "verify" => "Lütfen hesabınızı doğrulayın",
        "disabled" => "kullanıcınız devre dışı bırakıldı, lütfen yönetici ile iletişime geçin",
        "logout" => "Oturum başarıyla kapatıldı",
        "created" => "hesabınız başarıyla oluşturuldu",
        "reg" => "kayıt işleminiz başarılı, telefonunuza bir aktivasyon kodu gönderildi",
        "driver_verify" => "hesap başarıyla doğrulandı, lütfen yönetici ayrıntısı onayını bekleyin",
        "generate_code" => "kodu başarıyla oluştur",
        "try" => "Tekrar deneyin",
        "mail_code" => "E-postanıza sıfırlama koduyla gönderilen bir e-posta",
        "phone_code" => "telefon numaranıza gönderilen bir sıfırlama kodu",
        "wrong" => "bir şeyler yanlış gitti",
        "code" => "geçersiz kod",
        "password_changed" => "parola başarıyla değiştirildi",
        "already" => "hesabınız zaten aktif",
        "code_expired" => "kod süresini sıfırlayın, kodu yeniden göndermeyi deneyin",
        "invalid_old_password" => "geçersiz eski şifre",
        "your_account" => "senin hesabın şimdi :statue",
    ],
    "charge" => [
        "invalid" => "lütfen geçerli bir kod girin",
        "already" => "bu kod zaten kullanılıyor, lütfen başka bir kod girin",
        "success" => "bakiye başarınızı şarj etme",
    ],

    "error" => [
        "error" => "hata"
    ],

    "driver_order" => [
        "not_enough_balance" => "Yeterli bakiyeniz yok!",
        "have_order" => "Zaten siparişiniz var, lütfen önce bitirin",
        "accepted_success" => "siparişi başarıyla kabul ettiniz",
        "already_accepted" => "Sipariş başka bir sürücü tarafından kabul edildi",
        "taken_success" => "Siparişi başarıyla aldınız",
        "qr_code_wrong" => "qr kodu eşleşmiyor",
        "delivered_success" => "Siparişi başarıyla teslim ettiniz",
        "not_in_range" => "mevcut konumunuz müşteriye yeterince yakın değil",
    ],
    "process" => [
        "create" => "oluşturma işlemi başarıyla tamamlandı",
        "update" => "güncelleme işlemi başarıyla tamamlandı",
        "delete" => "silme tamamlandı",
        "save" => "kayıt tamamlandı"
    ],
    "record" => [
        "create" => "kayıt oluşturma başarıyla tamamlandı",
        "update" => "güncelleme kaydı başarıyla tamamlandı",
        "delete" => "silme kaydı tamamlandı",
        "save" => "kayıt tamamlandı",
    ],
    "lang" => [
        "change" => "mevcut diliniz değişti"
    ],
    "store_order" => [
        "no_store" => "Mağazası yok",
        "no_order" => "Sipariş yok",
        "rated" => "sipariş :id başarıyla derecelendirildi",
        "suspended" => "Sipariş askıya alındı. Bakiye yeterli değil.",
        "reordered" => "Yeniden sipariş başarıyla tamamlandı"
    ],
    "place" => [
        "out_of_range" => "burası menzil dışında"
    ],
    "create" => "oluşturma işlemi tamamlandı",
    "update" => "güncelleme işlemi tamamlandı",
    "delete" => "silme işlemi başarıyla tamamlandı",
    "failed" => "Hata , bir şeyler ters gitti",
    "cancel" => "iptal işlemi başarıyla tamamlandı",
    "success" => "işlem başarıyla tamamlandı",
    "archived" => "kayıt(lar)ınız arşivlendi",
    "wait_for_admin" => "lütfen yöneticinin onaylamasını bekleyin",
    "wait_for_accepted" => "lütfen kabul edilmesini bekleyin",
    "please_verify_phone" => "lütfen yeni telefon numaranızı doğrulayın, size mesaj yoluyla bir kod gönderildi.",
    "max_code_usage" => "bu kod için maksimum denemenizi kullandınız 5 deneme",
    "can_not_cancel" => "Sipariş durumu şu olduğundan siparişi iptal edemezsiniz :status",
];
