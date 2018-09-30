<?php


namespace Common\Validation;


use Phalcon\Validation;


/**
 * Common\Validation\SimpleValidation
 *
 * Validate by description like
 *
 * <code>
 *      $description = [
 *      # name must be alpha and require
 *      'firstname, lastname' => 'required|alpha',
 *
 *      # length
 *      'lastname'            => 'length:5,18',
 *
 *      # must be an email format
 *      # must be unique under 'users' table
 *      'email'               => 'email',
 *
 *      # must be numeric
 *      'id'                  => 'numeric',
 *      'age'                 => 'digit',
 *      'info[country]'       => 'alpha',
 *
 *      # the format must be 'm-Y.d H:i'
 *      'date'                => 'date:(m-Y.d H:i)',
 *
 *      'type'          => 'include:[1,2,3,4]',
 *
 *      # the provided phone number should follow the format
 *      'phone'           => 'regex:/^\d{7,12}$/',
 *
 *      'randNum'             => 'between:1, 10|numeric',
 *
 *      # the value must be an IP Format
 *      'ip'                  => 'ip',
 *      'password'            => 'required',
 *
 *      # it must be a json format
 *      'site'                => 'url',
 *
 *      'evenNumber, evenNumber2, evenNumber4' => 'include:[0,2,4,6]',
 *
 *      'oddNumber, oddNumber3, oddNumber5' => 'include:[1,3,5,7]',
 *
 *      ];
 *
 *      $data = [
 *      'email'    => null,
 *      'username' => 'chai65',
 *      'age'      => 12,
 *      'id'       => '1',
 *      ];
 *
 *
 *      $customMessage = [
 *      'eventNumber.include' => ':eventNumber phải là số chẵn',
 *      'email.include'       => 'Mail sai rồi',
 *      'firstname.required'  => 'Thiếu tên firstname'
 *      ];
 *
 *      // Validation throw exception on failure
 *      $result = SimpleValidation::validateOrFail($description, $data, $customMessage);
 *
 *      // Or
 *      $messages = SimpleValidation::validate($description, $data, $customMessage);
 * </code>
 *
 */
class SimpleValidation
{

    protected $description;

    protected $customMessages;

    protected $mapRules;


    public function __construct($description, $customMessages = null)
    {
        $this->description = $description;

        $this->customMessages = $customMessages;
    }


    /**
     * @param $description
     * @param $data
     * @param null $customMessages
     *
     * @return bool
     */
    static function validateOrFail($description, $data, $customMessages = null)
    {
        $failedMessages = static::validate($description, $data, $customMessages);

        if ($failedMessages->count()) {
            $message = $failedMessages->current();
            throw new \InvalidArgumentException($message->getMessage());
        }

        return true;
    }


    /**
     * @param $description
     * @param $data
     * @param null $customMessages
     *
     * @return Validation\Message\Group
     */
    static function validate($description, $data, $customMessages = null)
    {
        $validation = static::from($description, $customMessages);
        $validation->validate($data);
        return $validation->getMessages();
    }


    /**
     * @param $description
     * @param null $customMessages
     *
     * @return Validation
     */
    static function from($description, $customMessages = null)
    {
        $instance = new static($description, $customMessages);
        return $instance->parseAllDescription();
    }


    /**
     * @return Validation
     */
    function parseAllDescription()
    {
        $validation = new Validation();
        foreach ($this->description as $fields => $rules) {

            if (is_string($fields)) {
                $fields = explode(',', str_replace(' ', '', $fields));
            }

            foreach ($this->parseLineDescription($fields, $rules) as $validator) {

                $validation->add($fields, $validator);
            }
        }

        return $validation;
    }


    private function parseLineDescription($fields, $rules)
    {
        if (is_string($fields)) {
            $fields = explode(',', str_replace(' ', '', $fields));
        }

        if (is_string($rules)) {
            $rules = explode('|', str_replace(' ', '', $rules));
        }


        foreach ($rules as $ruleDescription) {
            $ruleDescription = explode(':', $ruleDescription);
            $ruleName = $ruleDescription[0];
            $ruleConstructInput = [];

            if ($ruleName == null) {
                continue;
            }


            if (isset($ruleDescription[1])) {

                if (preg_match('/^\[.*\]$/', $ruleDescription[1])) {
                    // Convert string "[1, 2, 3, 4]" to an array
                    $ruleInputParams = explode(',', str_replace([' ', '[', ']'], '', $ruleDescription[1]));
                    $ruleInputParams = [$ruleInputParams];

                } elseif ($ruleName == 'regex') {
                    $ruleInputParams = [$ruleDescription[1]];

                } else {
                    $ruleInputParams = explode(',', str_replace(' ', '', $ruleDescription[1]));

                }

            } else {
                $ruleInputParams = null;

            }

            if (!isset($this->getMapRules()[$ruleName])) {
                throw new \InvalidArgumentException("Rule name '$ruleName' is invalid or not supported yet");
            }

            $ruleInfo = $this->getMapRules()[$ruleName];
            $ruleClass = $ruleInfo['class'];


            if (isset($ruleInfo['params']) && $ruleInputParams) {
                foreach ($ruleInfo['params'] as $pName => $idx) {
                    $ruleConstructInput[$pName] = array_fill_keys($fields, $ruleInputParams[$idx]);
                }
            }

            foreach ($fields as $field) {
                if ($this->customMessages) {

                    $message = isset($this->customMessages["$field.$ruleName"])
                        ? $this->customMessages["$field.$ruleName"]

                        : (isset($this->customMessages["$field"])
                            ? $this->customMessages["$field"]
                            : null
                        );

                    $message && $ruleConstructInput['message'] = $message;
                }
            }

            if ($ruleName != 'required') {
                $ruleConstructInput['allowEmpty'] = true;
            }

            yield new $ruleClass($ruleConstructInput);
        }
    }


    /**
     * Tạm thời chưa support hết
     * Lazy init
     * @return array
     */
    function getMapRules()
    {
        $a = [
            'require' => 'email | status | abcxyz',
            'alnum'   => 'username | description',
        ];

        $b = [
            'email'    => 'require|email',
            'username' => 'require|alnum',
            'password' => 'require|length:0,1:',
        ];

        if ($this->mapRules == null) {
            $this->mapRules = [
                'alnum'    => [
                    'class' => Validation\Validator\Alnum::class,
                ],
                'alpha'    => [
                    'class' => Validation\Validator\Alpha::class,
                ],
                'between'  => [
                    'class'  => Validation\Validator\Between::class,
                    'params' => [
                        'minimum' => 0,
                        'maximum' => 1,
                    ]
                ],
                'cc'       => [
                    'class' => Validation\Validator\CreditCard::class,
                ],
                'confirm'  => [
                    'class'  => Validation\Validator\Confirmation::class,
                    'params' => [
                        'with' => 0
                    ]
                ],
                'date'     => [
                    'class'  => Validation\Validator\Date::class,
                    'params' => [
                        'format' => 0
                    ]
                ],
                'digit'    => [
                    'class' => Validation\Validator\Digit::class,
                ],
                'email'    => [
                    'class' => Validation\Validator\Email::class,
                ],
                'exclude'  => [
                    'class'  => Validation\Validator\ExclusionIn::class,
                    'params' => [
                        'domain' => 0
                    ]
                ],
                'include'  => [
                    'class'  => Validation\Validator\InclusionIn::class,
                    'params' => [
                        'domain' => 0
                    ]
                ],
                'numeric'  => [
                    'class' => Validation\Validator\Numericality::class,
                ],
                'required' => [
                    'class' => Validation\Validator\PresenceOf::class,
                ],
                'regex'    => [
                    'class'  => Validation\Validator\Regex::class,
                    'params' => [
                        'pattern' => 0
                    ]
                ],
                'length'   => [
                    'class'  => Validation\Validator\StringLength::class,
                    'params' => [
                        'min' => 0,
                        'max' => 1,
                    ]
                ],
                'url'      => [
                    'class' => Validation\Validator\Url::class,
                ],
                'ip'       => [
                    'class' => Validation\Validator\IpValidator::class,
                ],
            ];
        }

        return $this->mapRules;
    }
}