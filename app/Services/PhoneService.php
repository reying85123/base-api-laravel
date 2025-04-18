<?php

namespace App\Services;

use Propaganistas\LaravelPhone\PhoneNumber;

use Illuminate\Support\Facades\Validator;

class PhoneService
{

    protected $phone;

    protected $country;

    protected $phoneNumber;

    protected $countries = [
        "TW",
        "US",
        "CN",
        "HK",
        "MY",
        "TH",
        "JP",
        "KR",
        "ID",
        "PH",
        "GD",
        "GU",
        "JM",
        "KN",
        "KY",
        "LC",
        "MP",
        "MS",
        "PR",
        "SX",
        "TC",
        "TT",
        "VC",
        "VG",
        "VI",
        "RU",
        "KZ",
        "EG",
        "ZA",
        "GR",
        "NL",
        "BE",
        "FR",
        "ES",
        "HU",
        "IT",
        "VA",
        "RO",
        "CH",
        "AT",
        "GB",
        "GG",
        "IM",
        "JE",
        "DK",
        "SE",
        "NO",
        "SJ",
        "PL",
        "DE",
        "PE",
        "MX",
        "CU",
        "AR",
        "BR",
        "CL",
        "CO",
        "VE",
        "AU",
        "BS",
        "CC",
        "CX",
        "NZ",
        "SG",
        "VN",
        "BM",
        "BB",
        "AS",
        "AI",
        "AG",
        "TR",
        "IN",
        "PK",
        "AF",
        "LK",
        "MM",
        "IR",
        "SS",
        "MA",
        "DM",
        "EH",
        "DZ",
        "TN",
        "LY",
        "GM",
        "SN",
        "MR",
        "ML",
        "GN",
        "CI",
        "BF",
        "NE",
        "TG",
        "BJ",
        "MU",
        "LR",
        "SL",
        "GH",
        "NG",
        "TD",
        "CF",
        "CM",
        "CV",
        "ST",
        "GQ",
        "GA",
        "CG",
        "CD",
        "AO",
        "GW",
        "IO",
        "AC",
        "SC",
        "SD",
        "RW",
        "ET",
        "SO",
        "DJ",
        "KE",
        "TZ",
        "UG",
        "BI",
        "MZ",
        "ZM",
        "MG",
        "RE",
        "YT",
        "ZW",
        "NA",
        "MW",
        "LS",
        "BW",
        "SZ",
        "KM",
        "SH",
        "TA",
        "ER",
        "AW",
        "FO",
        "GL",
        "GI",
        "PT",
        "LU",
        "IE",
        "IS",
        "AL",
        "MT",
        "CY",
        "FI",
        "AX",
        "BG",
        "LT",
        "LV",
        "EE",
        "MD",
        "AM",
        "BY",
        "AD",
        "MC",
        "SM",
        "UA",
        "RS",
        "ME",
        "XK",
        "HR",
        "SI",
        "BA",
        "MK",
        "CZ",
        "SK",
        "LI",
        "FK",
        "BZ",
        "GT",
        "SV",
        "HN",
        "NI",
        "CR",
        "PA",
        "PM",
        "HT",
        "GP",
        "BL",
        "MF",
        "BO",
        "GY",
        "EC",
        "GF",
        "PY",
        "MQ",
        "SR",
        "UY",
        "CW",
        "BQ",
        "TL",
        "NF",
        "BN",
        "NR",
        "PG",
        "TO",
        "SB",
        "VU",
        "FJ",
        "PW",
        "WF",
        "CK",
        "NU",
        "WS",
        "KI",
        "NC",
        "TV",
        "PF",
        "TK",
        "FM",
        "MH",
        "KP",
        "MO",
        "KH",
        "LA",
        "CA",
        "BD",
        "MV",
        "LB",
        "JO",
        "SY",
        "IQ",
        "KW",
        "SA",
        "YE",
        "UZ",
        "OM",
        "PS",
        "AE",
        "IL",
        "BH",
        "QA",
        "BT",
        "MN",
        "NP",
        "TJ",
        "TM",
        "AZ",
        "GE",
        "KG",
        "DO"
    ];

    public function __construct($phone, $country = null)
    {
        $this->phone = $phone;
        if ($country) {
            $this->phoneNumber = PhoneNumber::make($this->phone, $country);
        } else {
            $this->setCountry();
            $this->phoneNumber = PhoneNumber::make($this->phone, $this->country);
        }
    }

    public static function validate()
    {
        return self::validatePhoneNumber(self::$phone, self::$country);
    }

    public static function formatE164()
    {
        return self::$phoneNumber->formatE164();
    }

    protected function setCountry()
    {
        collect($this->countries)
            ->each(function ($item) {
                if ($this->validatePhoneNumber($this->phone, $item)) {
                    $this->country = $item;
                    return false;
                }
            });
    }

    protected function validatePhoneNumber($phoneNumber, $country)
    {
        $validator = Validator::make(
            ['phone_number' => $phoneNumber],
            ['phone_number' => 'phone:' . $country]
        );
        return $validator->passes();
    }
}
