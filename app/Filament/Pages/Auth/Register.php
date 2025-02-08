<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Forms\Components\Grid;
use Filament\Events\Auth\Registered;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Notifications\Notification;
use Tapp\FilamentGoogleAutocomplete\Forms\Components\GoogleAutocomplete;
use Filament\Forms;
use App\Services\RegionService;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Get;
use Filament\Forms\Components\Tabs;




class RegisterPage extends BaseRegister
{
    //model
    protected static string $model = \App\Models\User::class;

    //chnage login view
    protected static string $view = 'filament.pages.auth.register';

    public function form(Form $form): Form
    {
        $regionService = new RegionService();
    
        return $form
            ->schema([
                Grid::make(1) // Create a 2-column layout to place Fieldsets side by side
                    ->schema([
                        Tabs::make('Registration')
                            ->tabs([
                                Tabs\Tab::make('Personal Information')
                                    ->schema([
                                        // Group 1: Personal Information
                                        Fieldset::make('Personal Information')
                                            ->schema([
                                                Grid::make(2) // Internal 2-column layout for personal information fields
                                                    ->schema([
                                                        TextInput::make('first_name')->label('First Name')->required(),
                                                        TextInput::make('last_name')->label('Last Name')->required(),
                                                        TextInput::make('email')->label('Email Address')->required(),
                                                        PhoneInput::make('mobile_number')->label('Mobile Number'),
                                                        PhoneInput::make('work_number')->label('Work Number (Optional)'),
                                                        $this->getPasswordFormComponent(),  // Password fields
                                                        $this->getPasswordConfirmationFormComponent(),
                                                    ]),
                                            ]),
                    
                                        // Group 2: Organization Details
                                        ]),
                                Tabs\Tab::make('Other Addional Information')
                                    ->schema([
                                        Grid::make(2) // Internal 2-column layout for organization details
                                            ->schema([
                                                Select::make('organization_id')
                                                    ->label('Organisation Name')
                                                    ->required()
                                                    ->options(\App\Models\Organization::pluck('name', 'name')) // Manually populate options
                                                    ->createOptionForm([
                                                        TextInput::make('name')->required()->label('Organization Name'),
                                                    ])
                                                    ->createOptionUsing(function (array $data): int {
                                                        return \App\Models\Organization::create($data)->getKey();
                                                    })
                                                    ->label('Organisation Name'),
                                                GoogleAutocomplete::make('Address Search')
                                                    ->language('en')
                                                    ->label('Address Search')
                                                    ->countries(['AU'])
                                                    ->language('en')
                                                    ->withFields([
                                                        Forms\Components\TextInput::make('suburb')
                                                            ->extraInputAttributes(['data-google-field' => '{locality}']),
                                                        Forms\Components\TextInput::make('state')
                                                            ->extraInputAttributes(['data-google-field' => '{administrative_area_level_1}']),
                                                        Forms\Components\TextInput::make('postcode')
                                                            ->live()
                                                            ->reactive()
                                                            ->extraInputAttributes(['data-google-field' => '{postal_code}']),
                                                    ]),
                                                Fieldset::make("Additional Address Details") // Subgroup for additional address details
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('region')
                                                            ->label('Region')
                                                            ->live()
                                                            ->reactive()
                                                            ->content(function (Get $get) use ($regionService) {
                                                                return $regionService->getRegionByPostcode($get('postcode') ?? "");
                                                            })
                                                    ]),
                                                Checkbox::make('is_indigenous_organisation')
                                                    ->label('Indigenous Organisation? Must be 51% Indigenous Owned'),
                                            ]),
                                    ]),
                                ]),
                    ]),
            ]);
    }
    


    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (\App\Models\User::where('email', $data['email'])->exists()) {
            //throw notifcation
            Notification::make()
                ->title('Email Already Exists')
                ->warning()
                ->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            //handle name if blank
            if (!isset($data['name']) || empty($data['name'])) {
                $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
            }

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        $data = $this->form->getState();

        //create profile
        $contact = \App\Models\Contact::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile_number' => $data['mobile_number'],
            'work_number' => $data['work_number'],
            'organization_id' => $data['organization_id'],
            'suburb' => $data['suburb'],
            'post_code' => $data['postcode'],
            'state' => $data['state'],
            'status' => 'DRAFT',
            'is_indigenous_organisation' => $data['is_indigenous_organisation']
        ]);

        event(new Registered($user));

        //$this->sendEmailVerificationNotification($user);

        // Filament::auth()->login($user);

        // session()->regenerate();

        //redirect to login

        // return app(RegistrationResponse::class);

        return app(RegistrationResponse::class);
    }
}
