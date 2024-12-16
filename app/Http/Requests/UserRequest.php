<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest as AnikFormRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserRequest extends AnikFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        try {

            $userId = $this->route('id');

            if (!$userId) {
                $userId = $this->input('user_id');
            } else {
                $userId = auth()->user()->id;
            }

            $rules = [
                'step' => [
                    'required',
                    Rule::in(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14),
                ],
            ];

            switch ($this->input('step')) {
                case 1:
                    $rules += [
                        'employee_id'   =>  [
                            'required',
                            'regex:/^[A-Za-z]{3}\d{4}$/',
                            'size:7',
                            Rule::unique('users', 'employee_id'),
                        ],
                        'honorific'     => [
                            'nullable',
                            Rule::in('Mr.', 'Ms.', 'Mrs.'),
                        ],
                        'date_of_birth' => [
                            'required',
                            'date',
                            function ($attribute, $value, $fail) {
                                $eighteenYearsAgo = Carbon::now()->subYears(18);

                                if (strtotime($value) > strtotime($eighteenYearsAgo)) {
                                    $fail("The $attribute must be at least 18 years ago.");
                                }
                            },
                        ],
                        'gender'     => [
                            'nullable',
                            Rule::in('Male', 'Female', 'Other'),
                        ],
                        'office_email'         => [
                            'required',
                            'email:strict',
                            Rule::unique('users', 'email'),
                        ],
                        'mobile'         => [
                            'nullable',
                            Rule::unique('employee_personal_details', 'phone'),
                        ],
                        'reporting_manager_id'     =>  [
                            'required',
                            Rule::exists('users', 'id')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            }),
                        ],
                    ];
                    break;
                case 2:
                    $rules += [
                        'honorific'     => [
                            'nullable',
                            Rule::in('Mr.', 'Ms.', 'Mrs.'),
                        ],
                        'first_name'            =>  'required|min:3|max:40',
                        'date_of_birth'         =>  'required|date',
                        'gender'     => [
                            'required',
                            Rule::in('Male', 'Female', 'Other'),
                        ],
                        'personal_phone'         => [
                            'required',
                            // Rule::unique('employee_personal_details', 'phone')->ignore($userId),
                        ],
                        // 'office_email'         => [
                        //     'required',
                        //     'email:strict',
                        //     Rule::unique('users', 'email')->ignore($userId),
                        // ],                      
                        // 'reporting_manager_id'     =>  [
                        //     'required',
                        //     Rule::exists('users', 'id')->where(function ($query) {
                        //         $query->whereNull('deleted_at');
                        //     }),
                        // ],
                    ];
                    break;
                case 3:
                    $rules += [
                        'image' => [
                            'nullable',
                            'image',
                            'mimes:jpeg,png,jpg,gif',
                            'max:2048', // 2 MB in kilobytes (1 MB = 1024 KB)
                        ],
                        
                    ];
                    break;
                case 4:
                    $rules += [
                        // 'first_name'            =>  'required|min:3|max:40',
                        'user_id'   => [
                            'required',
                            Rule::exists('users', 'id'),
                        ],
                        
                        'reporting_manager_id'     =>  [
                            'nullable',
                            Rule::exists('users', 'id')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            }),
                        ],
                        'designation_id' => [
                            'nullable',
                            Rule::exists('employee_departments', 'id'),
                        ],
                        'department_id' => [
                            'nullable',
                            Rule::exists('employee_designations', 'id'),
                        ],
                    ];
                    break;
                case 5:
                    $rules += [
                        'first_name'            =>  'required|min:3|max:40',
                        'date_of_birth'         =>  'nullable',
                        'date',
                        'user_id'   => [
                            'nullable',
                            Rule::exists('users', 'id'),
                        ],
                        'gender'     => [
                            'nullable',
                            Rule::in('Male', 'Female', 'Other'),
                        ],
                    ];
                    break;
                case 6:
                    $rules += [
                        'status' =>  [
                            'required',
                            Rule::in(0, 1),
                        ],
                        'employment_type_id' =>  [
                            'nullable',
                            Rule::exists('employment_types', 'id'),
                        ],
                        'office_email' =>  [
                            'nullable',
                            'email:strict',
                            Rule::unique('users', 'email')->ignore($userId),
                        ],
                        'date_of_joining' =>  [
                            'nullable',
                            'date',
                        ],
                        'salary_start_date' =>  [
                            'nullable',
                            'date',
                        ],
                        'transfer_date' =>  [
                            'nullable',
                            'date',
                        ],
                        'probation_period_in_days' =>  [
                            'nullable',
                            'numeric'
                        ],
                        'confirmation_date' =>  [
                            'nullable',
                            'date',
                        ],
                        'last_working_date' =>  [
                            'nullable',
                            'date',
                        ],
                        'notice_period_employer' =>  [
                            'nullable',
                            'numeric'
                        ],
                        'notice_period_employee' =>  [
                            'nullable',
                            'numeric'
                        ],
                    ];
                    break;
                case 7:
                    $rules += [
                        'designation_id' => [
                            'nullable',
                            Rule::exists('designations', 'id'),
                        ],
                        'department_id' => [
                            'nullable',
                            Rule::exists('departments', 'id'),
                        ],
                        'location'  =>  [
                            'nullable'
                        ],
                        'effective_date'    =>  'nullable',
                        'date',
                    ];
                    break;
                case 9:
                    switch ($this->input('form')) {
                        case 1:
                            if ($this->input('key') == 'create') {
                                $rules += [
                                    'adhaar_no' => [
                                        'nullable',
                                        'min:12',
                                        'max:12',
                                        Rule::unique('employee_adhaars', 'adhaar_no')->where(function ($query) {
                                            $query->whereNull('deleted_at');
                                        }),
                                    ],
                                    'enrollment_no' => [
                                        'nullable',
                                        'min:14',
                                        'max:14',
                                        Rule::unique('employee_adhaars', 'enrollment_no')->where(function ($query) {
                                            $query->whereNull('deleted_at');
                                        }),
                                    ],
                                    'name' => [
                                        'nullable'
                                    ]
                                ];
                            } else if ($this->input('key') == 'update') {
                                $rules += [
                                    'adhaar_no' => [
                                        'nullable',
                                        'min:12',
                                        'max:12',
                                    ],
                                    'enrollment_no' => [
                                        'nullable',
                                        'min:14',
                                        'max:14',
                                    ],
                                    'name' => [
                                        'nullable'
                                    ]
                                ];
                            }
                            break;
                        case 2:
                            if ($this->input('key') == 'create') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:10',
                                        'max:10',
                                        Rule::unique('employee_pans', 'number')->where(function ($query) {
                                            $query->whereNull('deleted_at');
                                        }),
                                    ],
                                
                                    'name' => [
                                        'nullable'
                                    ]
                                ];
                            } else if ($this->input('key') == 'update') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:10',
                                        'max:10',
                                    ],
                                    'name' => [
                                        'nullable'
                                    ]
                                ];
                            }
                            break;
                        case 3:
                            if ($this->input('key') == 'create') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:10',
                                        'max:10',
                                        Rule::unique('employee_voter_cards', 'number')->where(function ($query) {
                                            $query->whereNull('deleted_at');
                                        }),
                                    ],
                                
                                    'name' => [
                                        'nullable'
                                    ]
                                ];
                            } else if ($this->input('key') == 'update') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:10',
                                        'max:10',
                                    ],
                                    'name' => [
                                        'nullable'
                                    ]
                                ];
                            }
                            break;
                        case 4:
                            if ($this->input('key') == 'create') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:15',
                                        'max:15',
                                        Rule::unique('employee_driving_licenses', 'number')->where(function ($query) {
                                            $query->whereNull('deleted_at');
                                        }),
                                    ],
                                
                                    'name' => [
                                        'nullable'
                                    ],
                                    'expiry_date' => [
                                        'date'
                                    ]
                                ];
                            } else if ($this->input('key') == 'update') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:15',
                                        'max:15',
                                    ],
                                    'name' => [
                                        'nullable'
                                    ],
                                    'expiry_date' => [
                                        'date'
                                    ]
                                ];
                            }
                            break;
                        case 5:
                            if ($this->input('key') == 'create') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:15',
                                        'max:15',
                                        Rule::unique('employee_passports', 'number')->where(function ($query) {
                                            $query->whereNull('deleted_at');
                                        }),
                                    ],
                                
                                    'name' => [
                                        'nullable'
                                    ],
                                    'country' => [
                                        'nullable'
                                    ],
                                    'expiry_date' => [
                                        'date'
                                    ],
                                    'issue_date' => [
                                        'date'
                                    ]
                                ];
                            } else if ($this->input('key') == 'update') {
                                $rules += [
                                    'number' => [
                                        'nullable',
                                        'min:15',
                                        'max:15',
                                    ],
                                    'name' => [
                                        'nullable'
                                    ],
                                    'country' => [
                                        'nullable'
                                    ],
                                    'expiry_date' => [
                                        'date'
                                    ],
                                    'issue_date' => [
                                        'date'
                                    ]
                                ];
                            }
                            break;
                    }
                case 10:
                    switch ($this->input('form')) {
                        case 1:
                            $rules += [
                                'personal_email'         => [
                                    'nullable',
                                    'email:strict',
                                    Rule::unique('employee_personal_details', 'personal_email')->ignore($userId),
                                ],
                                'marriage_date'   =>  [
                                    'nullable',
                                    'date',
                                ],
                                'confirmation_date'  =>  [
                                    'nullable',
                                    'date',
                                ]
                            ];
                            break;
                        case 2:
                            $rules += [
                                'wef'  =>  [
                                    'nullable',
                                    'date',
                                ],
                            ];
                            break;
                        case 3:
                            $rules += [
                                'gender'     => [
                                    'nullable',
                                    Rule::in('Male', 'Female', 'Other'),
                                ],
                                'marriage_date'   =>  [
                                    'nullable',
                                    'date',
                                ],
                                'date_of_birth'   =>  [
                                    'nullable',
                                    'date',
                                ],
                            ];
                            break;
                        case 4:
                            $rules += [
                                // 'number'   =>  [
                                //     'nullable',
                                //     Rule::unique('employee_driving_licenses', 'number'),
                                // ],
                                // 'name'  =>  [
                                //     'nullable'
                                // ],
                                // 'expiry_date'  =>  [
                                //     'nullable',
                                //     'date'
                                // ]
                            ];
                            break;
                        case 5:
                            $rules += [
                                'to_date' => 'nullable',
                                'date',
                                'from_date' => 'nullable',
                                'date',
                                'date_of_passing' => 'nullable',
                                'date',
                            ];
                            break;
                    }
            }

            $customMessages = [
                'required'          => 'The :attribute field is required.',
                'password.regex'    => 'Password must have at least one Uppercase, one Lowercase, one Number, and one Special Character.',
            ];

            return $rules;
        } catch (Exception $e) {

            Log::error('Validation Exception: ' . $e->getMessage());

            throw $e;
        }
    }
}
