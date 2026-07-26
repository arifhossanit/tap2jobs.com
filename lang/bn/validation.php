<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute ক্ষেত্রটি গ্রহণ করতে হবে।',
    'accepted_if' => ':other যখন :value হবে তখন :attribute গ্রহণ করতে হবে।',
    'active_url' => ':attribute একটি বৈধ URL হতে হবে।',
    'after' => ':attribute অবশ্যই :date এর পরে একটি তারিখ হতে হবে।',
    'after_or_equal' => ':attribute অবশ্যই :date এর পরে বা সমান একটি তারিখ হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর থাকতে হবে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে হবে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা থাকতে হবে।',
    'array' => ':attribute একটি অ্যারে হতে হবে।',
    'ascii' => ':attribute শুধুমাত্র সিঙ্গেল-বাইট আলফানিউমেরিক অক্ষর এবং প্রতীক থাকতে হবে।',
    'before' => ':attribute অবশ্যই :date এর আগে একটি তারিখ হতে হবে।',
    'before_or_equal' => ':attribute অবশ্যই :date এর আগে বা সমান একটি তারিখ হতে হবে।',
    'between' => [
        'array' => ':attribute এর মধ্যে :min এবং :max আইটেম থাকতে হবে।',
        'file' => ':attribute এর আকার :min এবং :max কিলোবাইটের মধ্যে হতে হবে।',
        'numeric' => ':attribute :min এবং :max এর মধ্যে হতে হবে।',
        'string' => ':attribute :min এবং :max অক্ষরের মধ্যে হতে হবে।',
    ],
    'boolean' => ':attribute ক্ষেত্রটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মেলে না।',
    'current_password' => 'পাসওয়ার্ডটি ভুল।',
    'date' => ':attribute একটি বৈধ তারিখ হতে হবে।',
    'date_equals' => ':attribute অবশ্যই :date এর সমান একটি তারিখ হতে হবে।',
    'date_format' => ':attribute অবশ্যই :format ফরম্যাটের সাথে মেলে।',
    'decimal' => ':attribute এ :decimal দশমিক স্থান থাকতে হবে।',
    'declined' => ':attribute ক্ষেত্রটি প্রত্যাখ্যান করতে হবে।',
    'declined_if' => ':other যখন :value হবে তখন :attribute প্রত্যাখ্যান করতে হবে।',
    'different' => ':attribute এবং :other ভিন্ন হতে হবে।',
    'digits' => ':attribute হতে হবে :digits সংখ্যা।',
    'digits_between' => ':attribute :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions' => ':attribute এর ভুল ইমেজ মাত্রা রয়েছে।',
    'distinct' => ':attribute ক্ষেত্রের একটি সদৃশ মান রয়েছে।',
    'doesnt_end_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শেষ হতে পারবে না: :values।',
    'doesnt_start_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শুরু হতে পারবে না: :values।',
    'email' => ':attribute একটি বৈধ ইমেইল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শেষ হতে হবে: :values।',
    'enum' => 'নির্বাচিত :attribute অবৈধ।',
    'exists' => 'নির্বাচিত :attribute অবৈধ।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute ক্ষেত্রটির একটি মান থাকতে হবে।',
    'gt' => [
        'array' => ':attribute এ :value এর বেশি আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বড় হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বড় হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে বড় হতে হবে।',
    ],
    'gte' => [
        'array' => ':attribute এ :value বা তার বেশি আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বড় বা সমান হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বড় বা সমান হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে বড় বা সমান হতে হবে।',
    ],
    'image' => ':attribute একটি ছবি হতে হবে।',
    'in' => 'নির্বাচিত :attribute অবৈধ।',
    'in_array' => ':attribute ক্ষেত্রটি :other এ বিদ্যমান থাকতে হবে।',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lowercase' => ':attribute ছোট হাতের অক্ষরে হতে হবে।',
    'lt' => [
        'array' => ':attribute এ :value এর কম আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে ছোট হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে ছোট হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে ছোট হতে হবে।',
    ],
    'lte' => [
        'array' => ':attribute এ :value এর বেশি আইটেম থাকতে পারবে না।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে ছোট বা সমান হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে ছোট বা সমান হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে ছোট বা সমান হতে হবে।',
    ],
    'mac_address' => ':attribute একটি বৈধ MAC ঠিকানা হতে হবে।',
    'max' => [
        'array' => ':attribute এ :max এর বেশি আইটেম থাকতে পারবে না।',
        'file' => ':attribute :max কিলোবাইটের চেয়ে বড় হতে পারবে না।',
        'numeric' => ':attribute :max এর চেয়ে বড় হতে পারবে না।',
        'string' => ':attribute :max অক্ষরের চেয়ে বড় হতে পারবে না।',
    ],
    'max_digits' => ':attribute এ :max এর বেশি সংখ্যা থাকতে পারবে না।',
    'mimes' => ':attribute টাইপের একটি ফাইল হতে হবে: :values।',
    'mimetypes' => ':attribute টাইপের একটি ফাইল হতে হবে: :values।',
    'min' => [
        'array' => ':attribute এ কমপক্ষে :min আইটেম থাকতে হবে।',
        'file' => ':attribute কমপক্ষে :min কিলোবাইট হতে হবে।',
        'numeric' => ':attribute কমপক্ষে :min হতে হবে।',
        'string' => ':attribute কমপক্ষে :min অক্ষর হতে হবে।',
    ],
    'min_digits' => ':attribute এ কমপক্ষে :min সংখ্যা থাকতে হবে।',
    'missing' => ':attribute ক্ষেত্রটি অনুপস্থিত থাকতে হবে।',
    'missing_if' => ':other যখন :value হবে তখন :attribute অনুপস্থিত থাকতে হবে।',
    'missing_unless' => ':other :values না হলে :attribute অনুপস্থিত থাকতে হবে।',
    'missing_with' => ':values উপস্থিত থাকলে :attribute অনুপস্থিত থাকতে হবে।',
    'missing_with_all' => ':values উপস্থিত থাকলে :attribute অনুপস্থিত থাকতে হবে।',
    'multiple_of' => ':attribute :value এর গুণিতক হতে হবে।',
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute ফরম্যাটটি অবৈধ।',
    'numeric' => ':attribute একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters' => ':attribute এ কমপক্ষে একটি অক্ষর থাকতে হবে।',
        'mixed' => ':attribute এ কমপক্ষে একটি বড় হাতের এবং একটি ছোট হাতের অক্ষর থাকতে হবে।',
        'numbers' => ':attribute এ কমপক্ষে একটি সংখ্যা থাকতে হবে।',
        'symbols' => ':attribute এ কমপক্ষে একটি প্রতীক থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি ডাটা লিকে উপস্থিত হয়েছে। অনুগ্রহ করে একটি ভিন্ন :attribute চয়ন করুন।',
    ],
    'present' => ':attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'prohibited' => ':attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_if' => ':other যখন :value হবে তখন :attribute নিষিদ্ধ।',
    'prohibited_unless' => ':other :values তে না থাকলে :attribute নিষিদ্ধ।',
    'prohibits' => ':attribute ক্ষেত্রটি :other কে উপস্থিত হতে নিষেধ করে।',
    'regex' => ':attribute ফরম্যাটটি অবৈধ।',
    'required' => ':attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_array_keys' => ':attribute ক্ষেত্রটিতে এর জন্য এন্ট্রি থাকতে হবে: :values।',
    'required_if' => ':other যখন :value হবে তখন :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_if_accepted' => ':other গ্রহণ করা হলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_unless' => ':other :values তে না থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_with' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_with_all' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_without' => ':values উপস্থিত না থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_without_all' => ':values এর কোনটিই উপস্থিত না থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'same' => ':attribute এবং :other মেলে।',
    'size' => [
        'array' => ':attribute এ :size আইটেম থাকতে হবে।',
        'file' => ':attribute :size কিলোবাইট হতে হবে।',
        'numeric' => ':attribute :size হতে হবে।',
        'string' => ':attribute :size অক্ষর হতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শুরু হতে হবে: :values।',
    'string' => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique' => ':attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ হয়েছে।',
    'uppercase' => ':attribute বড় হাতের অক্ষরে হতে হবে।',
    'url' => ':attribute একটি বৈধ URL হতে হবে।',
    'ulid' => ':attribute একটি বৈধ ULID হতে হবে।',
    'uuid' => ':attribute একটি বৈধ UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];